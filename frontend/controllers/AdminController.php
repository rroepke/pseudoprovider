<?php

namespace frontend\controllers;

use dektrium\user\controllers\AdminController as BaseAdminController;
use dektrium\user\filters\AccessRule;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * AdminController allows you to administrate users.
 * @package frontend\controllers
 * @author Rene Roepke
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class AdminController extends BaseAdminController
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete'  => ['post'],
                    'confirm' => ['post'],
                    'block'   => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'index', 'update'],
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update'],
                            'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
}
