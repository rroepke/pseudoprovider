<?php

namespace common\components\exceptions;

use yii\base\UserException;
use yii\web\HttpException;

class NotRegisteredException extends UserException {

    public function getName() {
        return "Not Registered";
    }
}