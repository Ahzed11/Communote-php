<?php


namespace App\Utils;


class StringUtils
{
    public static function slugify(string $toSlug): string
    {
        $slug = strtolower(preg_replace(array( '/[^-a-zA-Z0-9\s]/', '/[\s]/' ), array( '', '-' ), $toSlug));
        $slug .= uniqid("-", true);
        return $slug;
    }
}
