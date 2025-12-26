<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SignupForm;

class AuthController extends Controller
{
    // use main layout
    public $layout = 'auth';
    

    // login action
    public function actionLogin()
    {
        $model = new LoginForm();

        // if submitted form AND success go to blog
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['post/index']);
        }

        // render login page
        return $this->render('login', [
            'model' => $model
        ]);
    }

    // signup action
    public function actionSignup()
    {
        $model = new SignupForm();

        // if signup go to login page
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect(['auth/login']);
        }

        // render signup page
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    // logout
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['auth/login']);
    }
}
