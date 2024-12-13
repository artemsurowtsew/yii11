<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'C4pbAijgQS89owMmjrbZZExmEBDFzuwv',
            'baseUrl'=> '/web/2/web',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
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
        'db' => $db, // Переконайтесь, що тут підключена правильна конфігурація
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'admin/index' => 'admin/default/index',
                'admin/<controller>/<action>' => 'admin/<controller>/<action>', // Додаткові маршрути для адмін-контролерів 
                 // Користувацькі маршрути
                'user/<controller:\w+>/<action:\w+>' => 'user/<controller>/<action>',
                'user/index' => 'user/default/index',
                'signup' => 'auth/signup',
                'view<id:\d+>' => '/view?id=',
                'login' => 'auth/login',
                'topic/<id:\d+>' => 'topic/view',
                '<action>' => 'site/<action>',
                '<controller:(post|comment)>/<id:\d+>/<action:(create|update|delete)>' => '<controller>/<action>',
                '<controller:(post|comment)>/<id:\d+>' => '<controller>/view',
                '<controller:(post|comment)>s' => '<controller>/index',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // конфігурація для 'dev' середовища
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // розкоментуйте наступне, щоб додати ваш IP, якщо ви не підключаєтесь з localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'], // Додайте ваш IP-адрес, якщо працюєте з іншого комп'ютера
    ];
}

return $config;
