<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Sign Up';
$this->registerCssFile('@web/css/auth.css'); // CSS
?>

<div class="auth-page">
    <div class="auth-box">

        <h2>Sign Up</h2>
        <p class="auth-subtitle">Create an account to comment and save posts.</p>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')
            ->textInput(['placeholder' => 'Name']) ?>

        <?= $form->field($model, 'email')
            ->textInput(['placeholder' => 'Email']) ?>

        <?= $form->field($model, 'password')
            ->passwordInput(['placeholder' => 'Password']) ?>

        <div class="auth-actions">
            <?= Html::submitButton('Sign Up', ['class' => 'auth-btn']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="auth-divider"></div>

        <p class="auth-links">
            Already have an account?
            <a href="<?= \yii\helpers\Url::to(['auth/login']) ?>">Log In</a>
        </p>

    </div>
</div>
