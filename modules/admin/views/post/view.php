<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
<section class="comments" id="comments">

    <h2 class="comments-title">Comments</h2>

    <?php if (Yii::$app->user->isGuest): ?>
        <p class="comments-note">Please log in to leave a comment.</p>
    <?php else: ?>
        <div class="comment-form">
            <?php $form = ActiveForm::begin([
                'action' => ['post/add-comment', 'id' => $model->id],
                'method' => 'post',
            ]); ?>

            <?= $form->field($newComment, 'content')
                ->textarea(['rows' => 3, 'placeholder' => 'Write a comment...'])
                ->label(false) ?>

            <div class="comment-form-actions">
                <?= Html::submitButton('Send', ['class' => 'search-btn']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($model->comments)): ?>
        <?php foreach ($model->comments as $c): ?>
            <div class="comment">
                <div class="comment-author">
                    <?= Html::encode($c->user->name ?? $c->user->email ?? 'User') ?>
                    <span class="comment-date">
                        <?= Yii::$app->formatter->asDatetime($c->created_at) ?>
                    </span>
                </div>
                <div class="comment-text"><?= nl2br(Html::encode($c->content)) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="comments-note">No comments yet.</p>
    <?php endif; ?>

</section>
