<?php

namespace app\modules\admin;

use Yii;
use yii\filters\AccessControl;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\NotFoundHttpException();
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Дозволити лише авторизованим користувачам
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin(); // Використовуйте ваш метод для перевірки адміністратора
                        }
                    ],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        // ваш код ініціалізації модуля
    }
}
