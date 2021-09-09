<?php

namespace App\Controller\Back;

use App\Entity\Tracking;
use App\Enum\PagerEnum;
use App\Repository\TrackingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TrackingController extends AbstractController
{
    /**
     * @Route("/admin/tracking/list", name="bo_tracking_list")
     */
    public function listAction(Request $request, TrackingRepository $trackingRepository): Response
    {
        $page = intval($request->query->get('page', '1'));

        return $this->render('back/tracking/list.html.twig', [
            'pager' => $trackingRepository->getPager($page, PagerEnum::DEFAULT_MAX_PER_PAGE),
        ]);
    }

    /**
     * @Route("/admin/tracking/delete/{tracking}", requirements={"tracking": "\d+"}, name="bo_tracking_delete")
     */
    public function deleteAction(
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        Tracking $tracking
    ): Response {
        try {
            $entityManager->remove($tracking);
            $entityManager->flush();

            $this->addFlash('info', $translator->trans('back.global.delete.success'));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_tracking_list');
    }

    /**
     * @Route("/admin/tracking/clear", name="bo_tracking_clear")
     */
    public function clearAction(
        TrackingRepository $trackingRepository,
        TranslatorInterface $translator
    ): Response {
        try {
            $trackingRepository->clearTracking();

            $this->addFlash('info', $translator->trans('back.global.delete.success'));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_tracking_list');
    }
}
