<?php

namespace App\Controller\Front\Tagline;

use App\Entity\Tagline\Genre;
use App\Repository\Tagline\GenreRepository;
use App\Repository\Tagline\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TaglineController extends Controller
{
    /**
     * @Route("/quiz-tagline", name="fo_tagline")
     *
     * @param GenreRepository $repository
     *
     * @return Response
     */
    public function taglineAction(GenreRepository $repository): Response
    {
        return $this->render('front/tagline/index.html.twig', ['genres' => $repository->getInfos()]);
    }

    /**
     * @Route("/quiz-tagline/{slug}", name="fo_tagline_genre")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param MovieRepository $repository
     * @param Genre $genre
     *
     * @return Response
     */
    public function playAction(
        Request $request,
        TranslatorInterface $translator,
        MovieRepository $repository,
        Genre $genre): Response
    {
        $count = $request->query->get('count');

        if (!in_array($count, [5, 6, 7, 8, 9, 10])) {
            $this->addFlash('warning', $translator->trans('front.tagline.play.invalid_parameters'));

            return $this->redirectToRoute('fo_tagline');
        }

        $movies = $repository->findByGenre($genre->getTmdbId());
        shuffle($movies);
        $movies = array_slice($movies, 0, 2*$count);

        $taglines = array_slice($movies, 0, $count);
        shuffle($taglines);
        shuffle($movies);

        return $this->render('front/tagline/play.html.twig', [
            'genre' => $genre,
            'taglines' => $taglines,
            'movies' => $movies,
        ]);
    }
}
