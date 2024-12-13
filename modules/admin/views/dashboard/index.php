<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Admin Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dashboard-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Ласкаво просимо до Адмін-Панелі!</p>
    
    <!-- Додаткові кнопки для керування -->
    <p>
        <?= Html::a('Керувати Користувачами', ['/admin/user/index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Керувати Статтями', ['/admin/article/index'], ['class' => 'btn btn-primary']) ?>
    </p>
</div>
