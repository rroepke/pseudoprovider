<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace frontend\controllers;

use dektrium\user\controllers\SettingsController as BaseSettingsController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * SettingsController manages updating user settings
 *
 * @property \dektrium\user\Module $module
 * *
 * @author Rene Roepke
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class SettingsController extends BaseSettingsController
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                    'delete'     => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['account', 'networks', 'disconnect', 'delete'],
                        'roles'   => ['@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['confirm'],
                        'roles'   => ['?', '@'],
                    ],
                ],
            ],
        ];
    }
}
