<?php

namespace Tool;

class TextUtil
{
    /**
     * Allows to remove all HTML from specified text (tags with its attributes...).
     *
     * @param string Text we want to remove HTML code.
     *
     * @return string Specified text without HTML tags, i.e. without HTML structure.
     */
    public static function removeAllHTMLTag(string $text): string
    {
        return empty($text) ? null : preg_replace('/(<[^>]*>)/', '', $text);
    }

    /**
     * Allows to truncate specified text with specified length.
     * NOTE : We do not truncate in the middle of a word but before => final size might be smaller than specified size.
     * WARNING : If text contains HTML code, text might be truncated on HTHML => causes errors.
     * In this case, it is highly recommended to remove HTML tags before.
     *
     * @param string text Text we want to truncate.
     * @param int size Max length of new truncated text (if more than current text size => no truncation).
     *
     * @return string Truncated text with specified length.
     */
    public static function truncateText(string $text, string $size): string
    {
        if (empty($text) || strlen($text) <= $size) {
            return $text;
        }

        // Truncate on 'space' (to not cut word)
        $textToCheck = substr($text, 0, $size + 1);
        $maxSize = strrpos($textToCheck, ' ');

        return (-1 === $maxSize) ? substr($text, 0, $size) : substr($text, 0, $maxSize);
    }

    /**
     * Sanitize a string by replacing all accent characters by the same characters without accent and by replacing special characters by "-".
     *
     * @param string stringToSanitize
     *
     * @return string A "clean" string sanitize from any accent or special characters
     */
    public static function sanitize(string $stringToSanitize): string
    {
        // Set to lower case
        $result = mb_strtolower($stringToSanitize);

        // Remove all accent
        $result = preg_replace('#ç#', 'c', $result);
        $result = preg_replace('#è|é|ê|ë#', 'e', $result);
        $result = preg_replace('#à|á|â|ã|ä|å#', 'a', $result);
        $result = preg_replace('#ì|í|î|ï#', 'i', $result);
        $result = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $result);
        $result = preg_replace('#ù|ú|û|ü#', 'u', $result);
        $result = preg_replace('#ý|ÿ#', 'y', $result);

        // Replace special character with '-'
        $result = preg_replace('#([^a-z0-9])+#', '-', $result);

        // If last character is '-', remove it
        return (substr($result, -1) == '-') ? substr($result, 0, -1) : $result;
    }
}
