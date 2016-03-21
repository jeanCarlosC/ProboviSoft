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

    <div class="row col-md-12">

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

    </div>

        
        <div class="col-md-12">
        <?= $form->field($model, 'tipo_parto')->radioList(['1'=>'Parto simple','2'=>'Parto multiple']); ?>
        </div>
        

    <div class="row col-md-12">
    <div class="col-md-4">
    <?= $form->field($model, 'cod_becerro')->textInput() ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'peso_becerro')->textInput() ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'sexo_becerro')->dropDownList([ 'M' => 'Macho (M)', 'H' => 'Hemabra (H)', ], ['prompt' => 'Seleccione el sexo..'])?>
    </div>

    </div>

    <div class='Wrapper-multiple' <?= $model->tipo_parto == 2 ? '' : 'style="display:none"'?> >
    <div class="col-md-12" style="font-size: 18px" align="center"><b>2do Becerro</b></div>

    <div class="row col-md-12">
    <div class="col-md-4">
    <?= $form->field($model, 'cod_becerro2')->textInput() ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'peso_becerro2')->textInput() ?>
    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'sexo_becerro2')->dropDownList([ 'M' => 'Macho (M)', 'H' => 'Hemabra (H)', ], ['prompt' => 'Seleccione el sexo..'])?>
    </div> 
    </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

$js = <<<JS

$(document).ready(function(){

    $('#parto-tipo_parto input').click(function(e){

if(this.value==1){
    $('.Wrapper-multiple').fadeOut("slow");
    document.getElementById("parto-cod_becerro2").value = "";
    document.getElementById("parto-sexo_becerro2").value = "";
    document.getElementById("parto-peso_becerro2").value = "";

console.log('parto simple');    
}
else{
    $('.Wrapper-multiple').fadeIn("slow");
    
console.log('parto multiple');
}


});

});
JS;
 
$this->registerJs($js);
?>