<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->registerCssFile('@web/css/music-posts.css');
?>

<h1 class="page-title">Smart music (Admin)</h1>

<div class="posts-toolbar">
    <?= Html::beginForm(['post/index'], 'get', ['class' => 'search-form']) ?>
    <?= Html::textInput('q', $q ?? '', [
        'class' => 'search-input',
        'placeholder' => 'Search'
    ]) ?>
    <?= Html::submitButton('Search', ['class' => 'search-btn']) ?>
    <?php if (!empty($q)): ?>
        <a class="search-reset" href="<?= Url::to(['post/index']) ?>">Reset</a>
    <?php endif; ?>
    <?= Html::endForm() ?>
</div>

<div class="posts-grid">
    <?php foreach ($posts as $post): ?>
        <div class="music-card">

            <h2 class="music-title"><?= Html::encode($post->title) ?></h2>

            <?php if (!empty($post->image)): ?>
                <img class="music-cover" src="<?= Yii::getAlias('@web') . '/uploads/' . $post->image ?>" alt="cover">
            <?php else: ?>
                <div class="music-cover placeholder"></div>
            <?php endif; ?>


            <p class="music-category">
                Category:
                <?= Html::encode($post->category->name ?? 'No category') ?>
            </p>

            <p class="music-text">
                <?= Html::encode($post->content) ?>
            </p>

            <?php if ($post->tags): ?>
                <div class="music-tags">
                    <?php foreach ($post->tags as $tag): ?>
                        <a class="tag" href="<?= \yii\helpers\Url::to(['post/index', 'q' => $tag->name]) ?>">
                            #<?= \yii\helpers\Html::encode($tag->name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <p class="music-date">
                Published: <?= $post->published_at ?>
            </p>

            <!-- CRUD BUTTONS -->
            <div class="music-actions">
                <a href="<?= Url::to(['view', 'id' => $post->id]) ?>" class="btn-action view">View</a>
                <a href="<?= Url::to(['update', 'id' => $post->id]) ?>" class="btn-action update">Update</a>
                <a href="<?= Url::to(['delete', 'id' => $post->id]) ?>"
                    data-method="post"
                    data-confirm="Are you sure?"
                    class="btn-action delete">Delete</a>
            </div>

        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>