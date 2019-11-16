<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/14/2019
 * Time: 12:52 AM
 */

namespace nadzif\base\actions\ajax;


use nadzif\base\helpers\StringHelper;
use nadzif\base\models\FormModel;
use nadzif\base\widgets\FloatAlert;
use yii\base\Action;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class FormAction extends Action
{
    public $formModel;
    public $scenario = FormModel::SCENARIO_DEFAULT;

    public $query;
    public $key = 'id';

    public $successTitle;
    public $successMessage;
    public $failedTitle;
    public $failedMessage;

    public $view       = '@nadzif/base/layouts/ajax/_form';
    public $viewParams = [];

    public $condition = true;

    public $refreshGrid = true;
    public $gridViewId;

    public function run()
    {

        /** @var FormModel $formModel */
        $formModel           = $this->formModel;
        $formModel->scenario = $this->scenario;

        $isUpdate = $this->scenario == FormModel::SCENARIO_UPDATE;

        if ($isUpdate) {
            $action = [
                'present'    => \Yii::t('app', 'update'),
                'past'       => \Yii::t('app', 'updated'),
                'continuous' => \Yii::t('app', 'updating'),
            ];
        } else {
            $action = [
                'present'    => \Yii::t('app', 'create'),
                'past'       => \Yii::t('app', 'created'),
                'continuous' => \Yii::t('app', 'creating'),
            ];
        }
        $requestParam = \Yii::$app->request->get($this->key);

        if ($isUpdate) {

            /** @var ActiveQuery $query */
            $query            = $this->query;
            $formModel->model = $query->andWhere([$this->key => $requestParam])->one();

            if ($formModel->model) {
                $formModel->loadAttributes();
            } else {
                return Json::encode([
                    'data' => [
                        'alert' => [
                            [
                                'type'    => 'warning',
                                'title'   => $this->failedTitle ?: \Yii::t('app', 'Data Not Found'),
                                'message' => $this->failedMessage
                                    ?: \Yii::t('app', 'Cannot find selected item for update.')
                            ]
                        ]
                    ]
                ]);
            }
        }

        if (\Yii::$app->request->isAjax) {
            $alertData = [];
            if ($formModel->load(\Yii::$app->request->post())) {
                $modelAttributes = $formModel->attributes;
                if ($this->condition && $formModel->save()) {
                    $title   = StringHelper::replace(
                        $this->successTitle,
                        ucwords(\Yii::t('app', '{present} Success', $action)),
                        $modelAttributes
                    );
                    $message = StringHelper::replace(
                        $this->successMessage,
                        \Yii::t('app', 'Record {past} successfully.', $action),
                        $modelAttributes
                    );

                    $alertData[] = ['type' => FloatAlert::TYPE_SUCCESS, 'title' => $title, 'message' => $message];
                } else {
                    if ($formModel->hasErrors()) {
                        $formErrors = '';
                        foreach ($formModel->getErrors() as $attribute => $error) {
                            $formErrors .= $formModel->getAttributeLabel($attribute) . '<br>';
                            $formErrors .= Html::ul($error);
                            $formErrors .= '<br>';
                        }

                        $alertData[] = [
                            'type'    => FloatAlert::TYPE_WARNING,
                            'title'   => \Yii::t('app', 'Validation Failed'),
                            'message' => $formErrors
                        ];
                    }


                    $title   = StringHelper::replace(
                        $this->failedTitle,
                        ucwords(\Yii::t('app', '{present} Failed', $action)),
                        $modelAttributes
                    );
                    $message = StringHelper::replace(
                        $this->failedMessage,
                        \Yii::t('app', 'Failed while {continuous} record.', $action),
                        $modelAttributes
                    );

                    $alertData[] = [
                        'type'    => 'danger',
                        'title'   => $title,
                        'message' => $message
                    ];
                }

                return Json::encode(['data' => ['alert' => $alertData]]);
            }
        }

        $_viewParams = [
            'formModel'        => $formModel,
            'activeFormConfig' => [],
            'actionUrl'        => [$this->controller->getRoute(), $this->key => $requestParam],
        ];

        if ($this->refreshGrid) {
            $_viewParams['gridViewId'] = $this->gridViewId;
        }

        return $this->controller->renderAjax($this->view, ArrayHelper::merge($_viewParams, $this->viewParams));

    }
}