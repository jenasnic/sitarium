<?php

namespace App\Controller\Front\Maze;

use App\Service\Maze\MaxPathFinder;
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
     * @param MovieGraphBuilder $graphBuilder
     * @param MoviePathHelpFactory $helpFactory
     */
    public function __construct(MovieGraphBuilder $graphBuilder, MoviePathHelpFactory $helpFactory)
    {
        $this->graphBuilder = $graphBuilder;
        $this->helpFactory = $helpFactory;
    }

    /**
     * @Route("/quiz-casting/jouer", name="fo_maze_movie_play", methods="POST")
     *
     * @param Request $request
     * @param RandomPathFinder $randomPathFinder
     *
     * @return Response
     */
    public function playAction(Request $request, RandomPathFinder $randomPathFinder): Response
    {
        $count = $request->request->get('count');
        $level = $request->request->get('level');

        if (!in_array($count, [3, 4, 5, 6, 7, 8, 9]) || !in_array($level, [0, 1, 2])) {
            $this->addFlash('warning', 'Les paramètres spécifiés pour la page sont incorrects.');

            return $this->redirectToRoute('fo_maze_movie');
        }

        try {
            $movieGraph = $this->graphBuilder->buildGraph();
            $moviePath = $randomPathFinder->find($movieGraph, $count);

            return $this->render('front/maze/movie/play.html.twig', [
                'moviePath' => $moviePath,
                'helpActorList' => $this->helpFactory->getShuffledActors($moviePath, $level),
            ]);
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

            return $this->render('front/maze/movie/play.html.twig', [
                'moviePath' => $moviePath,
                'helpActorList' => $this->helpFactory->getShuffledActors($moviePath, $level),
            ]);
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

            return $this->render('front/maze/movie/play.html.twig', [
                'moviePath' => $moviePath,
                'helpActorList' => $this->helpFactory->getShuffledActors($moviePath, $level),
            ]);
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'initialisation du quiz.');

            return $this->redirectToRoute('movie_maze_fo');
        }
    }
}
