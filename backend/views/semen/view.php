<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Semen */

$this->title = 'Toro: '.$model->identificacion;

?>
<div class="semen-view">

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
        <?= Html::a('Actualizar', ['update', 'id' => $model->identificacion], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->identificacion], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php echo Button::Widget(['label'=>'Cambiar Imagen','options'=>['class' => 'btn btn-primary'],'id'=>'modalButton',]);?>
    </p>


            <?php 
            Modal::begin([
            'header'=> '<p class="titulomodal">Cambiar Imagen</p>',
            'id'=> 'modal',
            'size'=> 'modal-md',

            ]);
            ?>

            <div class="imagen-form">

            <?php $form = ActiveForm::begin([
            'id' => 'form',
            'method'=>'post',
            'action'=>['imagen','id'=>$model->identificacion],
            'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>
            <div class="row">
            <div class='col-md-12'>
            <?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            ]); ?>
            </div>

            <div class="form-group">
            <?= Html::submitButton('Aceptar', ['class' =>'btn btn-success']) ?>
            </div>

            </div>

            <?php ActiveForm::end(); ?>
            </div>

            <?php
            Modal::end();   

            ?>  



<div class="row perfil">
<div class="row">

    <div class='col-md-4'>
    <?php if(!empty($model->imagen)):?>
    <a class="perfilanimalv"><img src="../../uploads/<?php echo $model->imagen; ?>"></a>
    <?php endif; ?>

    <?php if(empty($model->imagen)):?>
    <a class="perfilanimalv"><img src="../../uploads/notoro.jpg"></a>
    <?php endif; ?>
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>
    </div>


<br><br>
<div class="col-md-4">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'raza',
            'termo_identificacion',
            'canastilla_identificacion',
            'pajuelas',

        ],
    ]) ?>
    </div>
    <div class="col-md-4">
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
            'attribute' => 'hijos',
            'value' => $model->hijoos,

            ],

            [
            'attribute' => 'hembras',
            'value' => $model->hembraas,

            ],

            [
            'attribute' => 'machos',
            'value' => $model->machoos,

            ],

        ],
    ]) ?>
    </div>
    </div>
</div>

<div class="row perfil">

<h1 class="titulo">Registros de Semen</h1>

<div class="row col-md-12">

        <div class="col-md-6">
        <h4 class="">Entrada</h4>
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
        'dataProvider' => $entrada,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'fecha',
        'pajuelas_entrada',
        'descripcion',
        ],
        ]); ?>
        <?php Pjax::end(); ?>
        </div>

        <div class="col-md-6">
        <h4 class="">Salida</h4>
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
        'dataProvider' => $salida,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'fecha',
        'pajuelas_salida',
        'descripcion',
        ],
        ]); ?>
        <?php Pjax::end(); ?>
        </div>

</div>

</div>

</div>

<?php
$script = <<< JS
$(function(){
    $('#modalButton').click(function(){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
});
JS;
$this->registerJs($script);
?>

