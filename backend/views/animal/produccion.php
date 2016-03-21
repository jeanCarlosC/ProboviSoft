<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
?>

<?php if(!empty($model_produ)): ?>
        <div class="col-md-6">
<br>
          <?php echo DetailView::widget([
        'model' => $model_produ,
        'attributes' => [

        	[

            'attribute' => 'lactancias',
            'value' => $model_produ->lactanciaas,

            ],

            'Inicio_Lactancia',
            [

            'attribute' => 'pesaje_p',
            'value' => $model_produ->produccion_leche['pesaje_promedio'],

            ],
            
            [

            'attribute' => 'acumulado',
            'value' => $model_produ->produccion_leche['acumulado'],

            ],
            [

            'attribute' => 'dias_acum',
            'value' => $model_produ->produccion_leche['dias_acumulados'],

            ],


        ],
        ]); 


        ?>  

        </div>
        <?php endif; ?>

       <?php if(empty($model_produ)): ?>
        <div>
        	
        	<h4>No posee datos producctivos....</h4>

        </div>
        <?php endif; ?>