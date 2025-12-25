<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

$this->registerCssFile('@web/css/music-posts.css'); // CSS
?>

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
            <?php if (!empty($post->image)): ?>
                <a href="<?= Url::to(['post/view', 'id' => $post->id]) ?>">
                    <?php if (!empty($post->image)): ?>
                        <img class="music-cover"
                            src="/uploads/<?= Html::encode($post->image) ?>"
                            alt="<?= Html::encode($post->title) ?>">
                    <?php else: ?>
                        <div class="music-cover placeholder"></div>
                    <?php endif; ?>
                </a>

            <?php else: ?>
                <div class="music-cover placeholder"></div>
            <?php endif; ?>

            <h2 class="music-title">
                <a href="<?= Url::to(['post/view', 'id' => $post->id]) ?>">
                    <?= Html::encode($post->title) ?>
                </a>
            </h2>

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
        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>