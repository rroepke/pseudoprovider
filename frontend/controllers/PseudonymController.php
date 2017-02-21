<?php

namespace frontend\controllers;

use common\components\CommunicationHandler;
use common\models\User;
use Yii;
use common\components\exceptions\NotRegisteredException;
use common\components\exceptions\InvalidTokenException;
use common\models\Pseudonym;
use common\models\Service;
use yii\data\ActiveDataProvider;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use dektrium\user\helpers\Password;
use yii\web\Response;

/**
 * Class PseudonymController
 * @package frontend\controllers
 * @author Rene Roepke
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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
            [
                'class' => ContentNegotiator::className(),
                'only' => ['app-request'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['web-request', 'app-request'],
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
                        'actions' => ['web-request'],
                        'roles' => ['@'],
                    ],
                    // allow all users
                    [
                        'allow' => true,
                        'actions' => ['app-request'],
                        'roles' => ['?'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
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
     * Handles a Web request
     * @param $cipher
     * @param $service
     * @param $mac
     * @return mixed
     * @throws InvalidTokenException
     * @throws NotRegisteredException
     * @internal param int $id
     */
    public function actionAppRequest($cipher, $service, $mac) {
        try {
        $serviceModel = Service::find()->where(['name' => $service])->one();

        if (is_null($serviceModel)) {
            throw new NotRegisteredException('The requested service \'' . $service . '\' is not registered');
        }

        $key = $serviceModel->token;

        $comHandler = new CommunicationHandler($key,$serviceModel->cipher,$serviceModel->hash);
        $params = $comHandler->decrypt_data($cipher);

        $comHandler->validate_request_params($params, ['username', 'password', 'timestamp', 'service']);

        $comHandler->verify_hmac($mac,$params);

        if ($service != $params->service){
            $params = $comHandler->get_response_params($comHandler::CODE_FAIL, time());
            return $params;
        }

        $userModel = User::find()->where([
            'username' => $params->username
        ])->one();

        $password = $params->password;
        $hash = $userModel->password_hash;

        $validPassword = Yii::$app->getSecurity()->validatePassword($password, $hash);

        if (!$validPassword){
            $params = $comHandler->get_response_params($comHandler::CODE_FAIL, time());

            return $params;
        }

        $pseudonymModel = Pseudonym::find()->where([
            'user' => $userModel->id
        ])->one();

        $pseudonym = $pseudonymModel->pseudonym;
        $params = $comHandler->get_response_params($comHandler::CODE_SUCCESS, time(), $pseudonym);

        return $params;

        } catch (\Exception $err){
            $array = new \stdClass();
            $array->code = 'fail';
            return $array;
        }
    }

}
