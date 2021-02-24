<?php

namespace App\Controller\Back\Tagline;

use App\Entity\Tagline\Movie;
use App\Enum\PagerEnum;
use App\Repository\Tagline\MovieRepository;
use App\Repository\Tmdb\BuildProcessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListController extends AbstractController
{
    /**
     * @Route("/admin/tagline/movie/list", name="bo_tagline_movie_list")
     */
    public function listAction(
        Request $request,
        MovieRepository $movieRepository,
        BuildProcessRepository $buildProcessRepository
    ): Response {
        $page = intval($request->query->get('page', '1'));
        $title = $request->query->get('value', null);

        return $this->render('back/tagline/movie/list.html.twig', [
            'pager' => $movieRepository->getPager($title, $page, PagerEnum::DEFAULT_MAX_PER_PAGE),
            'value' => $title,
            'pendingProcess' => $buildProcessRepository->findPendingProcess(),
        ]);
    }

    /**
     * @Route("/admin/tagline/movie/view/{movie}", requirements={"movie": "\d+"}, name="bo_tagline_movie_view")
     */
    public function viewAction(Movie $movie): Response
    {
        return $this->render('back/tagline/movie/view.html.twig', ['movie' => $movie]);
    }

    /**
     * @Route("/admin/tagline/movie/delete/{movie}", requirements={"movie": "\d+"}, name="bo_tagline_movie_delete")
     */
    public function deleteAction(
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        Movie $movie
    ): Response {
        try {
            $entityManager->remove($movie);
            $entityManager->flush();

            $this->addFlash('info', $translator->trans('back.global.delete.success'));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_tagline_movie_list');
    }
}
