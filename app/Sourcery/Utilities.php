<?php

namespace App\Sourcery;

class Utilities
{
    public static function randomAlphanumericString($length = 16)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($permitted_chars), 0, 16);
    }
}
