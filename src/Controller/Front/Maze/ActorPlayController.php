<?php

namespace App\Controller\Front\Maze;

use App\Entity\Maze\Actor;
use App\Service\Maze\ActorGraphBuilder;
use App\Service\Maze\ActorPathHelpFactory;
use App\Service\Maze\MaxPathFinder;
use App\Service\Maze\MinPathFinder;
use App\Service\Maze\RandomPathFinder;
use App\Service\Tmdb\DisplayableResultAdapter;
use App\Tool\TmdbUtil;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ActorPlayController extends AbstractController
{
    protected TranslatorInterface $translator;

    protected DisplayableResultAdapter $displayableResultAdapter;

    protected ActorGraphBuilder $graphBuilder;

    protected ActorPathHelpFactory $helpFactory;

    protected UrlGeneratorInterface $urlGenerator;

    public function __construct(
        TranslatorInterface $translator,
        DisplayableResultAdapter $displayableResultAdapter,
        ActorGraphBuilder $graphBuilder,
        ActorPathHelpFactory $helpFactory,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->translator = $translator;
        $this->displayableResultAdapter = $displayableResultAdapter;
        $this->graphBuilder = $graphBuilder;
        $this->helpFactory = $helpFactory;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/quiz-filmographie/jouer", name="fo_maze_actor_play", methods="GET")
     */
    public function playAction(Request $request, RandomPathFinder $randomPathFinder): Response
    {
        $count = intval($request->query->get('count'));
        $level = intval($request->query->get('level'));

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
        } catch (Exception $ex) {
            $this->addFlash('error', $this->translator->trans('front.maze.initialization_error'));

            return $this->redirectToRoute('fo_maze_actor');
        }
    }

    /**
     * @Route("/quiz-filmographie/plus-court-chemin/jouer", name="fo_maze_actor_play_min_path", methods="POST")
     * @Security("is_granted('ROLE_USER')")
     */
    public function minPathAction(Request $request, MinPathFinder $minPathFinder): Response
    {
        $startActorId = intval($request->request->get('startTmdbId'));
        $endActorId = intval($request->request->get('endTmdbId'));
        $level = intval($request->request->get('level'));

        try {
            $minVoteCount = TmdbUtil::getMinVoteCountForLevel($level);

            $actorGraph = $this->graphBuilder->buildGraph(null, $minVoteCount);
            $actorPath = $minPathFinder->find($actorGraph, $actorGraph[$startActorId], $actorGraph[$endActorId]);
            $replayUrl = $this->urlGenerator->generate('fo_maze_actor_select_min_path');

            return $this->renderPlayView($actorPath, $minVoteCount, $level, $replayUrl);
        } catch (Exception $ex) {
            $this->addFlash('error', $this->translator->trans('front.maze.initialization_error'));

            return $this->redirectToRoute('actor_maze_fo');
        }
    }

    /**
     * @Route("/quiz-filmographie/plus-long-chemin/jouer", name="fo_maze_actor_play_max_path", methods="POST")
     * @Security("is_granted('ROLE_USER')")
     */
    public function maxPathAction(Request $request, MaxPathFinder $maxPathFinder): Response
    {
        /** @var array<int> $actorIds */
        $actorIds = explode(',', $request->request->get('tmdbIds'));
        $level = intval($request->request->get('level'));

        try {
            $minVoteCount = TmdbUtil::getMinVoteCountForLevel($level);

            $actorGraph = $this->graphBuilder->buildGraph($actorIds, $minVoteCount);
            $actorPath = $maxPathFinder->find($actorGraph);
            $replayUrl = $this->urlGenerator->generate('fo_maze_actor_select_max_path');

            return $this->renderPlayView($actorPath, $minVoteCount, $level, $replayUrl);
        } catch (Exception $ex) {
            $this->addFlash('error', $this->translator->trans('front.maze.initialization_error'));

            return $this->redirectToRoute('actor_maze_fo');
        }
    }

    /**
     * @param array<Actor> $actorPath
     */
    protected function renderPlayView(array $actorPath, int $minVoteCount, int $level, string $replayUrl): Response
    {
        $helpMovieList = $this->helpFactory->getMovies($actorPath, $minVoteCount, $level);
        $displayableMovieList = $this->displayableResultAdapter->adaptArray($helpMovieList);

        $displayableActorPath = $this->displayableResultAdapter->adaptArray($actorPath);

        return $this->render('front/maze/actor/play.html.twig', [
            'mazePath' => $displayableActorPath,
            'helpList' => $displayableMovieList,
            'responseRoute' => 'fo_maze_actor_progress',
            'trickRoute' => 'fo_maze_actor_trick',
            'cheatRoute' => 'fo_maze_actor_cheat',
            'level' => $level,
            'replayUrl' => $replayUrl,
        ]);
    }
}
