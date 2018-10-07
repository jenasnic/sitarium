<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="fo_home")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        return $this->render('front/home.html.twig');
    }

    /**
     * @Route("/autres-quiz", name="fo_other_quiz")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function otherQuizAction() {
        return $this->render('front/other_quiz.html.twig');
    }
}
