<?php

namespace Framework\Utils;

/**
 * Class Colors
 * The Colors class is used to format the output of the console with colors.
 */
class Colors
{
    /**
     * formatPrint.
     *
     * @param string[] $format
     */
    public static function formatPrint(array $format = [], string $text = ''): void
    {
        $codes = [
            'bold' => 1,
            'italic' => 3,
            'underline' => 4,
            'strikethrough' => 9,
            'black' => 30,
            'red' => 31,
            'green' => 32,
            'yellow' => 33,
            'blue' => 34,
            'magenta' => 35,
            'cyan' => 36,
            'white' => 37,
            'blackbg' => 40,
            'redbg' => 41,
            'greenbg' => 42,
            'yellowbg' => 44,
            'bluebg' => 44,
            'magentabg' => 45,
            'cyanbg' => 46,
            'lightgreybg' => 47,
        ];
        $formatMap = array_map(function ($v) use ($codes) {
            return $codes[$v];
        }, $format);
        echo "\e[".implode(';', $formatMap).'m'.$text."\e[0m";
    }

    /**
     * formatPrintLn.
     *
     * @param string[] $format
     */
    public static function formatPrintLn(array $format = [], string $text = ''): void
    {
        self::formatPrint($format, $text);
        echo "\r\n";
    }
}
