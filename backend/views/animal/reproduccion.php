<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm; 
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\bootstrap\Button;
?>

<?php if(!empty($model_repro)){ ?>
<div class="col-md-6">
        <br>
        <?php echo DetailView::widget([
        'model' => $model_repro,
        'attributes' => [

            [

            'attribute' => 'estado_reproductivo',
            'value' => $model_repro->estados,

            ],

            [

            'attribute' => 'partos',
            'value' => $model_repro->partoos,

            ],


            [

            'attribute' => 'hembras',
            'value' => $model_repro->hembraas,

            ],

            [

            'attribute' => 'machos',
            'value' => $model_repro->machoos,

            ],

            [

            'attribute' => 'lactancias',
            'value' => $model_repro->lactanciaas,

            ],

        ],
        ]); 



        ?>
        </div>

        <div class="col-md-6">
        <br>

        <a href="<?= Url::toRoute(['/'."produccion".'/'."view",'id'=>$model_repro->animal_identificacion])?>" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="produccion por lactancias" >Ver producción</a>


        </div>

         <?php  
    	} 
     	else 
     	{ 
     	?>
        <div>
        	<?php if(empty($model)){?>
        	<h4>No posee datos reproducctivos....</h4>
        	<?php }?>
        </div>
        <?php 
        } 
        ?>


        <?php if(!(empty($model)) && $model->sexo =='M'): ?>
		<div class="col-md-6">
        <br>
        <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [

            [

            'attribute' => 'hijosp',
            'value' => $model->hijos_padre,

            ],

/*
            [

            'attribute' => 'hembras',
            'value' => $model_repro->hembraas,

            ],
            [

            'attribute' => 'machos',
            'value' => $model_repro->machoos,

            ],

            [

            'attribute' => 'estado_reproductivo',
            'value' => $model_repro->estados,

            ],*/

        ],
        ]); 

        

        ?>
        </div>
        <?php endif; ?>

        <?php

$js = <<<JS

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
JS;
 
$this->registerJs($js);
?>
