<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Diagnostico */

$this->title ='Animal Diagnosticado: '.$model->animal;
$this->params['breadcrumbs'][] = ['label' => 'Diagnosticos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="diagnostico-view ">

    

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_diagnostico], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_diagnostico], [
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
<?php if($model->diagnostico_prenez=='P'):?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'fecha',
            'diagnostico_prenez',
            'dias_gestacion',
            'parto_esperado',
        ],
    ]) ?>
<?php endif; ?>

<?php if($model->diagnostico_prenez=='V'):?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'fecha',
            'diagnostico_prenez',
            'ovario_izq',
            'ovario_der',
            'utero',
            'observacion',

        ],
    ]) ?>
<?php endif; ?>
</div>
<!-- <h1>Servicio</h1> -->
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
