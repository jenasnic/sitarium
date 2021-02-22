<?php

namespace App\Event\Quiz;

use Symfony\Contracts\EventDispatcher\Event;

class TmdbLinkEndEvent extends Event
{
    public const BUILD_TMDB_LINK_END = 'build_tmdb_link_end';
}
