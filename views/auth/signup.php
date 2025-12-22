<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Sign Up';
?>

<div class="auth-box">
    <h2>Sign Up</h2>

    <?php
    // form start
    $form = ActiveForm::begin();
    ?>
    <?=
    // user name input
    $form->field($model, 'name')->textInput()
    ?>
    <?=
    // user email input
    $form->field($model, 'email')->textInput()
    ?>
    <?=
    // password input
    $form->field($model, 'password')->passwordInput()
    ?>
    <?=
    // submit button 
    Html::submitButton('Sign Up', ['class' => 'btn btn-success'])
    ?>
    <?php
    // form end
    ActiveForm::end();
    ?>

    <p>
        Have account?
        <a href="<?= \yii\helpers\Url::to(['auth/login']) ?>">Log In</a>
    </p>

</div>