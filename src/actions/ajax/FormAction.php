<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/14/2019
 * Time: 12:52 AM
 */

namespace nadzif\base\actions\ajax;


use nadzif\base\models\FormModel;
use yii\base\Action;
use yii\db\ActiveQuery;
use yii\helpers\Html;
use yii\helpers\Json;

class FormAction extends Action
{
    public $formModel;
    public $scenario = FormModel::SCENARIO_DEFAULT;

    public $query;
    public $key = 'id';

    public $successAlert;
    public $errorMessage;
    public $showError = true;

    public $view       = '@nadzif/base/layouts/ajax/_form';
    public $viewParams = [];

    public $refreshGrid = true;
    public $gridViewId;

    public function init()
    {

        if (!isset($this->successAlert)) {
            $this->successAlert[] = [
                'type'    => 'success',
                'title'   => \Yii::t('app', 'Create Success'),
                'message' => \Yii::t('app', 'Record has been Created.'),
            ];
        }

        if (!isset($this->errorMessage)) {
            $this->errorMessage = \Yii::t('app', 'Failed while creating record.');
        }

        parent::init();
    }

    public function run()
    {

        /** @var FormModel $formModel */
        $formModel           = $this->formModel;
        $formModel->scenario = $this->scenario;

        if ($this->scenario == FormModel::SCENARIO_UPDATE) {
            $requestParam = \Yii::$app->request->get($this->key);

            /** @var ActiveQuery $query */
            $query            = $this->query;
            $formModel->model = $query->andWhere([$this->key => $requestParam])->one();

            $formModel->loadAttributes();

            if (\Yii::$app->request->isAjax) {
                if ($formModel->load(\Yii::$app->request->post())) {
                    if ($formModel->save()) {
                        return Json::encode(['data' => ['alert' => $this->successAlert]]);
                    } else {
                        $errorMessage = '';
                        foreach ($formModel->getErrors() as $attribute => $error) {
                            $errorMessage .= $formModel->getAttributeLabel($attribute) . '<br>';
                            $errorMessage .= Html::ul($error);
                            $errorMessage .= '<br>';
                        }

                        $errorAlerts = [
                            [
                                'type'    => 'info',
                                'message' => $errorMessage
                            ]
                        ];

                        if ($this->showError) {
                            $errorAlerts[] = [
                                'type'    => 'danger',
                                'title'   => \Yii::t('app', 'Update Failed'),
                                'message' => $this->errorMessage
                            ];
                        }

                        return Json::encode([
                            'data' => ['alert' => $errorAlerts]
                        ]);
                    }

                } else {
                    $pageOptions = [
                        'model'            => $formModel,
                        'modalConfig'      => ['title' => $formModel->model->tableSchema->name],
                        'activeFormConfig' => [],
                        'actionUrl'        => [$this->controller->getRoute(), $this->key => $requestParam],
                    ];

                    if ($this->refreshGrid) {
                        $pageOptions['gridViewId'] = $this->gridViewId;
                    }

                    return $this->controller->renderAjax($this->view, $pageOptions);
                }
            }
        }


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        if ($formModel->load(\Yii::$app->request->post())) {

            if ($formModel->save()) {
                return Json::encode(['data' => ['alert' => $this->successAlert]]);

            } else {

                $errorMessage = '';
                foreach ($formModel->getErrors() as $attribute => $error) {
                    $errorMessage .= $formModel->getAttributeLabel($attribute) . '<br>';
                    $errorMessage .= Html::ul($error);
                    $errorMessage .= '<br>';
                }

                $errorAlerts = [
                    [
                        'type'    => 'warning',
                        'title'   => \Yii::t('app', 'Action Error'),
                        'message' => $errorMessage
                    ]
                ];

                if ($this->showError) {
                    $errorAlerts[] = [
                        'type'    => 'danger',
                        'title'   => \Yii::t('app', 'Create Failed'),
                        'message' => $this->errorMessage
                    ];
                }

                return Json::encode([
                    'data' => ['alert' => $errorAlerts]
                ]);
            }
        }


    }
}