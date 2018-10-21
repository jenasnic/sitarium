<?php

namespace App\Controller\Front\Maze;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MazeController extends Controller
{
    /**
     * @Route("/quiz-domino", name="fo_maze")
     *
     * @return Response
     */
    public function mazeAction(): Response
    {
        return $this->render('front/maze/index.html.twig');
    }
}
