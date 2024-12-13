<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Admin Panel';
?>
<div class="admin-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Ласкаво просимо до Адмін-Панелі!</p>

    <div class="admin-buttons">
        <?= Html::a('Керувати Постами', ['article/index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Керувати Користувачами', ['user/index'], ['class' => 'btn btn-warning']) ?>
        
    </div>
</div>

<style>
    .admin-buttons {
        margin-top: 20px;
    }
    .admin-buttons .btn {
        margin-right: 10px;
        margin-bottom: 10px;
    }
</style>
