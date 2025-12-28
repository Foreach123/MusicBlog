<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->registerCssFile('@web/css/music-posts.css');

/** @var yii\web\View $this */
/** @var app\models\Post[] $posts */
/** @var yii\data\Pagination $pagination */
/** @var string $q */
/** @var app\models\Category[] $categories */
/** @var int $cat */
/** @var string $q */
?>

<div class="cats-bar">
    <a class="cat-chip <?= $cat === 0 ? 'active' : '' ?>"
        href="<?= Url::to(['post/index', 'q' => $q]) ?>">
        All
    </a>

    <?php foreach ($categories as $c): ?>
        <a class="cat-chip <?= $cat === (int)$c->id ? 'active' : '' ?>"
            href="<?= Url::to(['post/index', 'cat' => $c->id, 'q' => $q]) ?>">
            <?= Html::encode($c->name) ?>
        </a>
    <?php endforeach; ?>
</div>
<h1 class="page-title">Smart music (Admin)</h1>

<div class="posts-toolbar">
    <?= Html::beginForm(['index'], 'get', ['class' => 'search-form']) ?>
    <?php if (!empty($cat)): ?>
        <?= Html::hiddenInput('cat', $cat) ?>
    <?php endif; ?>
    <?= Html::textInput('q', $q ?? '', [
        'class' => 'search-input',
        'placeholder' => 'Search'
    ]) ?>
    <?= Html::submitButton('Search', ['class' => 'search-btn']) ?>

    <?php if (!empty($q)): ?>
        <a class="search-reset" href="<?= Url::to(['post/index']) ?>">Reset</a>
    <?php endif; ?>

    <?php if (!empty($cat)): ?>
        <a class="search-reset"
            href="<?= Url::to(['post/index']) ?>">
            All categories
        </a>
    <?php endif; ?>
    <?= Html::endForm() ?>
</div>

<?php if (!empty($cat) && !empty($posts) && $posts[0]->category): ?>
    <div class="active-filter">
        Showing category: <b><?= Html::encode($posts[0]->category->name) ?></b>
    </div>
<?php elseif (!empty($cat) && empty($posts)): ?>
    <div class="active-filter">
        Showing category ID: <b><?= Html::encode($cat) ?></b>
    </div>
<?php endif; ?>

<div class="posts-grid">
    <?php foreach ($posts as $post): ?>
        <div class="music-card">
            <a href="<?= Url::to(['view', 'id' => $post->id]) ?>">
                <?php if (!empty($post->image)): ?>
                    <img class="music-cover"
                        src="/uploads/<?= Html::encode($post->image) ?>"
                        alt="<?= Html::encode($post->title) ?>">
                <?php else: ?>
                    <div class="music-cover placeholder"></div>
                <?php endif; ?>
            </a>

            <h2 class="music-title">
                <a href="<?= Url::to(['view', 'id' => $post->id]) ?>">
                    <?= Html::encode($post->title) ?>
                </a>
            </h2>

            <p class="music-category">
                Category:
                <?php if ($post->category): ?>
                    <a class="cat-link" href="<?= Url::to(['post/index', 'cat' => $post->category->id]) ?>">
                        <?= Html::encode($post->category->name) ?>
                    </a>
                <?php else: ?>
                    No category
                <?php endif; ?>
            </p>

            <p class="music-text">
                <?= Html::encode($post->content) ?>
            </p>

            <?php if ($post->tags): ?>
                <div class="music-tags">
                    <?php foreach ($post->tags as $tag): ?>
                        <a class="tag" href="<?= Url::to(['index', 'q' => $tag->name]) ?>">
                            #<?= Html::encode($tag->name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <p class="music-date">
                Published: <?= Html::encode($post->published_at) ?>
            </p>

            <!-- CRUD -->
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