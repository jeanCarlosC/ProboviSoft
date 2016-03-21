<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SemenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="semen-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'identificacion') ?>

    <?= $form->field($model, 'madre') ?>

    <?= $form->field($model, 'padre') ?>

    <?= $form->field($model, 'pajuelas') ?>

    <?= $form->field($model, 'entrada') ?>

    <?php // echo $form->field($model, 'salida') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
