<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Semen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agregar_pajuelas-form">

    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'method'=>'post',
    'action'=>['agregar'],
    'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class='row col-md-12'>
    <div class="col-md-4">
    <?php /* Yii::$app->session->getFlash('error'); */ ?>
    <?php
    $query2 = new Query;
    $query2->select(['*'])
    ->from('semen');
    $command = $query2->createCommand();
    $data2 = $command->queryAll();
    /*      echo "<pre>";
    print_r($data);
    echo "</pre>";
    yii::$app->end();*/

    echo $form->field($model, 'semen_identificacion')->widget(TypeaheadBasic::classname(), [
    'data' =>  ArrayHelper::map($data2,'identificacion','identificacion'),
    'dataset' => ['limit' => 5],
    'options' => ['placeholder' => 'Seleccione el Toro ...'],
    'pluginOptions' => ['highlight'=>true],
    ]);
    ?>
    </div>

    <div class='col-md-4'>   
   <?= $form->field($model, 'fecha')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'seleccione la fecha ...'],
    'pluginOptions' => [
        'format' => 'yyyy-m-d',
        'autoclose'=>true
    ]
    ]);
    ?>
    </div>

    <div class='col-md-4'>
    <?= $form->field($model, 'pajuelas_entrada')->textInput() ?>
    </div>
    </div>

    <div class='col-md-12'>
    <?= $form->field($model, 'descripcion' )->textarea(['rows' => 6]) ?>
    </div>

    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
