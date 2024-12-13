<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $popular app\models\Article[] */
/* @var $recent app\models\Article[] */
/* @var $topics app\models\Topic[] */
?>

<!-- Популярні Статті -->
<div class="mb-4">
    <h5>Популярні Статті</h5>
    <ul class="list-group">
        <?php foreach ($popular as $popArticle): ?>
            <li class="list-group-item">
                <?= Html::a(Html::encode($popArticle->title), ['view', 'id' => $popArticle->id], ['class' => 'text-decoration-none']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Останні Статті -->
<div class="mb-4">
    <h5>Останні Статті</h5>
    <ul class="list-group">
        <?php foreach ($recent as $recArticle): ?>
            <li class="list-group-item">
                <?= Html::a(Html::encode($recArticle->title), ['article/view', 'id' => $recArticle->id], ['class' => 'text-decoration-none']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Топіки -->
<div class="mb-4">
    <h5>Топіки</h5>
    <ul class="list-group">
        <?php foreach ($topics as $topic): ?>
            <li class="list-group-item">
                <?= Html::a(Html::encode($topic->name), ['topic/view', 'id' => $topic->id], ['class' => 'text-decoration-none']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

