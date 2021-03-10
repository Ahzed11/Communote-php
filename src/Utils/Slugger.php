<?php


namespace App\Utils;


class Slugger
{
    static public function slug(string $toSlug): string
    {
        $slug = strtolower(preg_replace(array( '/[^-a-zA-Z0-9\s]/', '/[\s]/' ), array( '', '-' ), $toSlug));
        $slug .= uniqid("-");
        return $slug;
    }
}