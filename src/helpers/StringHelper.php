<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/17/2019
 * Time: 2:46 AM
 */

namespace nadzif\base\helpers;


class StringHelper
{
    public static function replace($string, $default, $array = [])
    {
        if ($string) {
            return str_replace(['{', '}',], '', strtr($string, $array));
        } else {
            return $default;
        }
    }
}