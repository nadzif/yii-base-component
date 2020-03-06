<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 3/6/2020
 * Time: 6:09 PM
 */

namespace nadzif\base\components;


use yii\web\ForbiddenHttpException;

class Action extends \yii\base\Action
{
    /** @var bool Whether the user can access this action or not */
    public $checkAccess = true;

    /**
     * @since 2018-05-04 00:40:48
     * @return bool
     * @throws ForbiddenHttpException
     */
    protected function beforeRun()
    {
        if ($this->checkAccess instanceof \Closure) {
            $this->checkAccess = \call_user_func($this->checkAccess);
        }

        if (!$this->checkAccess) {
            throw new ForbiddenHttpException(null, \Yii::t('app', 'You are not allowed to access this page.'));
        }

        return true;
    }
}