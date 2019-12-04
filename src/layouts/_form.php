<?php

/**
 * @var \nadzif\base\models\FormModel $formModel
 * @var string                        $scenario
 * @var array|string                  $actionUrl
 * @var array                         $modalConfig
 * @var array                         $activeFormConfig
 */

use rmrevin\yii\ionicon\Ion;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use \yii\helpers\StringHelper;

$time = time();

$scenario  = $formModel->getScenario();
$modelName = StringHelper::basename(get_class($formModel));

$_activeFormConfig = [
    'id'     => $modelName . $scenario . '-form-' . $time,
    'action' => $actionUrl
];

$form      = ActiveForm::begin(ArrayHelper::merge($_activeFormConfig, $activeFormConfig));
$formRules = $formModel->formRules();

foreach ($formModel->scenarios()[$scenario] as $attributeName) {
    $attributeOptions = ArrayHelper::getValue($formRules, $attributeName, []);

    $inputType    = ArrayHelper::getValue($attributeOptions, 'inputType', 'text');
    $inputOptions = ArrayHelper::getValue($attributeOptions, 'inputOptions', []);
    $fieldOptions = ArrayHelper::getValue($attributeOptions, 'fieldOptions', []);

    $inputId   = $formModel->scenario . '-' . Html::getInputId($formModel, $attributeName);
    $formField = $form->field($formModel, $attributeName, $fieldOptions);

    switch ($inputType) {
        case 'text':
            $inputOptions['id'] = $inputId;
            $formField->textInput($inputOptions);
            break;
        case 'checkbox':
            $inputOptions['id'] = $inputId;
            $formField->checkbox($inputOptions);
            break;
        case 'textarea':
            $inputOptions['id'] = $inputId;
            $formField->textarea($inputOptions);
            break;
        case 'password':
            $inputOptions['id'] = $inputId;
            $formField->passwordInput($inputOptions);
            break;
        case 'hidden':
            $inputOptions['id']  = $inputId;
            $formField->template = '{input}';
            $formField->hiddenInput($inputOptions);
            break;
        case 'fileInput':
            $inputOptions['id']  = $inputId;
            $formField->template = '{input}';
            $formField->fileInput($inputOptions);
            break;
        default:
            if ($inputType instanceof \kartik\select2\Select2) {
                if ($formModel->$attributeName) {
                    $inputOptions['initValueText'] = $formModel->$attributeName;
                }
            }

            $inputOptions['id']            = $inputId;
            $inputOptions['options']['id'] = $inputId;
            $formField->widget($inputType, $inputOptions);
            break;
    }

    echo $formField;
}

switch ($formModel->scenario) {
    case $formModel::SCENARIO_CREATE:
        $submitLabel = Yii::t('app', 'Create');
        break;
    case $formModel::SCENARIO_UPDATE:
        $submitLabel = Yii::t('app', 'Update');
        break;
    default:
        $submitLabel = Yii::t('app', 'Submit');
        break;
}

echo Html::beginTag('div');
echo Html::submitButton($submitLabel, [
    'class' => 'btn btn-info',
    'id'    => $modelName . $scenario . '-submit-' . $time,
    'type'  => 'submit',
]);

echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-secondary float-right']);
echo Html::endTag('div');

ActiveForm::end();
