<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AbortoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="aborto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_aborto') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'observacion') ?>

    <?= $form->field($model, 'servicio_id_servicio') ?>

    <?= $form->field($model, 'animal_identificacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
