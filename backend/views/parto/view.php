<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Parto */

$this->title = 'Identificacion de la Madre: '.$model->identificacion_otro;
$this->params['breadcrumbs'][] = ['label' => 'Partos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parto-view">

    

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_parto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_parto], [
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
    'cod_becerro',
    'sexo_becerro',
    ],
    ]) ?>
    </div>

    <div class="col-md-6">

    <?php if($servicio->tipo_servicio=='MN'):?>
    <?= DetailView::widget([
        'model' => $servicio,
        'attributes' => [

            'fecha',
            'tipo_servicio',
            'toro_monta',
        ],
    ]) ?>
<?php endif; ?>

    <?php if($servicio->tipo_servicio=='IA'):?>
    <?= DetailView::widget([
        'model' => $servicio,
        'attributes' => [

            'fecha',
            'tipo_servicio',
            'semen',
            'inseminador',
        ],
    ]) ?>
<?php endif; ?>
</div>
</div>

</div>
