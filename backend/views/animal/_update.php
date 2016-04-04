<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use kartik\date\DatePicker;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use kartik\tabs\TabsX;
use backend\models\Peso;
use backend\models\Raza;
use backend\models\RazaAnimal;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Animal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="animal-form">

 <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'id' => 'dynamic-form',
    'method'=>'post',
    'options' => ['enctype' => 'multipart/form-data'],
 ]); ?>

<div class='col-md-12'>
<?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
'options' => ['accept' => 'image/*'],
]); ?>
</div>
<div><h4 class="titulo">Datos Basicos</h4></div>
<div class='row col-md-12'>
<div class="col-md-3">
    <?= $form->field($model, 'identificacion')->textInput() ?>
</div>

<div class="col-md-3">
    <?= $form->field($model, 'sexo')->dropDownList([ 'M' => 'Macho (M)', 'H' => 'Hembra (H)', ], ['prompt' => 'Seleccione el sexo..'])?>
</div> 

<div class="col-md-3">
<?= $form->field($model, 'fecha_nacimiento')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'seleccione la fecha ...'],
    'pluginOptions' => [
        'format' => 'yyyy-m-d',
        'autoclose'=>true
    ]
    ]);
    ?>
</div>

<div class='col-md-3'><?php echo $form->field($model_peso, 'peso')->textInput() ?></div>
</div>
<div class='row col-md-12'>
<div class="col-md-3">
    <?= $form->field($model, 'nuemro_arete')->textInput() ?>
</div>

<div class='col-md-3'><?php echo $form->field($model, 'nombre')->textInput() ?></div>

<div class="col-md-3">
    <?= $form->field($model, 'madre')->textInput() ?>
</div>

<div class="col-md-3">
    <?= $form->field($model, 'padre')->textInput() ?>
</div>
</div>
        <div class='row col-md-12'>
        <div class="col-md-12">
        <div class="panel panel-default">
        <div class="panel-heading"><h4>Raza</h4></div>
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 3, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $model_raza[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    /*'raza_id_raza',*/
                    'porcentaje',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($model_raza as $i => $model_raza1): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">Razas</h3>
                        <div class="pull-right">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $model_raza1->isNewRecord) {
                                echo Html::activeHiddenInput($model_raza1, "[{$i}]raza_id_raza");
                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-6">
                            <?php
                                echo $form->field($model_raza1, "[{$i}]raza_id_raza")->widget(TypeaheadBasic::classname(), [
                                'data' =>  ArrayHelper::map(Raza::find()->all(),'id_raza','id_raza'),
                                'options' => ['placeholder' => 'Seleccione la raza ...'],
                                'pluginOptions' => ['highlight'=>true],
                                ]);
                                ?>
                               
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model_raza1, "[{$i}]porcentaje")->textInput(['maxlength' => true]) ?>
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
        </div>
    
    </div>
    </div>
    


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php

$js = <<<JS
// get the form id and set the event
$('form#dynamic-form').on('beforeSubmit', function(e) {
   var \$form = $(this);

var porcent1=0;
if (typeof $("#razaanimal-0-porcentaje") != 'undefined') 
{
porcent1=parseInt($("#razaanimal-0-porcentaje").val());
}

var porcent2=0;
if (typeof $("#razaanimal-1-porcentaje") != 'undefined') 
{
    porcent2=parseInt($("#razaanimal-1-porcentaje").val());
}

var porcent3=0;
if (typeof $("#razaanimal-2-porcentaje") != 'undefined') 
{
    porcent3=parseInt($("#razaanimal-2-porcentaje").val());
}
   
   
   if (isNaN( porcent1 )) porcent1 = 0;
   if (isNaN( porcent2 )) porcent2 = 0;
   if (isNaN( porcent3 )) porcent3 = 0;
   var suma = porcent1+porcent2+porcent3;

console.log(suma);
   if(suma!==100)
   {
    alert('Los porcentajes de las razas deben sumar 100%');
    return false;
   }
   else
   {
    return true;
   }
   

});
JS;
 
$this->registerJs($js);
?>