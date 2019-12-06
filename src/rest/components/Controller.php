<?php

namespace nadzif\base\rest\components;

use api\filters\SystemAppFilter;
use nadzif\base\filters\FirstRequestTimeFilter;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;

class Controller extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    /**
     * Override behaviors from rest controller
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'contentNegotiator'      => [
                'class'   => ContentNegotiator::class,
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON
                ]
            ],
            'verbFilter'             => [
                'class'   => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
            'systemAppFilter'        => [
                'class'              => SystemAppFilter::class,
                'appKeyHeaderKey'    => 'X-App-key',
                'appSecretHeaderKey' => 'X-App-secret'
            ],
            'authenticator'          => [
                'class'       => CompositeAuth::className(),
                'authMethods' => [HttpBearerAuth::className()]
            ],
            'firstRequestTimeFilter' => [
                'class' => FirstRequestTimeFilter::class
            ]
        ];
    }

    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     *
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
        return [];
    }

    /**
     * @since 2018-02-02 09:39:14
     *
     * @param $action
     * @param $result
     *
     * @return mixed
     * @throws HttpException
     */
    public function afterAction($action, $result)
    {
        if (!$result instanceof Response) {
            throw new HttpException(500, 'Response should be instance of ' . Response::class);
        }

        if (($message = $result->validate()) !== true) {
            throw new HttpException(500, $message);
        }

        return parent::afterAction($action, $result);
    }

    public function init()
    {
        parent::init();
    }
}
