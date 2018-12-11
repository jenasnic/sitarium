<?php

namespace App\Twig;

use App\Tool\TmdbUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TmdbPictureExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('tmdbPictureUrl', [$this, 'tmdbPictureUrl']),
        ];
    }

    public function tmdbPictureUrl($url)
    {
        return TmdbUtil::getBasePictureUrl().$url;
    }
}
