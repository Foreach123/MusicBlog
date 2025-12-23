<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;
use app\models\Post;
use Yii;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class PostController extends Controller
{
    public function actionIndex()
    {
        $q = trim(Yii::$app->request->get('q', ''));

        $query = Post::find()
            ->where(['posts.published' => 1])
            ->joinWith(['category', 'tags']);

        if ($q !== '') {
            $query->andWhere([
                'or',
                ['like', 'posts.title', $q],
                ['like', 'posts.content', $q],
                ['like', 'category.name', $q],
                ['like', 'tags.name', $q],
            ])->distinct();
        }

        $pagination = new Pagination([
            'defaultPageSize' => 6,
            'totalCount' => $query->count(),
        ]);

        $posts = $query
            ->orderBy(['published_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'posts' => $posts,
            'pagination' => $pagination,
            'q' => $q,
        ]);
    }
    
    protected function findModel($id)
    {
        if (($m = Post::findOne($id)) !== null) {
            return $m;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
