<?php

namespace App\Event;

class SynchronizationEvents
{
    /**
     * Event fired when starting synchronization with TMDB.
     *
     * @var string
     */
    const SYNCHRONIZE_DATA_START = 'synchronize_data_start';

    /**
     * Event fired after synchonize data for an entity.
     *
     * @var string
     */
    const SYNCHRONIZE_DATA_PROGRESS = 'synchronize_data_progress';

    /**
     * Event fired when synchronization done.
     *
     * @var string
     */
    const SYNCHRONIZE_DATA_END = 'synchronize_data_end';

    /**
     * Event fired when error occurs for synchronization.
     *
     * @var string
     */
    const SYNCHRONIZE_DATA_ERROR = 'synchronize_data_error';
}
