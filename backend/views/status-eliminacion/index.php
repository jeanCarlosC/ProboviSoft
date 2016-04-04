<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\StatusEliminacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Eliminar Animal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-eliminacion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <?php echo Button::Widget(['label'=>'Muerte','options'=>['class' => 'btn btn-primary'],'id'=>'modalMuerte']);?>
    <?php echo Button::Widget(['label'=>'Venta','options'=>['class' => 'btn btn-primary'],'id'=>'modalVenta']);?>
    </p>

        <?php 
        Modal::begin([
        'header'=> '<p class="titulomodal">Registrar Muerte</p>',
        'id'=> 'modal_muerte',
        'size'=> 'modal-md',

        ]);
        ?>

        <div class="index-modal1">
        <?php $form = ActiveForm::begin([
        'enableAjaxValidation'=>true,
        'id' => 'form1',
        'method'=>'post',
        'action'=>['muerte'],
        'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>
        <div class="row col-md-12">

        <div class='col-md-6'>
        <?php
        echo $form->field($model, 'animal_identificacion')->widget(TypeaheadBasic::classname(), [
        'data' =>  ArrayHelper::map($data,'identificacion','identificacion'),
        'options' => ['placeholder' => 'Seleccione el animal ...'],
        'pluginOptions' => ['highlight'=>true],
        ]);
        ?>
        </div>
        <div class='col-md-6'>
        <?php


        echo '<div class="input-group drp-container col-md-12">';
        echo $form->field($model, 'fecha')->widget(DateRangePicker::classname(), [
        'value'=>'01/12/2015',
        'useWithAddon'=>true,
        'pluginOptions'=>[
        'allowClear' => true,
        'singleDatePicker'=>true,
        'showDropdowns'=>true
        ]
        ]);
        echo '</div>';
        
        ?>
        <br>
        </div>

        </div>

        <div class=' row col-md-12'>
        <div class='col-md-12'>
        <?= $form->field($model, 'descripcion' )->textarea(['rows' => 4]) ?>
        </div>
        </div>
        <div class="form-group">
        <?= Html::submitButton('Aceptar', ['class' =>'btn btn-primary','id'=>'bm1']) ?>
        </div>


        <?php $form = ActiveForm::end(); ?> 
        </div>

            

        <?php 
        Modal::end();
        ?>

        <?php 
        Modal::begin([
        'header'=> '<p class="titulomodal">Registrar Venta</p>',
        'id'=> 'modal_venta',
        'size'=> 'modal-md',

        ]);
        ?>

        <div class="index-modal">
        <?php $form = ActiveForm::begin([
        'enableAjaxValidation'=>true,
        'id' => 'form',
        'method'=>'post',
        'action'=>['venta'],
        'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>
        <div class="row">

        <div class='col-md-12'>
        <?=
        $form->field($model, 'animales')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($animales, 'identificacion', 'identificacion'),
        'maintainOrder' => true,
        'toggleAllSettings' => [
        'selectLabel' => '<i class="glyphicon glyphicon-ok-circle" style="font-size:15px;"></i> <h style="font-size:15px;">Seleccionar todos</h>',
        'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle" style="font-size:15px;"></i> <h style="font-size:15px;">Eliminar todos</h>',
        'selectOptions' => ['class' => 'text-success'],
        'unselectOptions' => ['class' => 'text-danger'],
        ],
        'options' => ['placeholder' => 'Seleccione Animales ...', 'multiple' => true],
        'pluginOptions' => [
        'allowClear' => false,

        ],
        ]);
        ?>
        </div>

        <div class='col-md-12'>
        <?php

        echo '<div class="input-group drp-container col-md-12">';
        echo $form->field($model, 'fecha1')->widget(DateRangePicker::classname(), [
        'value'=>'01/12/2015',
        'useWithAddon'=>true,
        'pluginOptions'=>[
        'allowClear' => true,
        'singleDatePicker'=>true,
        'showDropdowns'=>true
        ]
        ]);
         echo '</div>';
        
        ?>
        <br>
        </div>


        <div class='col-md-12'>
        <?= $form->field($model, 'descripcion' )->textarea(['rows' => 4]) ?>
        </div>



        <div class="col-md-12">
        <?= Html::submitButton('Aceptar', ['class' =>'btn btn-primary','id'=>'bm2']) ?>
        </div>
        </div>

        <?php $form = ActiveForm::end(); ?> 
        </div>



        <?php 
        Modal::end();
        ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'export'=>false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'animal_identificacion',
            'causa',
            'status_id_status',
            'fecha',

                        [
                'class' => 'yii\grid\ActionColumn',

                'header'=>'Opciones',

                'headerOptions' => ['width' => '20'],

                'template' => '{view}{update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) 
                {

                    if ($action === 'view') 
                    {
                    return \Yii::$app->getUrlManager()->createUrl(['status-eliminacion/view', 'id' => $model['id']]);
                    }
                    else if ($action === 'update') {
                    // return \Yii::$app->getUrlManager()->createUrl(['nomina/update', 'id' => $model['idnomina']]);
                    return Url::toRoute(['status-eliminacion/update', 'id' => $model['id']]);
                    } 

                    if ($action === 'delete') 
                    {
                    return \Yii::$app->getUrlManager()->createUrl(['status-eliminacion/delete', 'id' => $model['id']]);
                    }
                }

            ],
        ],
    ]); ?>

</div>

<?php
$script = <<< JS
$(function(){

    $('#modalVenta').click(function(){
        $('#modal_venta').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });

    $('#modalMuerte').click(function(){
        $('#modal_muerte').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });


    $('#bm2').click(function(e){
        /*e.preventDefault();*/
    var keys = $('#grid').yiiGridView('getSelectedRows');
    console.log(keys);
    
    if(keys.length==0){
    alert('Debe seleccionar al menos 1 animal para continuar');
    console.log(keys);
    return false; 
    }
    
    /*$.post("servicio/fecha",keys);*/

    });
});
JS;
$this->registerJs($script);
?>
