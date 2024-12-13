<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Керування Постами';
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Створити Пост', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'description:ntext',
            'date',
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::img($model->getImage(), ['width' => '100']);
                },
            ],
            'tag',
            'viewed',
            [
                'attribute' => 'topic_id',
                'value' => function ($model) {
                    return $model->topic->name;
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Topic::find()->all(), 'id', 'name'),
            ],
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user->name;
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\User::find()->all(), 'id', 'name'),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
