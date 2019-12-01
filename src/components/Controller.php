<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/14/2019
 * Time: 3:41 AM
 */

namespace nadzif\base\components;


use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class Controller extends \yii\web\Controller
{
    public $allowedRoles = ['@'];
    public $language;

    public function behaviors()
    {
        $behaviors = [];

        if (!isset($this->behaviors['access'])) {
            $behaviors['access'] = [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'roles' => $this->allowedRoles]
                ]
            ];
        }

        $behaviors['verbFilter'] = [
            'class'   => VerbFilter::className(),
            'actions' => $this->verbs(),
        ];

        return $behaviors;
    }

    public function verbs()
    {
        return [];
    }

    public function init()
    {
        parent::init();
        if ($this->language) {
            \Yii::$app->language = $this->language;
        }
    }
}