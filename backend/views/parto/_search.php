<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PartoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_parto') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'cod_becerro') ?>

    <?= $form->field($model, 'peso_becerro') ?>

    <?= $form->field($model, 'sexo_becerro') ?>

    <?php // echo $form->field($model, 'servicio_id_servicio') ?>

    <?php // echo $form->field($model, 'animal_identificacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
