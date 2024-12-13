<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = $model->title;
?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Повернутися до Адмін-Меню', ['/admin/dashboard/index'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Видалити', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете видалити цей пост?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Всі пости', ['/admin/article/index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="article-details">
        <p><strong>Дата:</strong> <?= Yii::$app->formatter->asDate($model->date) ?></p>
        <p><strong>Тема:</strong> <?= Html::encode($model->topic->name) ?></p>
        <p><strong>Автор:</strong> <?= Html::encode($model->user->name) ?></p>
        <p><strong>Переглядів:</strong> <?= Html::encode($model->viewed) ?></p>
        <p><strong>Теги:</strong> <?= Html::encode($model->tag) ?></p>
    </div>

    <div class="article-image">
        <?= Html::img($model->getImage(), ['width' => '300']) ?>
    </div>

    <div class="article-description">
        <p><?= nl2br(Html::encode($model->description)) ?></p>
    </div>

</div>
