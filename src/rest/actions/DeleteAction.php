<?php

namespace nadzif\base\rest\actions;

use nadzif\base\rest\components\Response;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;

class DeleteAction extends SingleRecordAction
{

    public $isSoftDelete        = false;
    public $softDeleteAttribute = 'status';
    public $softDeleteValue     = 'deleted';

    public $key                        = 'id';
    public $condition                  = true;
    public $attributeCondition         = [];
    public $attributeConditionJunction = 'and';

    /**
     * @since 2018-05-04 12:14:55
     *
     * @param $id
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function run($id)
    {

        /** @var ActiveQuery $query */
        $query = $this->find()->where(['id' => $id]);

        $record = $query->one();

        if ($this->isSoftDelete) {
            $attr = $this->softDeleteAttribute;

            $record->{$attr} = $this->softDeleteValue;
            $record->save();
        } else {
            $record->delete();
        }

        $response          = new Response();
        $response->name    = \Yii::t('app', 'Delete Success');
        $response->message = $this->successMessage;
        $response->code    = $this->apiCodeSuccess;
        $response->status  = 200;
        $response->data    = [];

        return $response;
    }
}
