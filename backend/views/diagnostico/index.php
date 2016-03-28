<?php

use yii\helpers\Html;
/*use yii\grid\GridView;*/
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DiagnosticoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Diagnosticos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="diagnostico-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* Html::a('Registrar Diagnostico', ['create'], ['class' => 'btn btn-primary'])*/ ?>
         <?php echo Button::Widget(['label'=>'Registrar Diagnosticos','options'=>['class' => 'btn btn-primary'],'id'=>'modalButton']);?>
    </p>




            <?php 
            Modal::begin([
            'header'=> '<p class="titulomodal">Registrar Diagnosticos</p>',
            'id'=> 'modal',
            'size'=> 'modal-md',

            ]);
            ?>

            <div class="index-modal">
            
            <div class="row">
            <div class="col-md-12"><h4 align="center">Seleccione las hembras a diagnosticar</h4></div>


            <?php $form = ActiveForm::begin([
            'enableAjaxValidation'=>true,
            'id' => 'form',
            'method'=>'post',
            'action'=>['fecha'],
            'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

            <div class='col-md-12'>
            <?=GridView::widget([
            'dataProvider' => $dataProvider2,
            /*'filterModel'=> $searchModel2,*/
            
            'export'=>false,
            'pjax'=>true,
            'pjaxSettings' => [
            'options' => [
            'enablePushState' => false,
            ],
            ],
            'id' => 'grid',
            'columns' => [
            [
            'class' => 'yii\grid\CheckboxColumn',
            'header' => Html::checkBox('selection_all', false, [
            'class' => 'select-on-check-all',
            'label' => 'Todos',
            ]),
            ],
            'identificacion',
            ],
            ]); ?>
            </div>

            <div class='col-md-12'>
                <?= $form->field($model, 'fecha')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'seleccione la fecha ...'],
                'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'autoclose'=>true
                ]
                ]);
                ?>
            </div>

            <div class="col-md-12">
            <?= $form->field($model, 'diagnostico_prenez')->dropDownList([ 'P' => 'Preñada', 'V' => 'Vacia', ], ['prompt' => 'seleccione un diagnostico..']) ?>
            </div>
           

            
            <div class="col-md-12">
            <?= Html::submitButton('Aceptar', ['class' =>'btn btn-primary','id'=>'bm']) ?>
            </div>
            <?php $form = ActiveForm::end(); ?> 
            </div>            
            </div>

            

            <?php 
            Modal::end();
            ?>






    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export'=>false,
        'pjax'=>true,
        'pjaxSettings' => [
        'options' => [
        'enablePushState' => false,
        ],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'animal_identificacion',
            'diagnostico_prenez',
            'fecha',
            'parto_esperado',
        ],
    ]); ?>

</div>

<?php
$script = <<< JS
$(function(){
    $('#modalButton').click(function(e){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });

    $('#bm').click(function(){
    var keys = $('#grid').yiiGridView('getSelectedRows');
    $.post("diagnostico/fecha",keys);
    if(keys.length==0){
    alert('Debe seleccionar al menos 1 vaca/novilla para continuar');
    console.log(keys);
    return false; 
    }
    else
    {
        
    }
    
    /*$.post("servicio/fecha",keys);*/

    });
});
JS;
$this->registerJs($script);
?>