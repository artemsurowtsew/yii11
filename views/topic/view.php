<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $topic app\models\Topic */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $topic->name;
$this->params['breadcrumbs'][] = ['label' => 'Топіки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <h2>Статті</h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::img(Yii::getAlias('@web/uploads/') . $model->image, ['width' => '100px']);
                },
            ],
            'date',
            'tag',
            [
                'attribute' => 'user_id',
                'label' => 'Автор',
                'value' => function ($model) {
                    return $model->user->username ?? 'Невідомо';
                },
                'filter' => yii\helpers\ArrayHelper::map(app\models\User::find()->all(), 'id', 'username'),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
