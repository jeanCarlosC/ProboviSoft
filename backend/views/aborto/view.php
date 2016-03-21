<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Aborto */

$this->title = 'Identificador de la Madre: '.$model->identificacion;
$this->params['breadcrumbs'][] = ['label' => 'Abortos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aborto-view">

    <p>
    <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_aborto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_aborto], [
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
        'observacion:ntext',
        'sexo_feto',

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
