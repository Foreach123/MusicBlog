<?php

/** @var yii\web\View $this */
/** @var string $content */
$this->registerCssFile('@web/css/nav-bar.css'); // CSS

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

$user = Yii::$app->user;
/** @var \app\models\User|null $identity */
$identity = $user->identity;

$isAdmin = !$user->isGuest && $identity && $identity->isAdmin();


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
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => 'Smart music',
            'brandUrl' => ['/post/index'],
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'encodeLabels' => false,
            'items' => array_filter([
                ['label' => 'Blog', 'url' => ['/post/index']],

                $isAdmin ? ['label' => 'Admin', 'url' => ['/admin/post/index']] : null,
                $isAdmin ? ['label' => '+ New Post', 'url' => ['/admin/post/create']] : null,

                $user->isGuest
                    ? ['label' => 'Login', 'url' => ['/auth/login']]
                    : [
                        'label' => 'Logout (' . Html::encode($identity->name ?? $identity->email) . ')',
                        'url' => ['/auth/logout'],
                        'linkOptions' => ['data-method' => 'post'],
                    ],

                $user->isGuest ? ['label' => 'Sign Up', 'url' => ['/auth/signup']] : null,
            ]),
        ]);

        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>