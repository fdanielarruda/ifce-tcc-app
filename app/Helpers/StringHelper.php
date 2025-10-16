<?php

namespace App\Helpers;

class StringHelper
{
    public static function newCode(int $size): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        $max = strlen($chars) - 1;

        for ($i = 0; $i < $size; $i++) {
            $string .= $chars[mt_rand(0, $max)];
        }

        return $string;
    }
}
