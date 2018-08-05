<?php

namespace App\Event;

class QuizEvents
{
    /**
     * Event fired when starting to build link between responses of quiz and TMDB
     * @var string
     */
    const BUILD_TMDB_LINK_START = 'build_tmdb_link_start';

    /**
     * Event fired after building tmdb link between responses of quiz and TMDB
     * @var string
     */
    const BUILD_TMDB_LINK_PROGRESS = 'build_tmdb_link_progress';

    /**
     * Event fired when links between responses of quiz and TMDB are built
     * @var string
     */
    const BUILD_TMDB_LINK_END = 'build_tmdb_link_end';
}
