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
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class ListAction extends Action
{
    /**
     * @var GridModel
     */
    public $gridModel;
    public $gridViewConfig = [];

    public $query = false;
    public $columns;

    public $title;
    public $description;
    public $breadcrumbs = [];

    public $showToggleData = false;
    public $view           = '@nadzif/base/layouts/_list';

    public $pageSizeData = [
        1   => 1,
        10  => 10,
        25  => 25,
        50  => 50,
        100 => 100,
    ];

    public $createConfig = [];

    public function init()
    {
        if (!isset($this->createConfig['model']) && !isset($this->createConfig['button'])) {
            throw new InvalidConfigException(\Yii::t('app', 'Set model'));

            if (!isset($this->createConfig['button']) && !isset($this->createConfig['actionUrl'])) {
                throw new InvalidConfigException(\Yii::t('app', 'Set url action for create'));
            }
        }

        if ($this->gridModel === null) {
            throw new InvalidConfigException(get_class($this) . '::$gridModel must be set.');
        }
    }

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

        $dataProvider = $this->gridModel->getDataProvider($this->query);
        $columns      = $this->columns ?: $this->gridModel->getColumns();

        $_gridViewConfig = [
            'dataProvider' => $dataProvider,
            'filterModel'  => $this->gridModel,
            'columns'      => $columns,
            'toggleData'   => $this->showToggleData,
        ];

        return $this->controller->render($this->view, [
            'gridModel'        => $this->gridModel,
            'gridViewConfig'   => ArrayHelper::merge($_gridViewConfig, $this->gridViewConfig),
            'pageSizeData'     => $this->pageSizeData,
            'createConfig'     => $this->createConfig,
        ]);
    }
}