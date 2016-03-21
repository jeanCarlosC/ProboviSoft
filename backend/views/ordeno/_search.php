<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrdenoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ordeno-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_ordeno') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'pesaje') ?>

    <?= $form->field($model, 'id_potrero') ?>

    <?= $form->field($model, 'animal_identificacion') ?>

    <?php // echo $form->field($model, 'turno') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
