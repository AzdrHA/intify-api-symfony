<?php

namespace App\Utils;

abstract class UtilsStr
{
    /**
     * @param string $content
     * @return string
     */
    public static function ucFirst(string $content): string
    {
        return ucfirst($content);
    }
}