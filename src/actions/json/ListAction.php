<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/14/2019
 * Time: 12:54 AM
 */

namespace nadzif\base\actions\json;


use yii\base\Action;
use yii\db\ActiveRecord;

class ListAction extends Action
{
    public $queryParamKey = 'search';
    public $idKey         = 'id';
    public $limit         = 10;

    public $activeRecordClass;
    public $idAttribute;
    public $textAttribute;

    public function init()
    {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }


    public function run()
    {
        $q  = \Yii::$app->request->get($this->queryParamKey);
        $id = \Yii::$app->request->get($this->idKey);

        $out = ['results' => ['id' => '', 'text' => '']];

        /** @var ActiveRecord $activeRecord */
        $activeRecord  = new $this->activeRecordClass;
        $textAttribute = $this->textAttribute;

        if (!is_null($q)) {
            $model = $activeRecord::find()
                ->select([$this->idAttribute, $textAttribute . ' AS text'])
                ->where(['like', $textAttribute, $q])
                ->asArray()
                ->limit($this->limit)
                ->all();

            $out['results'] = $model;

        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => $activeRecord::findOne($id)->$textAttribute];
        }

        return $out;
    }
}