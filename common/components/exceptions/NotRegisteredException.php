<?php

namespace common\components\exceptions;

use yii\base\UserException;
use yii\web\HttpException;

/**
 * Class NotRegisteredException
 *
 * @author Rene Roepke
 */
class NotRegisteredException extends UserException {

    public function getName() {
        return "Not Registered";
    }
}