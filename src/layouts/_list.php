<?php

/**
 * @var \yii\base\View                $this
 *
 * @var \nadzif\base\models\GridModel $gridModel
 * @var array                         $gridViewConfig
 * @var array                         $pageSizeData
 * @var array                         $showCreateButton
 * @var array                         $createConfig
 */

use nadzif\base\components\ActionColumn;
use nadzif\base\widgets\GridView;
use kartik\select2\Select2;
use rmrevin\yii\ionicon\Ion;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var GridView $gridView */
$gridView   = new GridView($gridViewConfig);
$gridViewId = $gridView->getId();

echo Html::beginTag('div', ['class' => 'grid-container dataTables_wrapper pos-relative pd-t-45']);
echo Html::beginTag('div', ['class' => 'datatables-tools clearfix mb-2']);

echo Html::beginTag('div',
    ['id' => $gridViewId . '-filters', 'class' => 'select2-wrap dataTables_length mg-b-10 mg-sm-b-0']);
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

echo Html::endTag('div'); //dataTables_length

echo Html::beginTag('div', ['class' => 'dataTables_filter d-flex justify-content-end']);
if ($createConfig) {

    $_createConfig = [
        'modalConfig'      => [
            'toggleButton' => [
                'label' => '<i class="ion-plus"></i>',
                'class' => 'btn btn-sm btn-success ml-1'
            ]
        ],
        'activeFormConfig' => [],
        'gridViewId'       => $gridViewId,
    ];

    echo $this->render('@nadzif/base/layouts/ajax/_form', ArrayHelper::merge($_createConfig, $createConfig));
}


$filterToggleButton = <<<JS
    (function () { $( "body" ).toggleClass("datatable-filters"); })();
JS;

$reloadPjaxJS = <<<JS
   (function () { $.pjax.reload({container:"#$gridViewId-pjax"}); })();
JS;

echo Html::button(Ion::icon(Ion::_ANDROID_OPTIONS), [
    'class'   => 'btn btn-sm btn-info ml-1',
    'onclick' => $filterToggleButton
]);

echo Html::button(Ion::icon(Ion::_ANDROID_SYNC), [
    'class'   => 'btn btn-sm btn-info ml-1',
    'onclick' => $reloadPjaxJS
]);
echo Html::endTag('div'); //dataTables_filter

echo Html::endTag('div'); //datatables-tools

$gridView->run();

echo Html::endTag('div'); //dataTables_wrapper

if ($gridModel->actionColumn && $gridModel->actionColumnClass == ActionColumn::className()) {
    echo Html::tag('div', false, ['id' => 'grid-update-section', 'style' => 'width:0; height:0; overflow: hidden;']);
}