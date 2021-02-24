<?php

namespace App\Tool;

use InvalidArgumentException;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class PasswordUtil
{
    private const LOWER_CASE = 'abcdefghijklmnopqrstuvwxyz';
    private const UPPER_CASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const NUMERIC = '0123456789';
    private const SPECIAL = '-_+=*$%&?!';

    /**
     * Encode a string using SHA. Useful to get password hash.
     *
     * @param string $password
     * @param string $salt
     *
     * @return string
     */
    public static function encodePassword(string $password, $salt = null): string
    {
        // Use SHA to encode password (without salt => null)
        $shaEncoder = new MessageDigestPasswordEncoder();

        return $shaEncoder->encodePassword($password, $salt);
    }

    /**
     * Allows to generate a random password.
     * NOTE : minimum size depends on specified parameters.
     *
     * @param int $size number of characters for password
     * @param bool $withLowerCase TRUE to include lower case character in password, FALSE either
     * @param bool $withUpperCase TRUE to include upper case character in password, FALSE either
     * @param bool $withNumeric TRUE to include numeric character in password (i.e. number), FALSE either.
     * @param bool $withSpecial TRUE to include special character in password, FALSE either
     *
     * @return string a random password matching specified parameters
     */
    public static function generatePassword(
        int $size,
        bool $withLowerCase,
        bool $withUpperCase,
        bool $withNumeric,
        bool $withSpecial
    ): string {
        $minSize = ($withLowerCase ? 1 : 0) + ($withUpperCase ? 1 : 0) + ($withNumeric ? 1 : 0) + ($withSpecial ? 1 : 0);
        if (0 === $minSize || $size < $minSize) {
            throw new InvalidArgumentException('Given parameters doesn\'t allow to generate password.');
        }

        $result = '';
        $selectableChar = self::buildSelectableCharBuilder($withLowerCase, $withUpperCase, $withNumeric, $withSpecial);

        for ($i = 0; $i < $size; ++$i) {
            // If remaining size is less than minimum size => check if all required type of character are used or not
            // => we must add required character type before it is too late
            if ($size < $minSize + $i) {
                // Reset selectable characters list
                $selectableChar = '';

                // If lower case is required but not already added => add lower characters
                if ($withLowerCase && !self::hasCommonCharacter($result, self::LOWER_CASE)) {
                    $selectableChar .= self::LOWER_CASE;
                }
                // If upper case is required but not already added => add upper characters
                if ($withUpperCase && !self::hasCommonCharacter($result, self::UPPER_CASE)) {
                    $selectableChar .= self::UPPER_CASE;
                }
                // If numeric is required but not already added => add numeric characters
                if ($withNumeric && !self::hasCommonCharacter($result, self::NUMERIC)) {
                    $selectableChar .= self::NUMERIC;
                }
                // If special character is required but not already added => add special characters
                if ($withSpecial && !self::hasCommonCharacter($result, self::SPECIAL)) {
                    $selectableChar .= self::SPECIAL;
                }

                // If all required characters type has been added and if there are still characters to add => used all required character type
                if (0 === strlen($selectableChar)) {
                    $selectableChar = self::buildSelectableCharBuilder($withLowerCase, $withUpperCase, $withNumeric, $withSpecial);
                }
            }

            // Generate one char selection value
            $c = rand(0, strlen($selectableChar) - 1);
            $result .= $selectableChar[$c];
        }

        return $result;
    }

    /**
     * Allows to know if both specified string have common character or not.<br/>
     * NOTE : This method is case sensitive.
     *
     * @param string $s1
     * @param string $s2
     *
     * @return bool TRUE if specified String have common characters (one or many), FALSE either
     */
    protected static function hasCommonCharacter(string $s1, string $s2): bool
    {
        if (empty($s1) || empty($s2)) {
            return false;
        }

        // Browse all characters of s1 and search it in s2
        for ($i = 0; $i < strlen($s1); ++$i) {
            if (false !== strpos($s2, $s1[$i])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Allows to build a StringBuilder containing all specified character types.
     *
     * @param bool $withLowerCase TRUE to include lower case character in password, FALSE either
     * @param bool $withUpperCase TRUE to include upper case character in password, FALSE either
     * @param bool $withNumeric TRUE to include numeric character in password (i.e. number), FALSE either.
     * @param bool $withSpecial TRUE to include special character in password, FALSE either
     *
     * @return string a string containing all required character types
     */
    protected static function buildSelectableCharBuilder(
        string $withLowerCase,
        string $withUpperCase,
        string $withNumeric,
        string $withSpecial
    ): string {
        // Add all required characters to build password
        $selectableChar = '';

        if ($withLowerCase) {
            $selectableChar .= self::LOWER_CASE;
        }
        if ($withUpperCase) {
            $selectableChar .= self::UPPER_CASE;
        }
        if ($withNumeric) {
            $selectableChar .= self::NUMERIC;
        }
        if ($withSpecial) {
            $selectableChar .= self::SPECIAL;
        }

        return $selectableChar;
    }
}
