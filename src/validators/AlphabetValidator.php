<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/28/2020
 * Time: 11:10 PM
 */

namespace nadzif\base\validators;

use yii\validators\RegularExpressionValidator;

class AlphabetValidator extends RegularExpressionValidator
{
    public $pattern = '/^[a-zA-Z]+(\s{0,1}[a-zA-Z ])*$/';
}