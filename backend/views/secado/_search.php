<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SecadoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="secado-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_secado') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'animal_identificacion') ?>

    <?= $form->field($model, 'parto_id_parto') ?>

    <?= $form->field($model, 'creado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
