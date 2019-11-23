<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/16/2019
 * Time: 2:21 PM
 */

namespace nadzif\base\assets;


class AssetBundle extends \yii\web\AssetBundle
{
    public $sourcePath = "@nadzif/base/assets/files";
    public $css        = [
        'cs/float-alert.css',
    ];
    public $js         = [
        'js/float-alert.js',
    ];
    public $depends    = [
        'rmrevin\yii\fontawesome\AssetBundle',
        'rmrevin\yii\ionicon\AssetBundle'
    ];
}