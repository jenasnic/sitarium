<?php

namespace App\Controller\Back\Tmdb;

use App\Enum\Tmdb\TypeEnum;
use App\Service\Tmdb\DisplayableResultAdapter;
use App\Service\Tmdb\TmdbDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SearchController extends AbstractController
{
    /**
     * @Route("/admin/tmdb/search/{type}", name="bo_tmdb_search", requirements={"type"="\w+"})
     */
    public function searchAction(UrlGeneratorInterface $urlGenerator, string $type): Response
    {
        if (!TypeEnum::exist($type)) {
            return $this->createNotFoundException(sprintf('Type "%s" unknown!', $type));
        }

        return $this->render('back/tmdb/search.html.twig', [
            'type' => $type,
            'searchUrl' => $urlGenerator->generate('bo_tmdb_search_result', ['type' => $type]),
        ]);
    }

    /**
     * @Route("/admin/tmdb/search/{type}/result", name="bo_tmdb_search_result", requirements={"type"="\w+"})
     */
    public function searchResultAction(
        Request $request,
        TmdbDataProvider $tmdbDataProvider,
        DisplayableResultAdapter $displayableResultAdapter,
        string $type
    ): Response {
        if (!TypeEnum::exist($type)) {
            return $this->createNotFoundException(sprintf('Type "%s" unknown!', $type));
        }

        $value = $request->query->get('value', '');
        $result = [];
        $displayRoute = null;

        if (strlen($value) > 2) {
            switch ($type) {
                case TypeEnum::ACTOR :
                    $result = $tmdbDataProvider->searchActors($value, null);
                    $displayRoute = 'bo_tmdb_display_actor';
                    break;
                case TypeEnum::MOVIE :
                    $result = $tmdbDataProvider->searchMovies($value, null);
                    $displayRoute = 'bo_tmdb_display_movie';
                    break;
                default:
                    return $this->createNotFoundException(sprintf('Type "%s" not supported!', $type));

            }
        }

        return $this->render('back/tmdb/search_result.html.twig', [
            'items' => $displayableResultAdapter->adaptArray($result),
            'callback' => $displayRoute,
        ]);
    }
}
