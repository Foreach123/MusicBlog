<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Log In';
$this->registerCssFile('@web/css/auth.css'); // CSS
?>

<div class="auth-page">
    <div class="auth-box">

        <h2>Log In</h2>
        <p class="auth-subtitle">Welcome back. Please enter your details.</p>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email')
            ->textInput(['placeholder' => 'Email']) ?>

        <?= $form->field($model, 'password')
            ->passwordInput(['placeholder' => 'Password']) ?>

        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="auth-actions">
            <?= Html::submitButton('Log In', ['class' => 'auth-btn']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="auth-divider"></div>

        <p class="auth-links">
            Don’t have an account?
            <a href="<?= \yii\helpers\Url::to(['auth/signup']) ?>">Sign Up</a>
        </p>

    </div>
</div>
