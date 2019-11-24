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
$gridView     = new GridView($gridViewConfig);
$gridViewId   = $gridView->getId();

$createConfigDef = [
    'modalConfig'      => [
        'toggleButton' => [
            'label' => Ion::icon(Ion::_PLUS),
            'class' => 'btn btn-sm btn-success ml-1'
        ]
    ],
    'activeFormConfig' => [],
    'gridViewId'       => $gridViewId,
];
$formLayout   = '@nadzif/base/layouts/ajax/_form';


echo Html::beginTag('div', ['class' => 'grid-container dataTables_wrapper']);
echo Html::beginTag('div', ['class' => 'datatables-tools clearfix mb-2']);

echo Html::beginTag('div', ['class' => 'd-flex justify-content-end float-right mb-2 mb-sm-0']);
if ($createConfig) echo $this->render($formLayout, ArrayHelper::merge($createConfigDef, $createConfig));

echo Html::button(Ion::icon(Ion::_ANDROID_OPTIONS), [
    'class'   => 'btn btn-sm btn-info ml-1',
    'onclick' => "(function () { $('tr#{$gridViewId}-filters').toggleClass('d-none'); })()",
]);

echo Html::button(Ion::icon(Ion::_ANDROID_SYNC), [
    'class'   => 'btn btn-sm btn-info ml-1',
    'onclick' => "(function (e) { $.pjax.reload({container:'#{$gridViewId}-pjax'}); })()",
]);
echo Html::endTag('div');

echo Html::beginTag('div', ['id' => "{$gridViewId}-filters", 'class' => 'select2-wrap dataTables_length']);
echo Select2::widget([
    'model'        => $gridModel,
    'attribute'    => 'pageSize',
    'theme'        => Select2::THEME_DEFAULT,
    'hideSearch'   => true,
    'data'         => $pageSizeData,
    'options'      => ['class' => 'grid-size-filter'],
    'pluginEvents' => [
        'change' => "function(e){ $.pjax({container: '#{$gridViewId}-pjax'}) }"
    ]
]);
echo Html::endTag('div'); //dataTables_length

echo Html::endTag('div'); //datatables-tools

$gridView->run();

echo Html::endTag('div'); //dataTables_wrapper

if ($gridModel->actionColumn && $gridModel->actionColumnClass == ActionColumn::className()) {
    echo Html::tag('div', false, ['id' => 'grid-update-section', 'style' => 'width:0; height:0; overflow: hidden;']);
}
