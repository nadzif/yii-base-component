<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/17/2019
 * Time: 2:46 AM
 */

namespace nadzif\base\helpers;


use yii\helpers\StringHelper as YiiStringHelper;

class StringHelper
{
    public static function replace($string, $default, $array = [])
    {
        if ($string) {
            $placeholders = [];
            foreach ((array)$array as $name => $value) {
                if (is_array($value)) {
                    continue;
                }

                $placeholders['{' . $name . '}'] = $value;
            }

            return ($placeholders === []) ? $string : strtr($string, $placeholders);
        } else {
            return $default;
        }
    }

    public static function initial($string, $length = 2)
    {
        $initial = '';
        $string  = preg_replace('/[^\p{L}\p{N}\s]/u', '', $string);
        $words   = explode(' ', $string);

        foreach ($words as $word) {
            $initial .= substr($word, 0, 1);

            if (strlen($initial) >= $length) {
                break;
            }
        }

        return $initial;
    }

    public static function shorten($string, $length = 30)
    {
        $regex = preg_replace('/&#?[a-z0-9]+;/i', '', preg_replace('/\r|\n/', '', $string));
        $msg   = strip_tags($regex);
        return YiiStringHelper::truncate($msg, $length);
    }
}
