<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->registerCssFile('@web/css/music-posts.css'); // CSS
?>

<h1 class="page-title">Smart music</h1>

<div class="posts-grid">
    <?php foreach ($posts as $post): ?>
        <div class="music-card">
            <h2 class="music-title"><?= Html::encode($post->title) ?></h2>

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