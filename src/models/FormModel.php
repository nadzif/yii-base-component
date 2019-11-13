<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 5/24/2018
 * Time: 1:59 AM
 */

namespace nadzif\base\models;


use yii\base\Model;

class FormModel extends Model
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /** @var \yii\db\ActiveRecord */
    public $model;

    /** @var bool */
    public $validateForm = true;

    public function init()
    {
        parent::init();
    }

    public function getFormRules($attribute)
    {
        return $this->formRules()[$attribute];
    }

    /**
     * @return array
     *
     *               return [
     * 'name'     => [
     * 'inputType'    => Select2::className(),
     * 'inputOptions' => [
     * 'data' => ['satu' => 'one', 'dua' => 'two']
     * ]
     * ],
     * 'username' => [
     * 'inputType' => 'textarea'
     * ]
     * ];
     */
    public function formRules()
    {
        $defaultRules = [];
        foreach ($this->attributes as $attributeKey => $defaultRule) {
            $defaultRules[$attributeKey] = ['inputType' => 'text'];
        }

        return $defaultRules;
    }

    public function loadAttributes($attributes = [])
    {
        if ($attributes == []) {
            $this->attributes = $this->getModel()->attributes;
        } else {
            $this->attributes = $attributes;
        }
    }

    /**
     * @return ActiveRecord
     */
    public function getModel()
    {
        return $this->model;
    }

    public function save($runValidation = true)
    {
        $this->setModelAttributes();
        $validated = $runValidation ? ($this->validateForm ? $this->validate() : true) : true;
        return $validated && $this->getModel()->save();
    }

    public function setModelAttributes()
    {
        $modelAttributes = $this->scenarios()[$this->getScenario()];
        foreach ($modelAttributes as $modelAttribute) {
            try {
                $this->getModel()->$modelAttribute = $this->$modelAttribute;
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    protected function getAttributesKey()
    {
        return array_keys($this->attributes);
    }


}