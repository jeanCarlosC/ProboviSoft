<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PesoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="peso-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_peso') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'peso') ?>

    <?= $form->field($model, 'animal_identificacion') ?>

    <?= $form->field($model, 'tipo_id_tipo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
