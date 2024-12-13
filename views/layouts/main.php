<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <link rel="stylesheet" href="/web/css/style.css">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?php
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-expand-lg navbar-light bg-light',
    ],
]);

$menuItems = [];

// Загальні пункти меню для всіх користувачів
$menuItems[] = ['label' => 'Home', 'url' => ['/site/index']];

// Якщо користувач авторизований і є адміністратором, додаємо пункти Адмін-Панелі
if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()) {
    $menuItems[] = ['label' => 'Адмін Dashboard', 'url' => ['/admin/dashboard/index']];
    $menuItems[] = ['label' => 'Користувачі', 'url' => ['/admin/user/index']];
    $menuItems[] = ['label' => 'Статті', 'url' => ['/admin/article/index']];
}

// Якщо користувач авторизований і не є адміністратором, додаємо пункти користувацької панелі
if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin()) {
    $menuItems[] = ['label' => 'Мої Статті', 'url' => ['/user/article/index']];
    $menuItems[] = ['label' => 'Створити Статтю', 'url' => ['/user/article/create']];
}

// Відображаємо ліве меню навігації
echo Nav::widget([
    'options' => ['class' => 'navbar-nav me-auto mb-2 mb-lg-0'],
    'items' => $menuItems,
]);

// Праве меню навігації (Login/Logout)
$rightMenuItems = [];
if (Yii::$app->user->isGuest) {
    $rightMenuItems[] = ['label' => 'Login', 'url' => ['/auth/login']];
} else {
    $rightMenuItems[] = '<li>'
    . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
    . Html::submitButton(
        'Logout (' . Html::encode(Yii::$app->user->identity->login) . ')',
        [
            'class' => 'btn btn-danger logout',
            'style' => 'margin-left: 10px;',
            'onclick' => 'return confirm("Ви дійсно хочете вийти?")', // Додаємо підтвердження
        ]
    )
    . Html::endForm()
    . '</li>';

}

echo Nav::widget([
    'options' => ['class' => 'navbar-nav ms-auto mb-2 mb-lg-0'],
    'items' => $rightMenuItems,
]);

// Кнопка повернення до Адмін-Меню, якщо користувач адміністратор і не знаходиться на сторінці Адмін-Панелі
$currentRoute = Yii::$app->controller->route;
if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin() && strpos($currentRoute, 'admin/') !== 0) {
    echo Html::a('Повернутися до Адмін-Меню', ['/admin/dashboard/index'], ['class' => 'btn btn-primary ms-2']);
}

NavBar::end();
?>

<div class="container">
    <?= Alert::widget() ?>
    <!-- main content -->
    <div class="main-content">
        <div class="row">
            <?= $content ?>
        </div>
    </div>
</div>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <p class="float-start">&copy; My Company <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
