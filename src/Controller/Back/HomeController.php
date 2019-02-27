<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/admin", name="bo_home")
     *
     * @return Response
     */
    public function homeAction(): Response
    {
        return $this->render('back/home.html.twig');
    }
}
