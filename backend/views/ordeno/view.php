<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Ordeno */

$this->title = 'Vaca ordeñada: '.$model->animal_identificacion;
$this->params['breadcrumbs'][] = ['label' => 'Ordenos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ordeno-view">

    <p>
    <?= Html::a('<i class="glyphicon glyphicon-chevron-left">Atras</i>', ['index'], ['class' => 'btn btn-link']) ?>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_ordeno], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_ordeno], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
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

            'fecha',
            'pesaje',
            [

            'attribute' => 'dias',
            'value' => $model->produccion_leche['dias'],

            ],
            [

            'attribute' => 'pesaje_total',
            'value' => $model->produccion_leche['total'],

            ],
            [

            'attribute' => 'acumulado',
            'value' => $model->produccion_leche['acumulado'],

            ],

        ],
    ]) ?>
</div>

    <div class="col-md-6">
    <?= DetailView::widget([
        'model' => $parto,
        'attributes' => [

            'fecha',
            'cod_becerro',
            'sexo_becerro',

        ],
    ]) ?>
</div>

</div>
</div>
