<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\Secado */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="secado-form">

    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'id'=>'form',
    'method'=>'post',
    'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

        <div class="row col-md-12">
        <div class='col-md-6'>
        <?php
        $query2 = new Query;
        $query2->select(["LPAD(identificacion, 6, '0') as identificacion"])
        ->from('animal')
        ->join('JOIN','status_animal','animal.identificacion = status_animal.animal_identificacion')
        ->where(['status_animal.status_id_status'=>'O']);
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


    <div class="form-group">
        <?= Html::submitButton('Actualizar', ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

$js = <<<JS

$(document).ready(function(){
if(typeof $("#secado-animal_identificacion") == 'undefined'){
document.getElementById("secado-animal_identificacion").disabled=false;
}
else
{
document.getElementById("secado-animal_identificacion").disabled=true;    
}
});

$('form#form').on('beforeSubmit', function(e) {


    var r = confirm("¿ Esta Seguro que deseea actualizar el secado ?");
    if (r == true) {
        return true;
    } else {
        return false;
    }
});

JS;
 
$this->registerJs($js);
?>