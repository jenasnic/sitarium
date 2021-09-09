<?php

namespace App\Controller\Front;

use App\Entity\Tracking;
use App\Repository\TrackingRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrackingController extends AbstractController
{
    /**
     * @Route("/quiz-en-image", name="fo_tracking")
     */
    public function newAction(
        Request $request,
        EntityManagerInterface $entityManager,
        TrackingRepository $trackingRepository
    ): Response {
        $ip = $request->getClientIp();

        if (!$trackingRepository->ipAlreadyTracked($ip)) {
            $tracking = new Tracking();

            $tracking->setIp(($ip));
            $tracking->setTrace(implode(', ', $request->getClientIps()));
            $tracking->setDate(new DateTime());
            $tracking->setUserAgent($request->headers->get('user-agent', '-'));

            $entityManager->persist($tracking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fo_quiz');
    }
}
