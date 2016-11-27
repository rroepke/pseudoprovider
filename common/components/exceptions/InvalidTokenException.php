<?php

namespace common\components\exceptions;

use yii\base\UserException;
use yii\web\HttpException;

class InvalidTokenException extends UserException {

    public function getName() {
        return "Invalid Token";
    }
}