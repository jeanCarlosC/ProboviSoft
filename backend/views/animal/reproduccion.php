<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

?>
<?php Pjax::begin(); ?>
<?php if(!empty($model_repro)){ ?>
<div class="col-md-6">
        <br>
        <?php echo DetailView::widget([
        'model' => $model_repro,
        'attributes' => [

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

            'attribute' => 'estado_reproductivo',
            'value' => $model_repro->estados,

            ],

        ],
        ]); 



        ?>
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
        <?php Pjax::end(); ?>