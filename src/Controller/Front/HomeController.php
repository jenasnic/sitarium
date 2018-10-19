<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="fo_home")
     *
     * @return Response
     */
    public function homeAction(): Response
    {
        return $this->render('front/home.html.twig');
    }

    /**
     * @Route("/autres-quiz", name="fo_other_quiz")
     *
     * @return Response
     */
    public function otherQuizAction(): Response
    {
        return $this->render('front/other_quiz.html.twig');
    }
}