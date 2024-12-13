<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
