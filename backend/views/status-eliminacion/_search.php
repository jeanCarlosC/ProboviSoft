<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\StatusEliminacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="status-eliminacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'status_id_status') ?>

    <?= $form->field($model, 'animal_identificacion') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'causa') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
