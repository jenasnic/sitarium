<?php

namespace App\Controller\Front\Maze;

use App\Service\Maze\ActorGraphBuilder;
use App\Service\Maze\ActorPathHelpFactory;
use App\Service\Maze\MaxPathFinder;
use App\Service\Maze\MazeItemConverter;
use App\Service\Maze\MinPathFinder;
use App\Service\Maze\RandomPathFinder;
use App\Tool\TmdbUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ActorPlayController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var ActorGraphBuilder
     */
    protected $graphBuilder;

    /**
     * @var ActorPathHelpFactory
     */
    protected $helpFactory;

    /**
     * @var MazeItemConverter
     */
    protected $mazeItemConverter;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @param TranslatorInterface $translator
     * @param ActorGraphBuilder $graphBuilder
     * @param ActorPathHelpFactory $helpFactory
     * @param MazeItemConverter $mazeItemConverter
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        TranslatorInterface $translator,
        ActorGraphBuilder $graphBuilder,
        ActorPathHelpFactory $helpFactory,
        MazeItemConverter $mazeItemConverter,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->translator = $translator;
        $this->graphBuilder = $graphBuilder;
        $this->helpFactory = $helpFactory;
        $this->mazeItemConverter = $mazeItemConverter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/quiz-filmographie/jouer", name="fo_maze_actor_play", methods="GET")
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

            return $this->redirectToRoute('fo_maze_actor');
        }

        try {
            $minVoteCount = TmdbUtil::getMinVoteCountForLevel($level);

            $actorGraph = $this->graphBuilder->buildGraph(null, $minVoteCount);
            $actorPath = $randomPathFinder->find($actorGraph, $count);
            $replayUrl = $this->urlGenerator->generate('fo_maze_actor_play', [
                'count' => $count,
                'level' => $level,
            ]);

            return $this->renderPlayView($actorPath, $minVoteCount, $level, $replayUrl);
        } catch (\Exception $ex) {
            $this->addFlash('error', $this->translator->trans('front.maze.initialization_error'));

            return $this->redirectToRoute('fo_maze_actor');
        }
    }

    /**
     * @Route("/quiz-filmographie/plus-court-chemin/jouer", name="fo_maze_actor_play_min_path", methods="POST")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param Request $request
     * @param MinPathFinder $minPathFinder
     *
     * @return Response
     */
    public function minPathAction(Request $request, MinPathFinder $minPathFinder): Response
    {
        $startActorId = $request->request->get('startTmdbId');
        $endActorId = $request->request->get('endTmdbId');
        $level = $request->request->get('level');

        try {
            $minVoteCount = TmdbUtil::getMinVoteCountForLevel($level);

            $actorGraph = $this->graphBuilder->buildGraph(null, $minVoteCount);
            $actorPath = $minPathFinder->find($actorGraph, $actorGraph[$startActorId], $actorGraph[$endActorId]);
            $replayUrl = $this->urlGenerator->generate('fo_maze_actor_select_min_path');

            return $this->renderPlayView($actorPath, $minVoteCount, $level, $replayUrl);
        } catch (\Exception $ex) {
            $this->addFlash('error', $this->translator->trans('front.maze.initialization_error'));

            return $this->redirectToRoute('actor_maze_fo');
        }
    }

    /**
     * @Route("/quiz-filmographie/plus-long-chemin/jouer", name="fo_maze_actor_play_max_path", methods="POST")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param Request $request
     * @param MaxPathFinder $maxPathFinder
     *
     * @return Response
     */
    public function maxPathAction(Request $request, MaxPathFinder $maxPathFinder): Response
    {
        $actorIds = explode(',', $request->request->get('tmdbIds'));
        $level = $request->request->get('level');

        try {
            $minVoteCount = TmdbUtil::getMinVoteCountForLevel($level);

            $actorGraph = $this->graphBuilder->buildGraph($actorIds, $minVoteCount);
            $actorPath = $maxPathFinder->find($actorGraph);
            $replayUrl = $this->urlGenerator->generate('fo_maze_actor_select_max_path');

            return $this->renderPlayView($actorPath, $minVoteCount, $level, $replayUrl);
        } catch (\Exception $ex) {
            $this->addFlash('error', $this->translator->trans('front.maze.initialization_error'));

            return $this->redirectToRoute('actor_maze_fo');
        }
    }

    /**
     * @param array $actorPath
     * @param int $minVoteCount
     * @param int $level
     * @param string $replayUrl
     *
     * @return Response
     */
    protected function renderPlayView(array $actorPath, int $minVoteCount, int $level, string $replayUrl): Response
    {
        $helpMovieList = $this->helpFactory->getMovies($actorPath, $minVoteCount, $level);

        return $this->render('front/maze/actor/play.html.twig', [
            'mazePath' => $this->mazeItemConverter->convertActors($actorPath),
            'helpList' => $this->mazeItemConverter->convertFilmographyMovies($helpMovieList),
            'responseRoute' => 'fo_maze_actor_progress',
            'trickRoute' => 'fo_maze_actor_trick',
            'level' => $level,
            'replayUrl' => $replayUrl,
        ]);
    }
}
