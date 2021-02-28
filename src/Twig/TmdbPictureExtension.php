<?php

namespace App\Twig;

use App\Tool\TmdbUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TmdbPictureExtension extends AbstractExtension
{
    /**
     * @return TwigFilter[]|array<TwigFilter>
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('tmdbPictureUrl', [$this, 'tmdbPictureUrl']),
        ];
    }

    public function tmdbPictureUrl(string $url): string
    {
        return TmdbUtil::getBasePictureUrl().$url;
    }
}
