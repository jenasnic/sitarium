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

class ActorPlayController extends Controller
{
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
     * @param ActorGraphBuilder $graphBuilder
     * @param ActorPathHelpFactory $helpFactory
     * @param MazeItemConverter $mazeItemConverter
     */
    public function __construct(
        ActorGraphBuilder $graphBuilder,
        ActorPathHelpFactory $helpFactory,
        MazeItemConverter $mazeItemConverter
    ) {
        $this->graphBuilder = $graphBuilder;
        $this->helpFactory = $helpFactory;
        $this->mazeItemConverter = $mazeItemConverter;
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
            $this->addFlash('warning', 'Les paramètres spécifiés pour la page sont incorrects.');

            return $this->redirectToRoute('fo_maze_actor');
        }

        try {
            $minVoteCount = TmdbUtil::getMinVoteCountForLevel($level);

            $actorGraph = $this->graphBuilder->buildGraph(null, $minVoteCount);
            $actorPath = $randomPathFinder->find($actorGraph, $count);

            $helpMovieList = $this->helpFactory->getShuffledMovies($actorPath, $minVoteCount, $level);
            return $this->render('front/maze/actor/play.html.twig', [
                'mazePath' => $this->mazeItemConverter->convertActors($actorPath),
                'helpList' => $this->mazeItemConverter->convertFilmographyMovies($helpMovieList),
            ]);
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'initialisation du quiz.');

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
        $startActorId = $request->request->get('startActorId');
        $endActorId = $request->request->get('endActorId');
        $level = $request->request->get('level');

        try {
            $minVoteCount = TmdbUtil::getMinVoteCountForLevel($level);

            $actorGraph = $this->graphBuilder->buildGraph(null, $minVoteCount);
            $actorPath = $minPathFinder->find($actorGraph, $actorGraph[$startActorId], $actorGraph[$endActorId]);

            return $this->render('front/maze/actor/play.html.twig', [
                'actorPath' => $actorPath,
                'helpMovieList' => $this->helpFactory->getShuffledMovies($actorPath, $minVoteCount, $level),
            ]);
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'initialisation du quiz.');

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
        $actorIds = explode(',', $request->request->get('actorIds'));
        $level = $request->request->get('level');

        try {
            $minVoteCount = TmdbUtil::getMinVoteCountForLevel($level);

            $actorGraph = $this->graphBuilder->buildGraph($actorIds, $minVoteCount);
            $actorPath = $maxPathFinder->find($actorGraph);

            return $this->render('front/maze/actor/play.html.twig', [
                'actorPath' => $actorPath,
                'helpMovieList' => $this->helpFactory->getShuffledMovies($actorPath, $minVoteCount, $level),
            ]);
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'initialisation du quiz.');

            return $this->redirectToRoute('actor_maze_fo');
        }
    }
}
