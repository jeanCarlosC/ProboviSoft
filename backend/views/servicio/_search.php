<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ServicioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servicio-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_servicio') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'toro') ?>

    <?= $form->field($model, 'tipo_servicio') ?>

    <?= $form->field($model, 'inseminador') ?>

    <?php // echo $form->field($model, 'animal_identificacion') ?>

    <?php // echo $form->field($model, 'semen_identificacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
