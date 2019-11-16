<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/14/2019
 * Time: 12:52 AM
 */

namespace nadzif\base\actions\ajax;


use nadzif\base\helpers\StringHelper;
use nadzif\base\widgets\FloatAlert;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class DeleteAction extends Action
{
    public $activeRecordClass;
    public $condition = true;

    public $key = 'id';

    public $successTitle;
    public $successMessage;
    public $failedTitle;
    public $failedMessage;

    public function run()
    {
        $requestParam = \Yii::$app->request->get($this->key);
        /** @var ActiveRecord $newModel */
        $newModel = new $this->activeRecordClass;

        /** @var ActiveRecord $model */
        $model = $newModel::find()->where([$this->key => $requestParam])->one();

        if ($model) {
            $modelAttributes = $model->attributes;
            if ($this->condition && $model->delete()) {
                $type    = FloatAlert::TYPE_SUCCESS;
                $title   = StringHelper::replace(
                    $this->successTitle,
                    \Yii::t('app', 'Delete Success'),
                    $modelAttributes
                );
                $message = StringHelper::replace(
                    $this->successTitle,
                    \Yii::t('app', 'Record deleted successfully.'),
                    $modelAttributes
                );
            } else {
                $type    = FloatAlert::TYPE_DANGER;
                $title   = StringHelper::replace(
                    $this->failedTitle,
                    \Yii::t('app', 'Failed while deleting record.'),
                    $modelAttributes
                );
                $message = StringHelper::replace(
                    $this->failedMessage,
                    \Yii::t('app', 'Failed while deleting record.'),
                    $modelAttributes
                );
            }
        } else {
            $type    = FloatAlert::TYPE_WARNING;
            $title   = $this->failedTitle ?: \Yii::t('app', 'Data Not Found');
            $message = $this->failedMessage ?: \Yii::t('app', 'Cannot find selected item for delete.');
        }


        return Json::encode([
            'data' => [
                'alert' => [
                    [
                        'type'    => $type,
                        'title'   => $title,
                        'message' => $message
                    ]
                ]
            ]
        ]);
    }


}