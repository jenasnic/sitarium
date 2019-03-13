<?php

namespace App\Event;

class MazeEvents
{
    /**
     * Event fired when starting to build filmography for actors.
     *
     * @var string
     */
    const BUILD_FILMOGRAPHY_START = 'build_filmography_start';

    /**
     * Event fired after building filmography for an actor.
     *
     * @var string
     */
    const BUILD_FILMOGRAPHY_PROGRESS = 'build_filmography_progress';

    /**
     * Event fired when filmography built.
     *
     * @var string
     */
    const BUILD_FILMOGRAPHY_END = 'build_filmography_end';

    /**
     * Event fired when error occurs for filmography.
     *
     * @var string
     */
    const BUILD_FILMOGRAPHY_ERROR = 'build_filmography_error';

    /**
     * Event fired when starting to build casting for movies.
     *
     * @var string
     */
    const BUILD_CASTING_START = 'build_casting_start';

    /**
     * Event fired after building casting for a movie.
     *
     * @var string
     */
    const BUILD_CASTING_PROGRESS = 'build_casting_progress';

    /**
     * Event fired when casting built.
     *
     * @var string
     */
    const BUILD_CASTING_END = 'build_casting_end';

    /**
     * Event fired when error occurs for casting.
     *
     * @var string
     */
    const BUILD_CASTING_ERROR = 'build_casting_error';
}
