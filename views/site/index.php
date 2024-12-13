<?php
use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\bootstrap5\Card;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $articles app\models\Article[] */
/* @var $topics app\models\Topic[] */
/* @var $pagination yii\data\Pagination */
/* @var $popular app\models\Article[] */
/* @var $recent app\models\Article[] */

$this->title = 'Головна';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <div class="row">
        <!-- Основний контент -->
        <div class="col-md-8">
            <?php foreach ($articles as $article): ?>
                <article class="post mb-4">
                    <div class="card">
                        <!-- Зображення статті -->
                        <a href="<?= Url::to(['view', 'id' => $article->id]) ?>">
                            <img src="<?= $article->getImage() ?>" class="card-img-top img-index" alt="<?= Html::encode($article->title) ?>">
                        </a>

                        <div class="card-body">
                            <!-- Назва топіку -->
                            <h6 class="card-subtitle mb-2 text-muted">
                                <?= Html::a(Html::encode($article->topic->name), ['topic/view', 'id' => $article->topic->id], ['class' => 'text-decoration-none']) ?>
                                <?= Url::toRoute(['/topic', 'id' => $article->topic->id]) ?>
                            </h6>

                            <!-- Заголовок статті -->
                            <h5 class="card-title">
                                <?= Html::a(Html::encode($article->title), ['view', 'id' => $article->id], ['class' => 'text-dark text-decoration-none']) ?>
                            </h5>

                            <!-- Опис статті -->
                            <p class="card-text">
                                <?= Html::encode(mb_strimwidth($article->description, 0, 360, "...")) ?>
                            </p>

                            <!-- Кнопка для продовження читання -->
                            <div class="d-grid gap-2">
                                <?= Html::a('Читати далі', ['view', 'id' => $article->id], ['class' => 'btn btn-primary']) ?>
                            </div>

                            <!-- Інформація про автора та дату -->
                            <div class="mt-3">
                                <span class="text-muted">Автор: <?= Html::encode($article->user->name); ?></span>
                                <span class="text-muted ms-3">Дата: <?= Yii::$app->formatter->asDate($article->date); ?></span>
                            </div>
                        </div>

                        <!-- Соціальний поділ -->
                        <div class="card-footer text-muted d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa fa-eye"></i> <?= (int)$article->viewed; ?> переглядів
                            </div>
                            <div>
                                <a href="#" class="text-decoration-none me-2"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-decoration-none me-2"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-decoration-none"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>

            <!-- Пагінація -->
            <?= LinkPager::widget([
                'pagination' => $pagination,
                'options' => ['class' => 'pagination justify-content-center'],
                'linkOptions' => ['class' => 'page-link'],
                'prevPageLabel' => '<span aria-hidden="true">&laquo;</span>',
                'nextPageLabel' => '<span aria-hidden="true">&raquo;</span>',
                'disabledListItemSubTagOptions' => ['tag' => 'span'],
            ]) ?>
        </div>

        <!-- Бокова панель -->
        <div class="col-md-4">
            <?php echo \Yii::$app->view->renderFile('@app/views/site/right.php', compact('popular','recent','topics'));?>
        </div>
    </div>
</div>
