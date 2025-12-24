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

class PostController extends Controller
{
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }


    public function actionIndex()
    {
        $q = trim(Yii::$app->request->get('q', ''));

        $query = Post::find()
            ->alias('p')
            ->where(['p.published' => 1])
            ->joinWith(['category c']); 

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
        ]);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete', 'admin-index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
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
        if (($m = Post::findOne($id)) !== null) {
            return $m;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
