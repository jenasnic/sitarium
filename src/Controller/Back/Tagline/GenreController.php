<?php

namespace App\Controller\Back\Tagline;

use App\Entity\Tagline\Genre;
use App\Repository\Tagline\GenreRepository;
use App\Service\Tagline\GenreSynchronizer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class GenreController extends AbstractController
{
    /**
     * @Route("/admin/tagline/genre/list", name="bo_tagline_genre_list")
     */
    public function listAction(GenreRepository $genreRepository): Response
    {
        return $this->render('back/tagline/genre/list.html.twig', [
            'genreInfos' => $genreRepository->getInfos(),
            'unusedGenres' => $genreRepository->findUnusedGenres(),
        ]);
    }

    /**
     * @Route("/admin/tagline/genre/delete/{genre}", requirements={"genre" = "\d+"}, name="bo_tagline_genre_delete")
     */
    public function deleteAction(
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        Genre $genre
    ): Response {
        try {
            $entityManager->remove($genre);
            $entityManager->flush();

            $this->addFlash('info', $translator->trans('back.global.delete.success'));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.global.delete.error'));
        }

        return $this->redirectToRoute('bo_tagline_genre_list');
    }

    /**
     * @Route("/admin/tagline/genre/synchronize", name="bo_tagline_genre_synchronize")
     */
    public function synchronizeAction(GenreSynchronizer $synchronizer, TranslatorInterface $translator): Response
    {
        try {
            $synchronizer->synchronize();
            $this->addFlash('info', $translator->trans('back.tagline.genre.synchronize.success'));
        } catch (Exception $e) {
            $this->addFlash('error', $translator->trans('back.tagline.genre.synchronize.error'));
        }

        return $this->redirectToRoute('bo_tagline_genre_list');
    }
}
