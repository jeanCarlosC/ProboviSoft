<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use backend\models\servicio;

/* @var $this yii\web\View */
/* @var $model backend\models\Diagnostico */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="diagnostico-form">

    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'id' => 'form',
    'method'=>'post',
    'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class="row col-md-12">
        <div class='col-md-6'>
        <?php
        $query2 = new Query;
        $query2->select(["LPAD(identificacion, 6, '0') as identificacion"])
        ->from('animal')
        ->join('JOIN','servicio','animal.identificacion=servicio.animal_identificacion')
        ->orderBy('servicio.fecha');
        $command = $query2->createCommand();
        $data2 = $command->queryAll();

        echo $form->field($model, 'animal_identificacion')->widget(TypeaheadBasic::classname(), [
        'data' =>  ArrayHelper::map($data2,'identificacion','identificacion'),
        'options' => ['placeholder' => 'Seleccione el animal ...'],
        'pluginOptions' => ['highlight'=>true],
        ]);
        ?>
        </div>
        <div class='col-md-6'>
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
    <div class="col-md-12">
    <?= $form->field($model, 'diagnostico_prenez')->dropDownList([ 'P' => 'Preñada', 'V' => 'Vacia', ], ['prompt' => 'seleccione un diagnostico..']) ?>
    </div>
    </div>

    <div class="row col-md-12">
    <div class='preñada-wrapper' <?= $model->diagnostico_prenez == '' ? 'style="display:none"' : ''?>>
        <div class='col-md-12'>
        <?= $form->field($model, 'dias_gestacion')->textInput() ?>
        </div>
    </div>
    </div>

    <div class="row col-md-12">

        <div class='vacia-wrapper' <?= $model->diagnostico_prenez == '' ? 'style="display:none"' : ''?>>

            <div class='col-md-6'>
            <?= $form->field($model, 'ovario_izq')->textInput(['maxlength' => true]) ?>
            </div>
            <div class='col-md-6'>
            <?= $form->field($model, 'ovario_der')->textInput(['maxlength' => true]) ?>
            </div>


            <div class='col-md-6'>
            <?= $form->field($model, 'utero')->textInput(['maxlength' => true]) ?>
            </div>
            <div class='col-md-6'>
            <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    
    </div>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

$js = <<<JS

$(document).ready(function(){

$('#diagnostico-diagnostico_prenez').change(function () {

selection2 = this.value;

if(selection2 == 'P'){
$('.vacia-wrapper').hide();
$('.preñada-wrapper').fadeIn("slow");
document.getElementById("diagnostico-ovario_izq").value = "";
document.getElementById("diagnostico-ovario_der").value = "";
document.getElementById("diagnostico-utero").value = "";
document.getElementById("diagnostico-observacion").value = "";
}
else
{
    $('.vacia-wrapper').fadeIn("slow");
    $('.preñada-wrapper').hide();
document.getElementById("diagnostico-dias_gestacion").value = "";
}

});

});
JS;
 
$this->registerJs($js);
?>