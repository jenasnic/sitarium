<?php

namespace App\Controller\Front;

use App\Entity\Quiz\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PictureQuizController extends Controller
{
    /**
     * @Route("/quiz", name="fo_quiz")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('front/home.html.twig');
    }

    /**
     * @Route("/quiz/jouer/{quiz}", requirements={"quiz" = "\d+"}, name="fo_quiz_play")
     *
     * @param Quiz $quiz
     *
     * @return Response
     */
    public function playAction(Quiz $quiz)
    {
        return $this->render('front/home.html.twig');
    }
}
