<?php

namespace Lxr\Model;

/**
 * String methods from Illuminate\Support\Str
 * https://github.com/illuminate/support/blob/master/Str.php
 */
class StringHelpers
{
    /**
     * The cache of snake-cased words.
     *
     * @var array
     */
    protected static $snakeCache = [];

    /**
     * The cache of studly-cased words.
     *
     * @var array
     */
    protected static $studlyCache = [];

    /**
     * Replace the given value in the given string.
     *
     * @param string|array<string> $search
     * @param string|array<string> $replace
     * @param string|array<string> $subject
     * @param bool $caseSensitive
     * @return string|string[]
     */
    public static function replace($search, $replace, $subject, $caseSensitive = true)
    {
        return $caseSensitive
            ? str_replace($search, $replace, $subject)
            : str_ireplace($search, $replace, $subject);
    }

    /**
     * Convert the given string to upper-case.
     *
     * @param string $value
     * @return string
     */
    public static function upper($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Convert the given string to lower-case.
     *
     * @param  string  $value
     * @return string
     */
    public static function lower($value)
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * Make a string's first character uppercase.
     *
     * @param string $string
     * @return string
     */
    public static function ucfirst($string)
    {
        return static::upper(static::substr($string, 0, 1)) . static::substr($string, 1);
    }

    /**
     * Returns the portion of the string specified by the start and length parameters.
     *
     * @param  string  $string
     * @param  int  $start
     * @param  int|null  $length
     * @param  string  $encoding
     * @return string
     */
    public static function substr($string, $start, $length = null, $encoding = 'UTF-8')
    {
        return mb_substr($string, $start, $length, $encoding);
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    public static function studly($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $words = explode(' ', static::replace(['-', '_'], ' ', $value));

        $studlyWords = array_map(fn ($word) => static::ucfirst($word), $words);

        return static::$studlyCache[$key] = implode($studlyWords);
    }

    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    public static function snake($value, $delimiter = '_')
    {
        $key = $value;

        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }

        if (! ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));

            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));
        }

        return static::$snakeCache[$key][$delimiter] = $value;
    }
}