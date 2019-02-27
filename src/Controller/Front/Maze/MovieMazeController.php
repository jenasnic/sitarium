<?php

namespace App\Controller\Front\Maze;

use App\Enum\Maze\CastingStatus;
use App\Repository\Maze\MovieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieMazeController extends AbstractController
{
    /**
     * @Route("/quiz-casting", name="fo_maze_movie")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('front/maze/movie/index.html.twig');
    }

    /**
     * @Route("/quiz-casting/plus-court-chemin", name="fo_maze_movie_select_min_path")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param MovieRepository $movieRepository
     *
     * @return Response
     */
    public function selectMinPathAction(MovieRepository $movieRepository): Response
    {
        return $this->render('front/maze/movie/min_path_selection.html.twig', [
            'movies' => $movieRepository->findBy(['status' => CastingStatus::INITIALIZED], ['title' => 'asc']),
        ]);
    }

    /**
     * @Route("/quiz-casting/plus-long-chemin", name="fo_maze_movie_select_max_path")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param MovieRepository $movieRepository
     *
     * @return Response
     */
    public function selectMaxPathAction(MovieRepository $movieRepository): Response
    {
        return $this->render('front/maze/movie/max_path_selection.html.twig', [
            'movies' => $movieRepository->findBy(['status' => CastingStatus::INITIALIZED], ['title' => 'asc']),
        ]);
    }
}
