<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/14/2019
 * Time: 12:20 AM
 */

namespace nadzif\base\actions;


use nadzif\base\models\GridModel;
use yii\base\Action;
use yii\helpers\ArrayHelper;

class ListAction extends Action
{
    public $gridClass;
    public $gridViewConfig = [];
    public $query          = false;
    public $columns;
    public $title;
    public $description;
    public $breadcrumbs    = [];
    public $showToggleData = false;
    public $view           = '@nadzif/base/layouts/_list';

    ////////////
    public $pageSizeData = [
        1   => 1,
        10  => 10,
        25  => 25,
        50  => 50,
        100 => 100,
    ];
    public $createConfig = [];

    /**
     * @var GridModel
     */
    private $_gridModel;

    public function run()
    {
        if ($this->title) {
            $this->controller->getView()->title = $this->title;
        }

        if ($this->breadcrumbs) {
            $this->controller->getView()->params['breadcrumbs'] = $this->breadcrumbs;
        }
        if ($this->description) {
            $this->controller->getView()->params['description'] = $this->description;
        }

        $this->_gridModel = new $this->gridClass;

        $dataProvider = $this->_gridModel->getDataProvider($this->query);
        $columns      = $this->columns ?: $this->_gridModel->getColumns();

        $_gridViewConfig = [
            'dataProvider' => $dataProvider,
            'filterModel'  => $this->_gridModel,
            'columns'      => $columns,
            'toggleData'   => $this->showToggleData,
        ];

        $this->createConfig['formModel'] = new $this->createConfig['formClass'];
        return $this->controller->render($this->view, [
            'gridModel'      => $this->_gridModel,
            'gridViewConfig' => ArrayHelper::merge($_gridViewConfig, $this->gridViewConfig),
            'pageSizeData'   => $this->pageSizeData,
            'createConfig'   => $this->createConfig,
        ]);
    }
}