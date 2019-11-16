<?php

/**
 * @var \nadzif\base\models\FormModel $formModel
 * @var string                        $scenario
 * @var array|string                  $actionUrl
 * @var array                         $modalConfig
 * @var array                         $activeFormConfig
 */

use nadzif\base\widgets\Modal;
use demogorgorn\ajax\AjaxSubmitButton;
use rmrevin\yii\ionicon\Ion;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
use \yii\helpers\StringHelper;

$modalTitle       = ArrayHelper::getValue($modalConfig, 'title', Yii::t('app', 'Form'));
$modalDescription = ArrayHelper::getValue($modalConfig, 'description', false);

$scenario  = $formModel->getScenario();
$modelName = StringHelper::basename(get_class($formModel));

$_modalConfig = [
    'id'    => 'modal-' . $modelName . '-' . $scenario,
    'title' => Html::tag('h6', $modalTitle, ['class' => 'tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold'])
];

$_activeFormConfig = [
    'id'     => $modelName . $scenario . '-form-' . time(),
    'action' => $actionUrl
];

$modal     = Modal::begin(ArrayHelper::merge($_modalConfig, $modalConfig));
$form      = ActiveForm::begin(ArrayHelper::merge($_activeFormConfig, $activeFormConfig));
$formRules = $formModel->formRules();

foreach ($formRules as $attributeName => $attributeOptions) {
    if (!ArrayHelper::isIn($attributeName, $formModel->scenarios()[$scenario])) {
        continue;
    }

    $inputType    = ArrayHelper::getValue($attributeOptions, 'inputType', false);
    $inputOptions = ArrayHelper::getValue($attributeOptions, 'inputOptions', []);

    $inputId   = $formModel->scenario . '-' . Html::getInputId($formModel, $attributeName);
    $formField = $form->field($formModel, $attributeName);

    switch ($attributeOptions['inputType']) {
        case 'text':
            $inputOptions['id'] = $inputId;
            $formField->textInput($inputOptions);
            break;
        case 'textarea':
            $inputOptions['id'] = $inputId;
            $formField->textarea($inputOptions);
            break;
        case 'password':
            $inputOptions['id'] = $inputId;
            $formField->passwordInput($inputOptions);
            break;
        default:
            if (ArrayHelper::isIn($attributeOptions['inputType'], ['backend\widgets\Select2'])) {
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

$formId      = $form->getId();
$modalId     = $modal->getId();
$iconSuccess = Ion::icon(Ion::_IOS_CHECKMARK_OUTLINE);
$iconWarning = Ion::icon(Ion::_ANDROID_WARNING);
$iconDanger  = Ion::icon(Ion::_IOS_FLAME);
$iconInfo    = Ion::icon(Ion::_INFORMATION);

$submitSuccess = <<<JS
    (function(html) {
        html = JSON.parse(html);
        console.log(html);
        console.log(typeof html);
        $('#output').html(html);
        
        if($("#$gridViewId-pjax").length){
            $.pjax.reload({container:"#$gridViewId-pjax"});
        }
        
        if(html.data !== undefined && html.data.alert != undefined){
            var alertObject = html.data.alert;
            if(Array.isArray(alertObject)){
    
                for (var i in alertObject){
                var alertData = alertObject[i];

                    switch (alertData.type){
                        case 'warning':
                            var alertIcon = '$iconWarning';
                            break;
                        case 'danger':
                            var alertIcon = '$iconDanger';
                            break;
                        case 'info':
                            var alertIcon = '$iconInfo';
                            break;
                        default:
                            var alertIcon = '$iconSuccess';
                    }
                    
                    window.FloatAlert.alert(alertData.title, alertData.message, alertData.type, alertIcon);
                } 
            }else{
                window.FloatAlert.alert(alertObject.title, alertObject.message, alertObject.type, '$iconSuccess');
            }
        }
        
        $("#$formId")[0].reset();
            $("#$modalId").modal('hide');
    })
JS;

echo AjaxSubmitButton::widget([
    'label'             => $submitLabel,
    'useWithActiveForm' => $formId,
    'ajaxOptions'       => [
        'type'    => 'POST',
        'url'     => Url::to($actionUrl),
        'success' => new JsExpression($submitSuccess),
    ],
    'options'           => ['class' => 'btn btn-info', 'type' => 'submit'],
]);

echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-secondary pull-right']);

ActiveForm::end();
Modal::end();
