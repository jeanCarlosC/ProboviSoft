<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ServicioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicio-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /*Html::a('Registrar Servicio', ['create'], ['class' => 'btn btn-primary'])*/ ?>
        <?php echo Button::Widget(['label'=>'Registrar Servicios','options'=>['class' => 'btn btn-primary'],'id'=>'modalButton']);?>
    </p>

            <?php 
            Modal::begin([
            'header'=> '<p class="titulomodal">Registrar Servicios</p>',
            'id'=> 'modal',
            'size'=> 'modal-md',

            ]);
            ?>

            <div class="index-modal">
            <?php $form = ActiveForm::begin([
            'enableAjaxValidation'=>true,
            'id' => 'form',
            'method'=>'post',
            'action'=>['fecha'],
            'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>
            <div class="row">
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

            <div class='col-md-12'>
                <?= $form->field($model, "tipo_servicio")->dropDownList([ 'IA' => 'Inseminacion Artificial', 'MN' => 'Monta Natural', ], ['prompt' => 'Seleccione el tipo de servicio...']);?>
            </div>

            <div class="col-md-12"><h4 align="center">Seleccione las hembras a servir</h4></div>

            <div class='col-md-12'>
            <?=GridView::widget([
            'dataProvider' => $animales,
            'id' => 'grid',
            'pjax'=>true,
        'pjaxSettings'=>
        [
           'neverTimeout'=>true,
        ],
        'export'=>false,
            'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'identificacion',
            ],
            ]); ?>
            </div>

            
            <div class="col-md-12">
            <?= Html::submitButton('Aceptar', ['class' =>'btn btn-primary','id'=>'bm']) ?>
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
        'pjaxSettings'=>
        [
           'neverTimeout'=>true,
        ],
        'export'=>false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'animal_identificacion',
           /* str_pad('animal_identificacion', 6, 0, STR_PAD_LEFT),*/
            'fecha',
            'tipo_servicio',
            'toro_otro',
            'semen',
            'inseminador',

                        [
                'class' => 'yii\grid\ActionColumn',

                'header'=>'Opciones',

                'headerOptions' => ['width' => '20'],

                'template' => '{view}{update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) 
                {

                    if ($action === 'view') 
                    {
                    return \Yii::$app->getUrlManager()->createUrl(['servicio/view', 'id' => $model['id_servicio']]);
                    }
                    else if ($action === 'update') {
                    // return \Yii::$app->getUrlManager()->createUrl(['nomina/update', 'id' => $model['idnomina']]);
                    return Url::toRoute(['servicio/update', 'id' => $model['id_servicio']]);
                    } else if ($action === 'delete') {
                    return \Yii::$app->getUrlManager()->createUrl(['servicio/delete', 'id' => $model['id_servicio']]);
                    }
                }

            ],
        ],
    ]); ?>


</div>
<?php
$script = <<< JS
$(function(){
    $('#modalButton').click(function(){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });

    $('#bm').click(function(e){
       /* e.preventDefault();*/
    var keys = $('#grid').yiiGridView('getSelectedRows');
    
    
    if(keys.length==0){
    alert('Debe seleccionar al menos 1 vaca/novilla para continuar');
    console.log(keys);
    return false; 
    }
    
    /*$.post("servicio/fecha",keys);*/

    });
});
JS;
$this->registerJs($script);
?>
