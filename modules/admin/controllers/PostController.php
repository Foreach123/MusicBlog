<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use app\models\Post;
use Yii;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\db\Expression;
use app\models\Comment;
use app\models\Category;

class PostController extends Controller
{
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $newComment = new Comment();
        return $this->render('view', [
            'model' => $model,
            'newComment' => $newComment,
        ]);
    }


    public function actionIndex()
    {
        $q = trim(Yii::$app->request->get('q', ''));
        $cat = (int)Yii::$app->request->get('cat', 0);

        $categories = Category::find()->orderBy(['name' => SORT_ASC])->all();

        $query = Post::find()
            ->alias('p')
            ->where(['p.published' => 1])
            ->joinWith(['category c']);

        if ($cat > 0) {
            $query->andWhere(['p.category_id' => $cat]);
        }

        if ($q !== '') {
            $tokens = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($tokens as $t) {
                $tagExists = (new Query())
                    ->from(['pt' => 'post_tag'])
                    ->innerJoin(['tg' => 'tags'], 'tg.id = pt.tag_id')
                    ->where(new Expression('pt.post_id = p.id'))
                    ->andWhere(['like', 'tg.name', $t]);

                $query->andWhere([
                    'or',
                    ['like', 'p.title', $t],
                    ['like', 'p.content', $t],
                    ['like', 'c.name', $t],
                    ['exists', $tagExists],
                ]);
            }
        }

        $pagination = new Pagination([
            'defaultPageSize' => 6,
            'totalCount' => $query->count(),
            'params' => ['q' => $q, 'cat' => $cat],
        ]);

        $posts = $query
            ->orderBy(['p.published_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'posts' => $posts,
            'pagination' => $pagination,
            'q' => $q,
            'cat' => $cat,
            'categories' => $categories,
        ]);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['add-comment'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // only logged in
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post())) {
            // handle uploaded file
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate() && $model->uploadImage() && $model->save(false)) {
                $model->syncTagsFromInput();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->loadTagsToInput();
        $oldImage = $model->image;

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {
                if ($model->imageFile) {
                    // uploadImage deletes old image itself
                    $model->uploadImage();
                } else {
                    // keep old image
                    $model->image = $oldImage;
                }
                $model->save(false);
                $model->syncTagsFromInput();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        // remove image file
        $model->removeImageFile();
        $model->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        $m = Post::find()
            ->where(['id' => $id, 'published' => 1])
            ->with(['category', 'tags', 'comments.user', 'comments.replies.user'])
            ->one();

        if ($m !== null) {
            return $m;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionAddComment($id)
    {
        $post = Post::find()->where(['id' => $id, 'published' => 1])->one();
        if (!$post) {
            throw new NotFoundHttpException('Post not found.');
        }

        $comment = new Comment();
        $comment->post_id = (int)$id;
        $comment->user_id = (int)Yii::$app->user->id;
        $comment->status = 1;

        $parentId = Yii::$app->request->post('parent_id');
        $comment->parent_id = $parentId !== null && $parentId !== '' ? (int)$parentId : null;

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            return $this->redirect(['post/view', 'id' => $id, '#' => 'comments']);
        }

        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
            'newComment' => $comment,
        ]);
    }
}
