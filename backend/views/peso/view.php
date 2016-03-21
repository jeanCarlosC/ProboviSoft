<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Peso */

$this->title = 'Animal Pesado: '.$model->animal_identificacion;
$this->params['breadcrumbs'][] = ['label' => 'Pesos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peso-view">

    <p> 
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_peso], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_peso], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="row perfil">
 <h1 class="titulo"><?= Html::encode($this->title) ?></h1>
 <div class="col-md-12">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fecha',
            'tipo_id_tipo',
            'peso',
        ],
    ]) ?>
</div>
</div>
</div>
