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
            $placeholders = [];
            foreach ((array) $array as $name => $value) {
                if(is_array($value)){
                    continue;
                }

                $placeholders['{' . $name . '}'] = $value;
            }

            return ($placeholders === []) ? $string : strtr($string, $placeholders);
        } else {
            return $default;
        }
    }
}