<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Secado */

$this->title = 'Animal: '.$model->animal;
$this->params['breadcrumbs'][] = ['label' => 'Secados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="secado-view">

    

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_secado], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_secado], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Seguro que quieres eliminar este secado?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

<div class="row perfil">
<h1 class="titulo"><?= Html::encode($this->title) ?></h1>
<div class="col-md-6">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        'parto_fecha',
        'secado_fecha',
        'cria',
        'sexo_cria',
        ],
    ]) ?>
    </div>
    <div class="col-md-6">
    <?= DetailView::widget([
        'model' => $model_ordeño,
        'attributes' => [
        [

            'attribute' => 'dias_totales',
            'value' => $model_ordeño->produccion_total['dias'],

            ],

            [

            'attribute' => 'leche',
            'value' => $model_ordeño->produccion_total['leche'],

            ],

/*            [

            'attribute' => 'acumulado',
            'value' => $model_ordeño->produccion_total['acumulado'],

            ],*/
            
/*          
'fecha',
            'cod_becerro',
            'sexo_becerro',*/
        ],
    ]) ?>
</div>
</div>
</div>

