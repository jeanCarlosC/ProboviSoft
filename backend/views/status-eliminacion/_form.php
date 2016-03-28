<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\StatusEliminacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="status-eliminacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row col-md-12">
    <div class='col-md-4'>
	<?php
	echo $form->field($model, 'animal_identificacion')->widget(TypeaheadBasic::classname(), [
	'data' =>  ArrayHelper::map($data,'identificacion','identificacion'),
	'options' => ['placeholder' => 'Seleccione el animal ...'],
	'pluginOptions' => ['highlight'=>true],
	]);
	?>
	</div>

    <div class="col-md-4">
    <?= $form->field($model, 'causa')->dropDownList([ 'M' => 'Muerte (M)', 'V' => 'Venta (V)', ], ['prompt' => 'Seleccione la causa..'])?>
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
</div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Registar' : 'Editar', ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
