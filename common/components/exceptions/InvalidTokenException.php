<?php

namespace common\components\exceptions;

use yii\base\UserException;
use yii\web\HttpException;

/**
 * Class InvalidTokenException
 *
 * @author Rene Roepke
 */
class InvalidTokenException extends UserException {

    public function getName() {
        return "Invalid Token";
    }
}