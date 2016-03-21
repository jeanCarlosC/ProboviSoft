<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DiagnosticoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="diagnostico-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_diagnostico') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'diagnostico_prenez') ?>

    <?= $form->field($model, 'dias_gestacion') ?>

    <?= $form->field($model, 'ovario_izq') ?>

    <?php // echo $form->field($model, 'ovario_der') ?>

    <?php // echo $form->field($model, 'utero') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'servicio_id_servicio') ?>

    <?php // echo $form->field($model, 'animal_identificacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
