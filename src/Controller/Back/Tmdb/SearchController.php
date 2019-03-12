<?php

namespace App\Controller\Back\Tmdb;

use App\Enum\Tmdb\TypeEnum;
use App\Model\Tmdb\Search\Movie;
use App\Model\Tmdb\Search\Actor;
use App\Service\Tmdb\TmdbApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SearchController extends AbstractController
{
    const TYPE_MAPPING_ENTITY = [
        TypeEnum::ACTOR => [
            'class' => Actor::class,
            'display_route' => 'bo_tmdb_display_actor',
        ],
        TypeEnum::MOVIE => [
            'class' => Movie::class,
            'display_route' => 'bo_tmdb_display_movie',
        ],
    ];

    /**
     * Max result count to return when searching entities through TMDB.
     *
     * @var int
     */
    const MAX_RESULT_COUNT = 10;

    /**
     * @Route("/admin/tmdb/search/{type}", name="bo_tmdb_search", requirements={"type"="\w+"})
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @param string $type
     *
     * @return Response
     */
    public function searchAction(UrlGeneratorInterface $urlGenerator, string $type): Response
    {
        if (!array_key_exists($type, self::TYPE_MAPPING_ENTITY)) {
            return $this->createNotFoundException(sprintf('Type "%s" unknown!', $type));
        }

        return $this->render('back/tmdb/search.html.twig', [
            'type' => $type,
            'searchUrl' => $urlGenerator->generate('bo_tmdb_search_result', ['type' => $type]),
        ]);
    }

    /**
     * @Route("/admin/tmdb/search/{type}/result", name="bo_tmdb_search_result", requirements={"type"="\w+"})
     *
     * Requires query parameter 'value' to specifiy searched value for current type.
     *
     * @param Request $request
     * @param TmdbApiService $tmdbService
     * @param string $type
     *
     * @return Response
     */
    public function searchResultAction(
        Request $request,
        TmdbApiService $tmdbService,
        string $type
    ): Response {
        if (!array_key_exists($type, self::TYPE_MAPPING_ENTITY)) {
            return $this->createNotFoundException(sprintf('Type "%s" unknown!', $type));
        }

        $value = $request->query->get('value', '');
        $result = [];

        if (strlen($value) > 2) {
            $result = $tmdbService->searchEntity(self::TYPE_MAPPING_ENTITY[$type]['class'], $value, null, self::MAX_RESULT_COUNT);
        }

        return $this->render('back/tmdb/search_result.html.twig', [
            'items' => $result['results'],
            'callback' => self::TYPE_MAPPING_ENTITY[$type]['display_route'],
        ]);
    }
}
