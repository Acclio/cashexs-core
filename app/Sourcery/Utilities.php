<?php

namespace App\Sourcery;

use Illuminate\Support\Facades\Log;

class Utilities
{
    public static function randomAlphanumericString($length = 16)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($permitted_chars), 0, 16);
    }

    // Check if a particular parameter is present in a request
    public static function checkRequest($request, $parameter)
    {
        $response = false;
        if($request->has($parameter) && $request->filled($parameter) && $request->query($parameter) != "null")
        {
            $response = true;
            return $response;
        };
        return $response;
    }
}
