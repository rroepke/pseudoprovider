<?php

$c = parse_ini_file(__DIR__ . '/../../common/config/secure.ini', true);

return [
    'name'=>'Trail Application',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            'viewPath' => '@common/mail',
//            // send all mails to a file by default. You have to set
//            // 'useFileTransport' to false and configure a transport
//            // for the mailer to send real emails.
//            'useFileTransport' => true,
            // TODO 7 - Insert -------------
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $c['smtp_host'],
                'username' => $c['smtp_username'],
                'password' => $c['smtp_password'],
                'port' => $c['smtp_port'],
                'encryption' => 'tls',
            ],
            // -----------------------------
        ],
    ],
];
