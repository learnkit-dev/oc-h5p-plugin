<?php namespace Kloos\H5p\Classes;

class H5pHelper
{
    public static function current_user_can($permission)
    {
        return true;
    }

    public static function nonce($token)
    {
        return bin2hex($token);
    }
}
