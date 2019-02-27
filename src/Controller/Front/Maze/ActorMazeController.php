<?php

namespace App\Controller\Front\Maze;

use App\Enum\Maze\FilmographyStatus;
use App\Repository\Maze\ActorRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActorMazeController extends AbstractController
{
    /**
     * @Route("/quiz-filmographie", name="fo_maze_actor")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('front/maze/actor/index.html.twig');
    }

    /**
     * @Route("/quiz-filmographie/plus-court-chemin", name="fo_maze_actor_select_min_path")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param ActorRepository $actorRepository
     *
     * @return Response
     */
    public function selectMinPathAction(ActorRepository $actorRepository): Response
    {
        return $this->render('front/maze/actor/min_path_selection.html.twig', [
            'actors' => $actorRepository->findBy(['status' => FilmographyStatus::INITIALIZED], ['fullname' => 'asc']),
        ]);
    }

    /**
     * @Route("/quiz-filmographie/plus-long-chemin", name="fo_maze_actor_select_max_path")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param ActorRepository $actorRepository
     *
     * @return Response
     */
    public function selectMaxPathAction(ActorRepository $actorRepository): Response
    {
        return $this->render('front/maze/actor/max_path_selection.html.twig', [
            'actors' => $actorRepository->findBy(['status' => FilmographyStatus::INITIALIZED], ['fullname' => 'asc']),
        ]);
    }
}
