<?php

namespace app\controllers;

use Yii;
use app\models\Topic;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class TopicController extends Controller
{
    /**
     * Дія для перегляду топіку та його статей.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException якщо топік не знайдений
     */
    public function actionView($id)
    {
        $topic = $this->findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $topic->getArticles(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('view', [
            'topic' => $topic,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Знаходить модель топіку за ID.
     * 
     * @param integer $id
     * @return Topic
     * @throws NotFoundHttpException якщо топік не знайдений
     */
    protected function findModel($id)
    {
        if (($model = Topic::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Топік не знайдено.');
    }
}
