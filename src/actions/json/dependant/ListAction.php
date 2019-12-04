<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 12/3/2019
 * Time: 7:25 PM
 */

namespace nadzif\base\actions\json\dependant;


use nadzif\base\models\ActiveRecord;
use yii\base\Action;

class ListAction extends Action
{
    public $limit = 10;

    public $activeRecordClass;
    public $idAttribute;
    public $textAttribute;

    public $parentAttribute;

    public $condition = [];

    public function init()
    {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function run($selected = '')
    {
        $out = ['output' => '', 'selected' => ''];

        if (isset($_POST['depdrop_parents'])) {
            /** @var ActiveRecord $activeRecord */
            $activeRecord  = new $this->activeRecordClass;
            $textAttribute = $this->textAttribute;

            $parents = $_POST['depdrop_parents'];
            if ($parents != null && $parents[0]) {
                $parentId = $parents[0];
                $query    = $activeRecord::find()
                    ->select([$this->idAttribute, $textAttribute . ' AS name'])
                    ->where([$this->parentAttribute => $parentId])
                    ->asArray()
                    ->limit($this->limit);

                if ($this->condition) {
                    $query->andWhere($this->condition);
                }

                return ['output' => $query->all(), 'selected' => $selected];
            }
        }

        return $out;
    }
}