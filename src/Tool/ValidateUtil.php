<?php

namespace App\Tool;

class ValidateUtil
{
    private const MAIL_REGEX = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}$/';
    private const PHONE_REGEX = '/^(\\+)?[0-9. ]*$/';
    private const URL_REGEX = '/^[a-z0-9.+_-]+$/';

    /**
     * Allows to check validity mail.
     *
     * @param string $mail mail we want to check validity
     *
     * @return bool TRUE if mail is valid, FALSE either
     */
    public static function checkMail(string $mail): bool
    {
        return empty($mail) ? false : preg_match(self::$MAIL_REGEX, $mail);
    }

    /**
     * Allows to check validity phone number.
     *
     * @param string $phone phone we want to check validity
     *
     * @return bool TRUE if phone is valid, FALSE either
     */
    public static function checkPhoneNumber(string $phone): bool
    {
        return empty($phone) ? false : preg_match(self::$PHONE_REGEX, $phone);
    }

    /**
     * Allows to check if text is conform to URL restriction (no accent, not special character...).
     * Allowed characters are : letter (in lower case only), number, characters '.', '-', '+' and '_'.
     *
     * @param string $text string we want to check characters for URL use
     *
     * @return bool TRUE if string can be used in URL, FALSE either
     */
    public static function checkForUrlRestriction(string $text): bool
    {
        return empty($text) ? false : preg_match(self::$URL_REGEX, $text);
    }

    /**
     * Allows to check if password is secure. It must contain at least 6 characters + characters depending on security level.
     *
     * @param string $password password we want to check if matches password security
     * @param int $level 1 for low security (only lower / upper case or letter / numeric),
     * 2 for medium security (lower / upper case + numeric),
     * 3 for high security (lower / upper case + numeric + special character)
     *
     * @return bool TRUE if password is valid, FALSE either
     */
    public static function checkPassword(string $password, int $level): bool
    {
        if (empty($password) || strlen($password) < 6) {
            return false;
        }

        $isSecure = (preg_match('/.*[a-z].*/', $password) && preg_match('/.*[A-Z].*/', $password))
                || (preg_match('/.*[a-zA-Z].*/', $password) && preg_match('/.*[0-9].*/', $password));

        if (2 === $level) {
            $isSecure = $isSecure && preg_match('/.*([0-9]|[^a-zA-Z]).*/', $password);
        } elseif ($level >= 3) {
            $isSecure = $isSecure && preg_match('/.*([-_+=*$%&?!]).*/', $password);
        }

        return $isSecure;
    }
}
