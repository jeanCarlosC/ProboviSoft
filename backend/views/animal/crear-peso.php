<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\Peso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="peso-form">

    <?php $form = ActiveForm::begin([
    	'id'=>'form-peso',
        'action'=>['peso'],

    ]); ?>

       <?= $form->field($model, 'fecha')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'seleccione la fecha ...'],
    'pluginOptions' => [
        'format' => 'yyyy-m-d',
        'autoclose'=>true
    ]
    ]);
    ?>

    <?= $form->field($model, 'peso')->textInput() ?>

    <?= $form->field($model, 'animal_identificacion')->textInput() ?>

    <?php 
    $query = new Query;
    $query->select(['*'])
    ->from('Tipo');

    $command = $query->createCommand();
    $data = $command->queryAll();

    echo $form->field($model, 'tipo_id_tipo')->widget(TypeaheadBasic::classname(), [
    'data' => ArrayHelper::map($data,'nombre','id_tipo'),
    'dataset' => ['limit' => 10],
    'options' => ['placeholder' => 'Seleccione el animal ...'],
    'pluginOptions' => ['highlight' => true, 'minLength' => 0],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
