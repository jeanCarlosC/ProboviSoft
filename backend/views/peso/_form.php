<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use backend\models\Tipo;

/* @var $this yii\web\View */
/* @var $model backend\models\Peso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="peso-form">

        <?php $form = ActiveForm::begin([
        'enableAjaxValidation'=>true,
        'id' => 'form',
        'method'=>'post',
        'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <div class="row col-md-12">

        <div class="col-md-6">
        <?php
        $query = new Query;
        $query->select(["LPAD(identificacion, 6, '0') as identificacion"])
        ->from('animal');
        $command = $query->createCommand();
        $data = $command->queryAll();

        echo $form->field($model, 'animal_identificacion')->widget(TypeaheadBasic::classname(), [
        'data' =>  ArrayHelper::map($data,'identificacion','identificacion'),
        'options' => ['placeholder' => 'Seleccione el animal ...'],
        'pluginOptions' => ['highlight'=>true],
        ]);
        ?>
        </div>

        <div class="col-md-6">
        <?= $form->field($model, 'fecha')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'seleccione la fecha ...'],
        'pluginOptions' => [
        'format' => 'yyyy-m-d',
        'autoclose'=>true
        ]
        ]);
        ?>
        </div>

        </div>

        <div class="row col-md-12">

        <div class="col-md-6">
        <?php echo $form->field($model, 'peso')->textInput() ?>
        </div>

        <div class="col-md-6">
        <?= $form->field($model, 'tipo_id_tipo')->dropDownList([ 'PN' => 'Nacimiento (PN)', 'PD' => 'Destete (PD)','PO' => 'Otro (PO)', ], ['prompt' => 'Seleccione el tipo..'])?>
        </div>
        </div>

        <div class="form-group">
            <?php echo Html::submitButton($model->isNewRecord ? 'Guardar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success'])?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
