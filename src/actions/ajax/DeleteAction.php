<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/14/2019
 * Time: 12:52 AM
 */

namespace nadzif\base\actions\ajax;


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

        if (!$model) {
            return Json::encode([
                'data' => [
                    'alert' => [
                        [
                            'type'    => 'warning',
                            'title'   => $this->failedTitle ?: \Yii::t('app', 'Data Not Found'),
                            'message' => $this->failedMessage ?: \Yii::t('app', 'Cannot find selected item for delete.')
                        ]
                    ]
                ]
            ]);
        }

        if ($this->condition && $model->delete()) {
            $modelAttributes = $model->attributes;

            if ($this->successTitle) {
                $successTitle = str_replace(['{', '}',], '', strtr($this->successTitle, $modelAttributes));
            } else {
                $successTitle = \Yii::t('app', 'Delete Success');
            }

            if ($this->successMessage) {
                $successMessage = str_replace(['{', '}',], '', strtr($this->successMessage, $modelAttributes));
            } else {
                $successMessage = \Yii::t('app', 'Record has been deleted.');
            }

            return Json::encode([
                'data' => [
                    'alert' => [
                        [
                            'type'    => 'success',
                            'title'   => $successTitle,
                            'message' => $successMessage
                        ]
                    ]
                ]
            ]);
        } else {
            return Json::encode([
                'data' => [
                    'alert' => [
                        [
                            'type'    => 'danger',
                            'title'   => $this->failedTitle ?: \Yii::t('app', 'Delete Failed'),
                            'message' => $this->failedMessage ?: \Yii::t('app', 'Failed while deleting record.')
                        ]
                    ]
                ]
            ]);
        }
    }
}