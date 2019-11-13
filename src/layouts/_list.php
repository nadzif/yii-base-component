<?php

/**
 * @var \yii\base\View               $this
 *
 * @var \nadzif\base\GridModel       $gridModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array                        $columns
 * @var array                        $pageSizeData
 * @var bool                         $showCreateButton
 * @var bool                         $showToggleData
 * @var array                        $createConfig
 * @var array                        $toolbars
 */

use nadzif\base\models\FormModel;
use nadzif\base\widgets\GridView;
use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FontAwesome;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var GridView $gridView */
$gridView = new GridView([
    'dataProvider'  => $dataProvider,
    'filterModel'   => $gridModel,
    'columns'       => $columns,
    'toggleData'    => $showToggleData,
    'showExportAll' => isset($showExportAll) ? $showExportAll : true
]);

$gridViewId   = $gridView->getId();
$initWrapper  = isset($wrapper) ? $wrapper : true;
$submitAjax   = isset($submitAjax) ? $submitAjax : true;
$enctype      = isset($createConfig['formEnctype']) ? $createConfig['formEnctype'] : false;
$gridToolbars = isset($toolbars) ? $toolbars : [];


foreach ($gridToolbars as $toolbar) {
    echo $toolbar;
}

if ($initWrapper) {
    echo Html::beginTag('div', ['class' => 'br-section-wrapper']);
}

echo Html::beginTag('div', ['class' => 'datatables-tools']);
echo Html::beginTag('div', ['id' => $gridViewId . '-filters', 'class' => 'select2-wrap']);
echo Select2::widget([
    'model'        => $gridModel,
    'attribute'    => 'pageSize',
    'theme'        => Select2::THEME_DEFAULT,
    'hideSearch'   => true,
    'data'         => $pageSizeData,
    'options'      => ['class' => 'grid-size-filter'],
    'pluginEvents' => [
        'change' => 'function(e){$.pjax({container: \'#' . $gridViewId . '-pjax\'})}'
    ]
]);

echo Html::endTag('div');


if ($showCreateButton) {
    if (isset($createConfig['button'])) {
        if (isset($createConfig['showDefaultCreateButton']) && $createConfig['showDefaultCreateButton']) {
            $model = $createConfig['model'];
            $model->setScenario(FormModel::SCENARIO_CREATE);
            echo $this->render('@backend/actions/layouts/_form', [
                'model'        => $model,
                'asModal'      => true,
                'modalOptions' => ArrayHelper::getValue($createConfig, 'modal', []),
                'submitAjax'   => $submitAjax,
                'actionUrl'    => $createConfig['actionUrl'],
                'gridViewId'   => $gridViewId,
                'enctype'      => $enctype
            ]);
        }

        echo $createConfig['button'];
    } else {
        /** @var FormModel $model */
        $model = $createConfig['model'];
        $model->setScenario(FormModel::SCENARIO_CREATE);

        echo $this->render('@nadzif/base/layouts/_form', [
            'model'        => $model,
            'asModal'      => true,
            'modalOptions' => ArrayHelper::getValue($createConfig, 'modal', []),
            'submitAjax'   => $submitAjax,
            'actionUrl'    => $createConfig['actionUrl'],
            'gridViewId'   => $gridViewId,
            'enctype'      => $enctype
        ]);
    }
}


$filterToggleButton = <<<JS
    (function () { $( "body" ).toggleClass("datatable-filters"); })();
JS;

$reloadPjaxJS = <<<JS
   (function () { $.pjax.reload({container:"#$gridViewId-pjax"}); })();
JS;

echo Html::button(FontAwesome::icon(FontAwesome::_FILTER), [
    'class'   => 'btn btn-info',
    'onclick' => $filterToggleButton
]);

echo Html::button(FontAwesome::icon(FontAwesome::_SYNC), [
    'class'   => 'btn btn-info',
    'onclick' => $reloadPjaxJS
]);

echo Html::endTag('div');


$gridView->run();

if ($initWrapper) {
    echo Html::endTag('div');
}

if ($gridModel->actionColumn && $gridModel->actionColumnClass == \nadzif\base\components\ActionColumn::className()) {
    echo Html::tag('div', false, ['id' => 'grid-update-section', 'style' => 'width:0; height:0; overflow: hidden;']);
}