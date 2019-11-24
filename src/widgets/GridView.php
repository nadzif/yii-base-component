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

    public $filterPosition   = \kartik\grid\GridView::FILTER_POS_HEADER;
    public $bordered         = true;
    public $striped          = true;
    public $condensed        = true;
    public $hover            = true;
    public $pjax             = true;
    public $responsiveWrap   = true;
    public $resizableColumns = true;

    public $export = [
        'fontAwesome'      => true,
        'showConfirmAlert' => true,
        'target'           => '_blank',
        'icon'             => 'fa fa-download'
    ];

    public $layout            = "<div class='grid-view-toolbar'>{toolbar}</div>{items}{pager}{summary}";
    public $toggleDataOptions = [
        'all'  => ['class' => 'btn btn-custom-toolbar'],
        'page' => ['class' => 'btn btn-custom-toolbar'],
    ];

    public $pager = [
        'options'        => ['class' => 'pagination pagination-space'],
        'nextPageLabel'  => '<i class="fa fa-angle-right"></i>',
        'prevPageLabel'  => '<i class="fa fa-angle-left"></i>',
        'firstPageLabel' => '<i class="fa fa-angle-double-left"></i>',
        'lastPageLabel'  => '<i class="fa fa-angle-double-right"></i>',
    ];

}
