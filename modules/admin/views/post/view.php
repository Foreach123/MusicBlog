<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Post $model */

$this->registerCssFile('@web/css/post-view.css');
?>

<article class="post-view">

    <?php if ($model->image): ?>
        <div class="post-hero">
            <img src="/uploads/<?= Html::encode($model->image) ?>" alt="<?= Html::encode($model->title) ?>">
        </div>
    <?php endif; ?>

    <h1 class="post-title"><?= Html::encode($model->title) ?></h1>

    <div class="post-meta">
        <span>Category: <?= Html::encode($model->category->name ?? '—') ?></span>
        <span>Published: <?= Yii::$app->formatter->asDate($model->published_at) ?></span>
    </div>

    <?php if ($model->tags): ?>
        <div class="post-tags">
            <?php foreach ($model->tags as $tag): ?>
                <a href="<?= Url::to(['post/index', 'q' => $tag->name]) ?>" class="tag">
                    #<?= Html::encode($tag->name) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="post-content">
        <?= nl2br(Html::encode($model->content)) ?>
    </div>

</article>

<!-- COMMENTS -->
<section class="comments">

    <h2 class="comments-title">Comments</h2>

    <div class="comment">
        <div class="comment-author">Alex</div>
        <div class="comment-text">Great article, very interesting!</div>
    </div>

    <div class="comment">
        <div class="comment-author">Maria</div>
        <div class="comment-text">I like this music trend.</div>
    </div>

    <div class="comment">
        <div class="comment-author">John</div>
        <div class="comment-text">Nice overview 👍</div>
    </div>

</section>
