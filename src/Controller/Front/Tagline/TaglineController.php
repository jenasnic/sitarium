<?php

namespace App\Controller\Front\Tagline;

use App\Entity\Tagline\Genre;
use App\Entity\Tagline\Movie;
use App\Repository\Tagline\GenreRepository;
use App\Repository\Tagline\MovieRepository;
use App\Service\Tmdb\DisplayableResultAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TaglineController extends AbstractController
{
    /**
     * @Route("/quiz-tagline", name="fo_tagline")
     */
    public function taglineAction(GenreRepository $repository): Response
    {
        return $this->render('front/tagline/index.html.twig', ['genres' => $repository->getInfos()]);
    }

    /**
     * @Route("/quiz-tagline/{slug}", name="fo_tagline_genre")
     */
    public function playAction(
        Request $request,
        TranslatorInterface $translator,
        MovieRepository $repository,
        DisplayableResultAdapter $displayableResultAdapter,
        Genre $genre
    ): Response {
        $count = intval($request->query->get('count'));

        if (!in_array($count, [5, 6, 7, 8, 9, 10])) {
            $this->addFlash('warning', $translator->trans('front.tagline.play.invalid_parameters'));

            return $this->redirectToRoute('fo_tagline');
        }

        $movies = $repository->findByGenre($genre->getTmdbId());
        shuffle($movies);
        $movies = array_slice($movies, 0, 2 * $count);

        $taglines = array_slice($movies, 0, $count);
        shuffle($taglines);

        // Sort list alphabetically for available responses
        usort($movies, function (Movie $movie1, Movie $movie2) {
            return strcmp($movie1->getTitle(), $movie2->getTitle());
        });

        return $this->render('front/tagline/play.html.twig', [
            'genre' => $genre,
            'taglines' => $taglines,
            'movies' => $displayableResultAdapter->adaptArray($movies),
        ]);
    }
}
