<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/14/2019
 * Time: 12:19 AM
 */

namespace nadzif\base\actions;


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
    public $scenario = FormModel::SCENARIO_DEFAULT;

    public $query;
    public $key = 'id';

    public $successTitle;
    public $successMessage;
    public $failedTitle;
    public $failedMessage;

    public $redirectUrl;

    public $view       = '@nadzif/base/layouts/_form';
    public $viewParams = [];

    public $condition = true;

    public $formClass;
    public $modelClass;

    /** @var FormModel */
    protected $_formModel;

    public function run()
    {

        $this->_formModel           = new $this->formClass;
        $this->_formModel->scenario = $this->scenario;

        if ($this->modelClass) {
            $this->_formModel->model = new $this->modelClass;
        }

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
            $query                   = $this->query;
            $this->_formModel->model = $query->andWhere([$this->key => $requestParam])->one();

            if ($this->_formModel->model) {
                $this->_formModel->loadAttributes();
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

        $alertData = [];
        if ($this->_formModel->load(\Yii::$app->request->post())) {
            $modelAttributes = \Yii::$app->request->post($this->_formModel->formName());
            if ($this->condition && $this->_formModel->submit()) {
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

                \Yii::$app->session->setFlash('success', $message);

                if ($this->redirectUrl) {
                    return $this->controller->redirect($this->redirectUrl);
                }
            } else {
                if ($this->_formModel->hasErrors()) {
                    $formErrors = '';
                    foreach ($this->_formModel->getErrors() as $attribute => $error) {
                        $formErrors .= $this->_formModel->getAttributeLabel($attribute) . '<br>';
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

            foreach ($alertData as $alertDatum) {
                \Yii::$app->session->setFlash($alertDatum['type'], $alertDatum['message']);
            }
        }

        $_viewParams = [
            'formModel'        => $this->_formModel,
            'activeFormConfig' => [],
            'actionUrl'        => [$this->controller->getRoute(), $this->key => $requestParam],
        ];

        return $this->controller->render($this->view, ArrayHelper::merge($_viewParams, $this->viewParams));
    }
}