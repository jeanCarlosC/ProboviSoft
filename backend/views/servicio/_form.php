<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Servicio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servicio-form">

    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'id' => 'form_servicio',
    'method'=>'post',
    'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>
    
    <div class=" row col-md-12">
    <div class="col-md-4">
    <?php /* Yii::$app->session->getFlash('error'); */ ?>
    <?php
    $query2 = new Query;
    $query2->select(["LPAD(identificacion, 6, '0') as identificacion"])
    ->from('animal')
    ->where("sexo='H'");
    $command = $query2->createCommand();
    $data2 = $command->queryAll();
    /*      echo "<pre>";
    print_r($data);
    echo "</pre>";
    yii::$app->end();*/

    echo $form->field($model, 'animal_identificacion')->widget(TypeaheadBasic::classname(), [
    'data' =>  ArrayHelper::map($data2,'identificacion','identificacion'),
    'dataset' => ['limit' => 5],
    'options' => ['placeholder' => 'Seleccione el animal ...'],
    'pluginOptions' => ['highlight'=>true],
    ]);
    ?>
    </div>

    <div class="col-md-4">
    <?= $form->field($model, 'fecha')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'seleccione la fecha ...'],
    'pluginOptions' => [
    'format' => 'yyyy-m-d',
    'autoclose'=>true
    ]
    ]);
    ?>
    </div>

    <div class="col-md-4">
    <?= $form->field($model, 'tipo_servicio')->dropDownList([ 'IA' => 'Inseminacion Artificial', 'MN' => 'Monta Natural', ], ['prompt' => 'Seleccione el tipo de servicio...']) ?>
    </div>
   </div>
      
    <div class="row col-md-12">
    <div class="col-md-12">
    <div class='Monta-Wrapper' <?= $model->tipo_servicio == 'IA' ? 'style="display:none"' : ''?> >       
    <?php
    $query = new Query;
    $query->select(["identificacion","LPAD(animal.identificacion, 6, '0') as animal"])
    ->from('animal')
    ->where("animal.sexo='M'");
    $command = $query->createCommand();
    $data = $command->queryAll();
    /*      echo "<pre>";
    print_r($data);
    echo "</pre>";
    yii::$app->end();
    */
    echo $form->field($model, 'toro')->widget(TypeaheadBasic::classname(), [
    'data' =>  ArrayHelper::map($data,'identificacion','animal'),
    'options' => ['placeholder' => 'Seleccione el animal ...'],
    'pluginOptions' => ['highlight'=>true],
    ]);
    ?>
    </div>
    </div>
    </div>

    <!-- lllllllllllllllllllllllllllllllllllllllllll Inseminacion llllllllllllllllllllllllllllllllllllllllllllllllllllll -->
    
    <div class='Inseminacion-Wrapper' <?= $model->tipo_servicio == 'MN' ? 'style="display:none"' : ''?> >
    <div class=" row col-md-12">
    <div class="col-md-6">
    <?php
    $query = new Query;
    $query->select(["identificacion","LPAD(semen.identificacion, 6, '0') as animal"])
    ->from('semen');
    $command = $query->createCommand();
    $data = $command->queryAll();
    /*      echo "<pre>";
    print_r($data);
    echo "</pre>";
    yii::$app->end();
    */

    echo $form->field($model, 'semen_identificacion')->widget(TypeaheadBasic::classname(), [
    'data' =>  ArrayHelper::map($data,'identificacion','animal'),
    'options' => ['placeholder' => 'Seleccione el animal ...'],
    'pluginOptions' => ['highlight'=>true],
    ]);
    ?>
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'inseminador')->textInput(['maxlength' => true]) ?>
    </div>
    </div>
    </div>

<!-- llllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllll-->
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>


<?php

$js = <<<JS

$(document).ready(function(){
$('.Inseminacion-Wrapper').hide();
$('.Monta-Wrapper').hide();
$('#servicio-tipo_servicio').change(function () {

selection2 = this.value;

if(selection2 == 'MN'){
$('.Inseminacion-Wrapper').hide();
$('.Monta-Wrapper').fadeIn("slow");
document.getElementById("servicio-inseminador").value = "";
document.getElementById("servicio-semen_identificacion").value = "";
}
else
{
    $('.Monta-Wrapper').hide();
    $('.Inseminacion-Wrapper').fadeIn("slow");
    document.getElementById("servicio-toro").value = "";
}

});

});
JS;
 
$this->registerJs($js);
?>

