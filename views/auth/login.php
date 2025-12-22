<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Log In';
?>

<div class="auth-box">
    <h2>Log In</h2>

    <?php 
    // login form start
    $form = ActiveForm::begin(); 
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
        // checkbox
        $form->field($model, 'rememberMe')->checkbox()
    ?>
    <?=
        // submit button
        Html::submitButton('Log In', ['class' => 'btn btn-primary'])
    ?>
    <?php 
    // login form end
    ActiveForm::end(); 
    ?>

    <p>
        Dont have account?
        <a href="<?= \yii\helpers\Url::to(['auth/signup']) ?>">Sign Up</a>
    </p>

</div>
