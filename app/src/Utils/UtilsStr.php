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

    /**
     * @param $namespace
     * @param bool $strtolower
     * @return string
     */
    public static function lastNamespaceItem($namespace, bool $strtolower = true): string
    {
        $items = explode("\\",$namespace);
        $item = array_pop($items);
        if(!$strtolower)
            return $item;
        return strtolower($item);
    }
}