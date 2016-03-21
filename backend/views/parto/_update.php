<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Parto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parto-form">

    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'method'=>'post',
    'id'=>'form1',
    'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

        <div class='col-md-6'>
        <?php
        $query2 = new Query;
        $query2->select(["LPAD(identificacion, 6, '0') as identificacion"])
        ->from('animal')
        ->join('JOIN','diagnostico','animal.identificacion=diagnostico.animal_identificacion')
        ->where(['diagnostico.diagnostico_prenez'=>'P']);
        $command = $query2->createCommand();
        $data2 = $command->queryAll();
        /*      echo "<pre>";
        print_r($data);
        echo "</pre>";
        yii::$app->end();*/

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

    <div class="col-md-4">
    <?= $form->field($model, 'cod_becerro')->textInput() ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'peso_becerro')->textInput() ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'sexo_becerro')->dropDownList([ 'M' => 'Macho (M)', 'H' => 'Hemabra (H)', ], ['prompt' => 'Seleccione el sexo..'])?>
    </div> 


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

$js = <<<JS

$(document).ready(function(){
if(typeof $("#parto-animal_identificacion") == 'undefined'){
document.getElementById("parto-animal_identificacion").disabled=false;
}
else
{
document.getElementById("parto-animal_identificacion").disabled=true;    
}
});
JS;
 
$this->registerJs($js);
?>