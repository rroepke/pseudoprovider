<?php

namespace frontend\controllers;

use common\models\Pseudonym;
use Yii;
use dektrium\user\controllers\RegistrationController as BaseRegistrationController;
use dektrium\user\models\User;

/**
 * Class RegistrationController
 * @package frontend\controllers
 * @author Rene Roepke
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class RegistrationController extends BaseRegistrationController{

    /**
     * Handle register action
     * @return string
     */
    public function actionRegister() {

        $this->on(self::EVENT_AFTER_REGISTER,function ($event) {
            $username = Yii::$app->request->post()['register-form']['username'];

            $model = User::find()->where(['username'=>$username])->one();
            $id = $model->id;

            $pseudonym = new Pseudonym();
            $pseudonym->user = $id;
            $pseudonym->pseudonym = hash('sha256', $id.time().$username);
            $pseudonym->save();
        });

        $result = parent::actionRegister();

        return $result;
    }

    /**
     * Handle connect action
     * @param string $code
     * @return string
     */
    public function actionConnect($code) {

        $this->on(self::EVENT_AFTER_CONNECT,function ($event) {
            $username = Yii::$app->request->post()['User']['username'];

            $model = User::find()->where(['username'=>$username])->one();
            $id = $model->id;

            $pseudonym = new Pseudonym();
            $pseudonym->user = $id;
            $pseudonym->pseudonym = hash('sha256', $id.time().$username);
            $pseudonym->save();
        });

        $result = parent::actionConnect($code);

        return $result;
    }

}