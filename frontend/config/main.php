<?php

$c = parse_ini_file(__DIR__ . '/../../common/config/secure.ini', true);

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'class' => 'common\components\Request',
            'web' => '/frontend/web',
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        // TODO 3 - Comment out -------
//        'user' => [
//            'identityClass' => 'common\models\User',
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
//        ],
        // ----------------------------
        // TODO 4 - Insert ------------
        'user' => [
            'identityCookie' => [
                'name'     => '_frontendIdentity',
                'path'     => '/',
                'httpOnly' => true,
            ],
            'enableSession' => true,
            //'authTimeout' => 120,
        ],
        // ----------------------------
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
            // TODO 5 - Insert --------
            'cookieParams' => [
                'httpOnly' => true,
                'path'     => '/',
            ],
            'timeout' => 60,
            // ------------------------
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Hide index.php
            'showScriptName' => false,
            // Use pretty URLs
            'enablePrettyUrl' => true,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api'],
            ],
        ],
        // TODO 9 - Insert -------------------------------------------
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => $c['oauth_google_clientId'], // '1066984194463-4occr0b5078u8pgjf37ff42t15la3l67.apps.googleusercontent.com',
                    'clientSecret' => $c['oauth_google_clientSecret']//'7jJHFWdpnkNixrOPkG-JilNg',
                ],
            ],
        ],
        // -----------------------------------------------------------
        // TODO 10 - Insert ------------------------------------------
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@frontend/views/user'
                ],
            ],
        ],
        // -----------------------------------------------------------
    ],
    // TODO 1 - Insert -----------------------
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            // TODO 6 - Insert --------
            // 'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 21600,
            'cost' => 12,
            //'admins' => ['admin'],
            // ------------------------
            // TODO 2 - Insert ---
            // 'as frontend' => 'dektrium\user\filters\FrontendFilter',
            // -------------------
            // TODO 8 - Insert ---
            'enableFlashMessages' => false,
            // -------------------
            // TODO 11 - Insert ---
            'admins' => ['roepke'],
            // --------------------
            'controllerMap' => [
                'registration' => 'frontend\controllers\RegistrationController',
                'admin' => 'frontend\controllers\AdminController',
                'settings' => 'frontend\controllers\SettingsController',
            ]
        ],
    ],
    // ---------------------------------------
    'params' => $params,
];
