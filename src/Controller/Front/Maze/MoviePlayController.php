<?php

namespace App\Controller\Front\Maze;

use App\Service\Maze\MaxPathFinder;
use App\Service\Maze\MazeItemConverter;
use App\Service\Maze\MinPathFinder;
use App\Service\Maze\MovieGraphBuilder;
use App\Service\Maze\MoviePathHelpFactory;
use App\Service\Maze\RandomPathFinder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviePlayController extends Controller
{
    /**
     * @var MovieGraphBuilder
     */
    protected $graphBuilder;

    /**
     * @var MoviePathHelpFactory
     */
    protected $helpFactory;

    /**
     * @var MazeItemConverter
     */
    protected $mazeItemConverter;

    /**
     * @param MovieGraphBuilder $graphBuilder
     * @param MoviePathHelpFactory $helpFactory
     * @param MazeItemConverter $mazeItemConverter
     */
    public function __construct(
        MovieGraphBuilder $graphBuilder,
        MoviePathHelpFactory $helpFactory,
        MazeItemConverter $mazeItemConverter
    ) {
        $this->graphBuilder = $graphBuilder;
        $this->helpFactory = $helpFactory;
        $this->mazeItemConverter = $mazeItemConverter;
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
            $this->addFlash('warning', 'Les paramètres spécifiés pour la page sont incorrects.');

            return $this->redirectToRoute('fo_maze_movie');
        }

        try {
            $movieGraph = $this->graphBuilder->buildGraph();
            $moviePath = $randomPathFinder->find($movieGraph, $count);

            return $this->renderPlayView($moviePath, $level);
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'initialisation du quiz.');

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
        $startMovieId = $request->request->get('startMovieId');
        $endMovieId = $request->request->get('endMovieId');
        $level = $request->request->get('level');

        try {
            $movieGraph = $this->graphBuilder->buildGraph();
            $moviePath = $minPathFinder->find($movieGraph, $movieGraph[$startMovieId], $movieGraph[$endMovieId]);

            return $this->renderPlayView($moviePath, $level);
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'initialisation du quiz.');

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
        $movieIds = explode(',', $request->request->get('movieIds'));
        $level = $request->request->get('level');

        try {
            $movieGraph = $this->graphBuilder->buildGraph($movieIds);
            $moviePath = $maxPathFinder->find($movieGraph);

            return $this->renderPlayView($moviePath, $level);
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'initialisation du quiz.');

            return $this->redirectToRoute('movie_maze_fo');
        }
    }

    /**
     * @param array $actorPath
     * @param int $level
     *
     * @return Response
     */
    protected function renderPlayView(array $moviePath, int $level): Response
    {
        $helpActorList = $this->helpFactory->getActors($moviePath, $level);

        return $this->render('front/maze/movie/play.html.twig', [
            'mazePath' => $this->mazeItemConverter->convertMovies($moviePath),
            'helpList' => $this->mazeItemConverter->convertCastingActors($helpActorList),
            'responseRoute' => 'fo_maze_movie_progress',
            'trickRoute' => 'fo_maze_movie_trick',
            'level' => $level,
        ]);

    }
}
