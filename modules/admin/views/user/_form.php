<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

    <!-- Використовуйте поле password замість password_hash -->
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => 'Введіть пароль']) ?>

    <?= $form->field($model, 'image')->fileInput() ?>

    <!-- Поле auth_key приховано, оскільки генерується автоматично -->
    <?= $form->field($model, 'auth_key')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
