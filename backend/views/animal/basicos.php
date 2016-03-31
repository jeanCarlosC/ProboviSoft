<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->identificacion_otro;
?>

<br>
    <div class='col-md-6'>
    

        <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [

            'nuemro_arete',
            'fecha_nacimiento',
            'edad',
            'sexo',
            'raza',


        ],
        ]); 


        ?>
    </div>

        <div class="col-md-6">
        <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [

            [

            'attribute' => 'estado',
            'value' => $model->estados,

            ],
            [
            'attribute' => 'status_MV',
            'value' => $model->statusMV,

            ],
            

            'padre_1',
            'madre_1',


        ],
        ]); 


        ?>
    </div>