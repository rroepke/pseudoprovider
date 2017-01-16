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

/**
 * Class PseudonymController
 * @package frontend\controllers
 */
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
                        'actions' => ['web-request', 'index'],
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Displays a list of pseudonyms.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Pseudonym::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Handles an API request
     * @param $cipher
     * @param $service
     * @param $mac
     * @return string
     * @throws InvalidTokenException
     * @throws NotRegisteredException
     */
    public function actionApiRequest($cipher, $service, $mac) {

        $serviceModel = Service::find()->where(['name' => $service])->one();

        if (is_null($serviceModel)) {
            throw new NotRegisteredException('The requested service \'' . $service . '\' is not registered');
        }

        $key = $serviceModel->token;
        $url = $serviceModel->return_url;

        $comHandler = new CommunicationHandler($key,$serviceModel->cipher,$serviceModel->hash);
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

    /**
     * Handles a Web request
     * @param $cipher
     * @param $service
     * @param $mac
     * @return mixed
     * @throws InvalidTokenException
     * @throws NotRegisteredException
     * @internal param int $id
     */
    public function actionWebRequest($cipher, $service, $mac) {

        $serviceModel = Service::find()->where(['name' => $service])->one();

        if (is_null($serviceModel)) {
            throw new NotRegisteredException('The requested service \'' . $service . '\' is not registered');
        }

        $key = $serviceModel->token;
        $url = $serviceModel->return_url;

        $comHandler = new CommunicationHandler($key,$serviceModel->cipher,$serviceModel->hash);
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

    /**
     * Displays a single Pseudonym model.
     * @param integer $id
     * @return mixed
     */
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

}
