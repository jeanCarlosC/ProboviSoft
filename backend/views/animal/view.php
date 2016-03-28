<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\bootstrap\Button;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Animal */

$this->title = $model->identificacion;
$this->params['breadcrumbs'][] = ['label' => 'Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$url = Url::toRoute(['animal/imagen', 'id' => $model->identificacion_otro]);
?>
<div class="animal-view">


    <div class="row">

    <p>
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
        <?= Html::a('Actualizar', ['update', 'id' => $model->identificacion], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->identificacion], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Estas seguro que desea eliminar este animal?',
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
</div>
    
    <div class="row perfil">

    <div class='col-md-4'>
    <?php if($model->imagen):?>
    <a class="perfilanimalv"><img src="../../uploads/<?php echo $model->imagen; ?>" alt=""></a>
    <?php endif; ?>

    <?php if(!($model->imagen) && $model->sexo =='H'):?>
    <a class="perfilanimalv"><img src="../../uploads/novaca.png" alt=""></a>
    <?php endif; ?>

    <?php if(!($model->imagen) && $model->sexo =='M'):?>
    <a class="perfilanimalv"><img src="../../uploads/notoro.jpg" alt=""></a>
    <?php endif; ?>
    <h1>Identificación: <?= Html::encode($this->title) ?></h1>
    </div>

<?php if($model->sexo=='H'):  ?>
    <br>
<div class='col-md-8'>
<?php
echo Tabs::widget([
    'items' => [
        [
            'label' => 'Datos Basicos',
            'content' => $this->render('basicos', [
            'model' => $model,
            ]),
            'active' => true
        ],
        [
            'label' => 'Datos reproductivos',
            'content' => $this->render('reproduccion', [
            'model_repro' => $model_repro,
            ]),
        ],

        [
            'label' => 'Pedigree',
            'content' => $this->render('pedigree', [
            'pedi'=>$pedi,
            ]),
        ],

    ],
]);
?>
</div>
<?php endif; ?>

<?php if($model->sexo=='M'):  ?>
    <br>
<div class='col-md-8'>
<?php
echo Tabs::widget([
    'items' => [
        [
            'label' => 'Datos Basicos',
            'content' => $this->render('basicos', [
            'model' => $model,
            ]),
            'active' => true
        ],

        [
            'label' => 'Datos reproductivos',
            'content' => $this->render('reproduccion', [
            'model' => $model,
            ]),
        ],

        [
            'label' => 'Pedigree',
            'content' => $this->render('pedigree', [
            'pedi'=>$pedi,
            ]),
        ],

    ],
]);
?>
</div>
<?php endif; ?>

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
