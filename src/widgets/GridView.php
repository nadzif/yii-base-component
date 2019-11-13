<?php

namespace nadzif\base\widgets;

use nadzif\base\components\ActionColumn;
use nadzif\base\components\ClosureHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Description of GridView
 *
 * @author Lambda
 */
class GridView extends \kartik\grid\GridView
{

    const FILTER_DATE_RANGE = '\nadzif\base\widgets\DateRangePicker';
    const FILTER_DATE       = '\nadzif\base\widgets\DatePicker';
    const FILTER_SELECT2    = '\nadzif\base\widgets\Select2';

    const ICON_ACTIVE   = '<span class="ion ion-checkmark text-success"></span>';
    const ICON_INACTIVE = '<span class="ion ion-close text-danger"></span>';

    public $filterPosition = \kartik\grid\GridView::FILTER_POS_HEADER;
    public $bordered       = true;
    public $striped        = true;
    public $condensed      = true;
    public $hover          = true;
    public $pjax           = true;
    public $responsiveWrap = true;
    public $resizableColumns  = true;

    public $showExportAll = true;
    public $export = [
        'fontAwesome'      => true,
        'showConfirmAlert' => true,
        'target'           => '_blank',
        'icon'             => 'export'
    ];

    public $layout            = "<div class='grid-view-toolbar'>{toolbar}</div>{items}{pager}{summary}";
    public $toggleDataOptions = [
        'all'  => ['class' => 'btn btn-custom-toolbar'],
        'page' => ['class' => 'btn btn-custom-toolbar'],
    ];

    public $pager = [
        'nextPageLabel'  => '<i class="fa fa-angle-right"></i>',
        'prevPageLabel'  => '<i class="fa fa-angle-left"></i>',
        'firstPageLabel' => '<i class="fa fa-angle-double-left"></i>',
        'lastPageLabel'  => '<i class="fa fa-angle-double-right"></i>',
    ];

    public function run()
    {
        parent::run();
    }

    protected function initLayout()
    {
        Html::addCssClass($this->filterRowOptions, 'skip-export');
        if ($this->resizableColumns && $this->persistResize) {
            $key                                                 =
                empty($this->resizeStorageKey) ? \Yii::$app->user->id : $this->resizeStorageKey;
            $gridId                                              =
                empty($this->options['id']) ? $this->getId() : $this->options['id'];
            $this->containerOptions['data-resizable-columns-id'] =
                (empty($key) ? "kv-{$gridId}" : "kv-{$key}-{$gridId}");
        }
        if ($this->hideResizeMobile) {
            Html::addCssClass($this->options, 'hide-resize');
        }
        //----------------------------------------------stream export------------------------------------------------------------

        //----------------------------------------------------------------------------------------------------------

        $this->replaceLayoutTokens([
            '{toolbarContainer}' => $this->renderToolbarContainer(),
            '{toolbar}'          => $this->renderToolbar(),
            '{export}'           => (isset($exportAll) ? $exportAll : false) . $this->renderExport(),
            '{toggleData}'       => $this->renderToggleData(),
            '{items}'            => Html::tag('div', '{items}', $this->containerOptions),
        ]);

        if (is_array($this->replaceTags) && !empty($this->replaceTags)) {
            foreach ($this->replaceTags as $key => $value) {
                if ($value instanceof \Closure) {
                    $value = call_user_func($value, $this);
                }
                $this->layout = str_replace($key, $value, $this->layout);
            }
        }
    }

}
