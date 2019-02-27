<?php

namespace App\Controller\Back\Tagline;

use App\Entity\Tagline\Movie;
use App\Repository\Tagline\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListController extends AbstractController
{
    /**
     * @Route("/admin/tagline/movie/list", name="bo_tagline_movie_list")
     *
     * @param MovieRepository $movieRepository
     *
     * @return Response
     */
    public function listAction(MovieRepository $movieRepository): Response
    {
        return $this->render('back/tagline/movie/list.html.twig', [
            'movies' => $movieRepository->findBy([], ['title' => 'asc']),
        ]);
    }

    /**
     * @Route("/admin/tagline/movie/view/{movie}", requirements={"movie" = "\d+"}, name="bo_tagline_movie_view")
     *
     * @param Movie $movie
     *
     * @return Response
     */
    public function viewAction(Movie $movie): Response
    {
        return $this->render('back/tagline/movie/view.html.twig', ['movie' => $movie]);
    }

    /**
     * @Route("/admin/tagline/movie/delete/{movie}", requirements={"movie" = "\d+"}, name="bo_tagline_movie_delete")
     *
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @param Movie $movie
     *
     * @return Response
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
        } catch (\Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_tagline_movie_list');
    }
}
