<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Повернутися до Адмін-Меню', ['/admin/dashboard/index'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Видалити', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете видалити цього користувача?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Користувачі', ['/admin/user/index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="user-details">
        <p><strong>Ім'я:</strong> <?= Html::encode($model->name) ?></p>
        <p><strong>Логін:</strong> <?= Html::encode($model->login) ?></p>
        <p><strong>Auth Key:</strong> <?= Html::encode($model->auth_key) ?></p>
    </div>

    <div class="user-image">
        <?= Html::img($model->getImage(), ['width' => '200']) ?>
    </div>

</div>
