<?php

namespace frontend\controllers;

use common\components\CommunicationHandler;
use Yii;
use common\components\exceptions\NotRegisteredException;
use common\components\exceptions\InvalidTokenException;
use common\models\Pseudonym;
use common\models\Service;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PseudonymController extends Controller {

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['request', 'index'],
                'rules' => [
                    // deny all POST requests
                    [
                        'allow' => false,
                        'verbs' => ['POST'],
                        'actions' => ['delete'],
                    ],
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['request', 'index'],
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Pseudonym::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRequest($cipher, $service, $mac) {

        $serviceModel = Service::find()->where(['name' => $service])->one();

        if (is_null($serviceModel)) {
            throw new NotRegisteredException('The requested service \'' . $service . '\' is not registered');
        }

        $key = $serviceModel->token;
        $url = $serviceModel->return_url;

        $comHandler = new CommunicationHandler($key,$serviceModel->chiffre,$serviceModel->hash);
        $params = $comHandler->decrypt_data($cipher);

        $comHandler->validate_request_params($params);

        $comHandler->verify_hmac($mac,$params);

        if ($service != $params->service){
            throw new InvalidTokenException('Request (Service) is not valid');
        }

        $pseudonymModel = Pseudonym::find()->where([
            'user' => Yii::$app->user->id
        ])->one();

        if (Yii::$app->request->isPost) {

            $postdata = Yii::$app->request->post();

            if (array_key_exists('grant',$postdata) && $postdata['grant'] == 'grant'){

                $pseudonym = $pseudonymModel->pseudonym;

                $response = $comHandler->build_response($url, $comHandler::CODE_SUCCESS, time(), $pseudonym);

                $this->redirect($response);

            } else if (array_key_exists('deny',$postdata) && $postdata['deny'] == 'deny'){

                $response = $comHandler->build_response($url, $comHandler::CODE_FAIL);

                $this->redirect($response);
            }
        }

        $model = new \stdClass();
        $model->service = $service;
        $model->cipher = $cipher;

        return $this->render('request', [
            'model' => $model,
            'service' => $serviceModel,
            'pseudonym' => $pseudonymModel,
        ]);

        return $this->render('error');
    }

    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pseudonym the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Pseudonym::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGrant($id, $service) {
        var_dump($id, $service);
        return $this->render('grant');
    }
}
