<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class DashboardController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Дозволено лише авторизованим користувачам
                        'matchCallback' => function($rule, $action) {
                            return Yii::$app->user->identity->isAdmin(); // Перевірка на адміністратора
                        }
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    throw new \yii\web\NotFoundHttpException('Доступ заборонено.');
                },
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // Тут можна вказати дозволені методи для дій
                ],
            ],
        ];
    }

    /**
     * Displays the dashboard.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
