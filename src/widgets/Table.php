<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\widgets;

use yii\helpers\Html;

/**
 * Description of Table
 *
 * @author Mohammad Nadzif <demihuman@live.com>
 */
class Table extends \yii\base\Widget
{
    public  $reverseBody      = false;
    public  $containerOptions = [];
    public  $options          = ['class' => 'table table-bordered table-hover'];
    public  $headerOptions    = [];
    public  $headers          = [];
    public  $rows             = [];
    public  $fieldConfig      = [];
    public  $rowOptions       = [];
    public  $summaryFormat    = 'decimal';
    public  $responsive       = true;
    public  $matrix           = [];
    public  $headersLabel     = [];
    private $summaryResult    = [];

    public function __construct($config = array())
    {
        parent::__construct($config);
        if ($this->responsive) {
            if (isset($this->containerOptions['class'])) {
                $this->containerOptions['class'] .= ' col-sm-12 table-responsive';
            } else {
                $this->containerOptions['class'] = 'col-sm-12 table-responsive';
            }
        }
    }

    public function addRow($data, $rowOptions = [])
    {
        $this->rows[]       = $data;
        $this->rowOptions[] = $rowOptions;
    }

    public function addEmptyRow()
    {
        $this->rows[]       = [['label' => null, 'options' => ['colspan' => count($this->headers)]]];
        $this->rowOptions[] = [];
    }

    public function run()
    {

        echo Html::beginTag('div', ['class' => 'row']);
        echo Html::beginTag('div', $this->containerOptions);
        echo Html::beginTag('table', $this->options);
        echo Html::beginTag('thead');
        $this->generateHeader();
        $this->headersLabel = \yii\helpers\ArrayHelper::getColumn($this->headers,
            'label');
        echo Html::endTag('thead');

        if ($this->reverseBody) {
            $this->rows       = array_reverse($this->rows);
            $this->rowOptions = array_reverse($this->rowOptions);
        }

        echo Html::beginTag('tbody');
        $this->generateRow();
        echo Html::endTag('tbody');
        if (count($this->summaryResult)) {
            echo Html::beginTag('tfoot');
            $this->generateFooter();
            echo Html::endTag('tfoot');
        }

        echo Html::endTag('table');
        echo Html::endTag('div');
        echo Html::endTag('div');
    }

    public function generateHeader()
    {
        echo Html::beginTag('tr', $this->headerOptions);
        foreach ($this->headers as $header) {
            echo Html::tag('th', $header['label'],
                isset($header['options']) ? $header['options'] : []);
        }
        echo Html::endTag('tr');
    }

    public function generateRow()
    {

        foreach ($this->rows as $index => $row) {
            echo Html::beginTag('tr', $this->rowOptions[$index]);
            foreach ($row as $columnIndex => $data) {
                echo Html::beginTag('td',
                    isset($data['options']) ? $data['options'] : []);

                $this->matrix[$index][$columnIndex] = $data['label'];

                if (is_array($data) && isset($data['label'])) {
                    $labelData = $data['label'];

                    if (isset($data['columnSummary']) && $data['columnSummary'] === true) {
                        if (isset($this->summaryResult[$columnIndex])) {
                            $this->summaryResult[$columnIndex] += $data['label'];
                        } else {
                            $this->summaryResult[$columnIndex] = $data['label'];
                        }
                    }

                    if (isset($data['format'])) {
                        $labelData = \Yii::$app->formatter->format($labelData,
                            $data['format']);
                    }
                } else {
                    $labelData = $data['label'];
                }

                if (isset($data['labelTag'])) {
                    echo Html::tag($data['labelTag'], $labelData);
                } else {
                    echo $labelData;
                }
                echo Html::endTag('td');
            }
            echo Html::endTag('tr');
        }
    }

    public function generateFooter()
    {
        echo Html::beginTag('tr');
        foreach ($this->headers as $index => $header) {
            echo Html::tag('td',
                isset($this->summaryResult[$index]) ? \Yii::$app->formatter->format($this->summaryResult[$index],
                    $this->summaryFormat) : null, ['align' => 'right']);
        }
        echo Html::endTag('tr');
    }

    public function asArray()
    {
        $tableAsArray = $this->rows;
        array_unshift($tableAsArray, $this->headers);
    }
}