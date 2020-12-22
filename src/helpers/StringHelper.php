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

    public static function initial($string)
    {
        $explode = explode(' ', $string);

        if (count($string) >= 2) {
            if (count($explode) <= 1) {
                return substr($explode[0], 0, 2);
            } else {
                $initialString = '';

                $iC = 0;
                foreach ($explode as $singleString) {
                    if ($iC == 2) {
                        break;
                    }

                    if (!$singleString) {
                        continue;
                    }

                    $initialString .= substr($singleString, 0, 1);
                    $iC++;
                }

                return $initialString;
            }
        } else {
            return $string;
        }
    }

    public static function shorten($string, $length = 30)
    {
        $regex = preg_replace('/&#?[a-z0-9]+;/i', '', preg_replace('/\r|\n/', '', $string));
        $msg   = strip_tags($regex);
        return YiiStringHelper::truncate($msg, $length);
    }
}