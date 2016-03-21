<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Servicio */

$this->title = 'Animal Servido: '.$model->animal_identificacion;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicio-view">


    <p>
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_servicio], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_servicio], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class=" row perfil">
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>
    <?php if($model->tipo_servicio=='IA'): ?>
<div class="col-md-12">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fecha',
            'tipo_servicio',
            'semen_identificacion',
            'inseminador',
        ],
    ]) ?>
</div>
<?php endif; ?>

<?php if($model->tipo_servicio=='MN'): ?>
<div class="col-md-12">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fecha',
            'tipo_servicio',
            'toro',
        ],
    ]) ?>
</div>
<?php endif; ?>
    </div>

</div>
