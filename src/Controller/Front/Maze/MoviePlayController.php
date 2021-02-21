<?php

namespace App\Controller\Front\Maze;

use App\Service\Maze\MaxPathFinder;
use App\Service\Maze\MinPathFinder;
use App\Service\Maze\MovieGraphBuilder;
use App\Service\Maze\MoviePathHelpFactory;
use App\Service\Maze\RandomPathFinder;
use App\Service\Tmdb\DisplayableResultAdapter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MoviePlayController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var DisplayableResultAdapter
     */
    protected $displayableResultAdapter;

    /**
     * @var MovieGraphBuilder
     */
    protected $graphBuilder;

    /**
     * @var MoviePathHelpFactory
     */
    protected $helpFactory;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @param TranslatorInterface $translator
     * @param DisplayableResultAdapter $displayableResultAdapter
     * @param MovieGraphBuilder $graphBuilder
     * @param MoviePathHelpFactory $helpFactory
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        TranslatorInterface $translator,
        DisplayableResultAdapter $displayableResultAdapter,
        MovieGraphBuilder $graphBuilder,
        MoviePathHelpFactory $helpFactory,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->translator = $translator;
        $this->displayableResultAdapter = $displayableResultAdapter;
        $this->graphBuilder = $graphBuilder;
        $this->helpFactory = $helpFactory;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/quiz-casting/jouer", name="fo_maze_movie_play", methods="GET")
     *
     * @param Request $request
     * @param RandomPathFinder $randomPathFinder
     *
     * @return Response
     */
    public function playAction(Request $request, RandomPathFinder $randomPathFinder): Response
    {
        $count = $request->query->get('count');
        $level = $request->query->get('level');

        if (!in_array($count, [3, 4, 5, 6, 7, 8, 9]) || !in_array($level, [0, 1, 2])) {
            $this->addFlash('warning', $this->translator->trans('front.maze.invalid_parameters'));

            return $this->redirectToRoute('fo_maze_movie');
        }

        try {
            $movieGraph = $this->graphBuilder->buildGraph();
            $moviePath = $randomPathFinder->find($movieGraph, $count);
            $replayUrl = $this->urlGenerator->generate('fo_maze_movie_play', [
                'count' => $count,
                'level' => $level,
            ]);

            return $this->renderPlayView($moviePath, $level, $replayUrl);
        } catch (\Exception $ex) {
            $this->addFlash('error', $this->translator->trans('front.maze.initialization_error'));

            return $this->redirectToRoute('fo_maze_movie');
        }
    }

    /**
     * @Route("/quiz-casting/plus-court-chemin/jouer", name="fo_maze_movie_play_min_path", methods="POST")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param Request $request
     * @param MinPathFinder $minPathFinder
     *
     * @return Response
     */
    public function minPathAction(Request $request, MinPathFinder $minPathFinder): Response
    {
        $startMovieId = $request->request->get('startTmdbId');
        $endMovieId = $request->request->get('endTmdbId');
        $level = $request->request->get('level');

        try {
            $movieGraph = $this->graphBuilder->buildGraph();
            $moviePath = $minPathFinder->find($movieGraph, $movieGraph[$startMovieId], $movieGraph[$endMovieId]);
            $replayUrl = $this->urlGenerator->generate('fo_maze_movie_select_min_path');

            return $this->renderPlayView($moviePath, $level, $replayUrl);
        } catch (\Exception $ex) {
            $this->addFlash('error', $this->translator->trans('front.maze.initialization_error'));

            return $this->redirectToRoute('movie_maze_fo');
        }
    }

    /**
     * @Route("/quiz-casting/plus-long-chemin/jouer", name="fo_maze_movie_play_max_path", methods="POST")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param Request $request
     * @param MaxPathFinder $maxPathFinder
     *
     * @return Response
     */
    public function maxPathAction(Request $request, MaxPathFinder $maxPathFinder): Response
    {
        $movieIds = explode(',', $request->request->get('tmdbIds'));
        $level = $request->request->get('level');

        try {
            $movieGraph = $this->graphBuilder->buildGraph($movieIds);
            $moviePath = $maxPathFinder->find($movieGraph);
            $replayUrl = $this->urlGenerator->generate('fo_maze_movie_select_max_path');

            return $this->renderPlayView($moviePath, $level, $replayUrl);
        } catch (\Exception $ex) {
            $this->addFlash('error', $this->translator->trans('front.maze.initialization_error'));

            return $this->redirectToRoute('movie_maze_fo');
        }
    }

    /**
     * @param array $actorPath
     * @param int $level
     * @param string $replayUrl
     *
     * @return Response
     */
    protected function renderPlayView(array $moviePath, int $level, string $replayUrl): Response
    {
        $helpActorList = $this->helpFactory->getActors($moviePath, $level);
        $displayableActorList = $this->displayableResultAdapter->adaptArray($helpActorList);

        $displayableMoviePath = $this->displayableResultAdapter->adaptArray($moviePath);

        return $this->render('front/maze/movie/play.html.twig', [
            'mazePath' => $displayableMoviePath,
            'helpList' => $displayableActorList,
            'responseRoute' => 'fo_maze_movie_progress',
            'trickRoute' => 'fo_maze_movie_trick',
            'cheatRoute' => 'fo_maze_movie_cheat',
            'level' => $level,
            'replayUrl' => $replayUrl,
        ]);
    }
}
