<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\StatusAnimal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="status-animal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status_id_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'animal_identificacion')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
