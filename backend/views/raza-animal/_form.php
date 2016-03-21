<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\RazaAnimal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="raza-animal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'raza_id_raza')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'porcentaje')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'animal_identificacion')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
