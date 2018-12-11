<?php

namespace App\Event;

class UserEvents
{
    /**
     * Event fired when creating new account.
     *
     * @var string
     */
    const NEW_ACCOUNT = 'new_account';

    /**
     * Event fired when updating account.
     *
     * @var string
     */
    const UPDATE_ACCOUNT = 'update_account';

    /**
     * Event fired when user wants to reset password.
     *
     * @var string
     */
    const RESET_PASSWORD = 'reset_password';
}
