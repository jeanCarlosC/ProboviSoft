<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\bootstrap\Button;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\db\Query;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrdenoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pesajes de leche';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ordeno-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <?php echo Button::Widget(['label'=>'Registrar Pesaje','options'=>['class' => 'btn btn-primary'],'id'=>'modalButton']);?>
    </p>
            <?php 
            Modal::begin([
            'header'=> '<p class="titulomodal">Registrar Pesaje</p>',
            'id'=> 'modal',
            'size'=> 'modal-md',

            ]);
            ?>

            <div class="imagen-form">

            <?php $form = ActiveForm::begin([
            'enableAjaxValidation'=>true,
            'id' => 'form',
            'method'=>'post',
            'action'=>['fecha'],
            'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>
            <!-- <div class="row perfil"> -->
            
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
              <?php 
    $query2 = new Query;
    $query2->select(['*'])
    ->from('potrero');
    $command = $query2->createCommand();
    $data2 = $command->queryAll();
    echo $form->field($model, 'id_potrero')->widget(TypeaheadBasic::classname(), [
    'data' =>  ArrayHelper::map($data2,'id_potrero','id_potrero'),
    'options' => ['placeholder' => 'Seleccione el animal ...'],
    'pluginOptions' => ['highlight'=>true],
    ]);
    ?>

            </div>

            <div class="form-group">
            <?= Html::submitButton('Aceptar', ['class' =>'btn btn-success']) ?>
            </div>

           <!--  </div> -->

            <?php ActiveForm::end(); ?>
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

            'identificacion',
            'fecha',
            'pesaje',
            'parto_id_parto',
            [

            'attribute' => 'dias',
            'value' => 'produccion_leche.dias',

            ],
            [

            'attribute' => 'pesaje_total',
            'value' => 'produccion_leche.total',

            ],
            [

            'attribute' => 'acumulado',
            'value' => 'produccion_leche.acumulado',

            ],
            
            // 'turno',

            [
                'class' => 'yii\grid\ActionColumn',

                'header'=>'Opciones',

                'headerOptions' => ['width' => '20'],

                'template' => '{view}{update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) 
                {

                    if ($action === 'view') 
                    {
                    return \Yii::$app->getUrlManager()->createUrl(['ordeno/view', 'id' => $model['id_ordeno']]);
                    }
                    else if ($action === 'update') {
                    // return \Yii::$app->getUrlManager()->createUrl(['nomina/update', 'id' => $model['idnomina']]);
                    return Url::toRoute(['ordeno/update', 'id' => $model['id_ordeno']]);
                    } else if ($action === 'delete') {
                    return \Yii::$app->getUrlManager()->createUrl(['ordeno/delete', 'id' => $model['id_ordeno']]);
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
});
JS;
$this->registerJs($script);
?>
