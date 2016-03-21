<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\StatusAnimal */

$this->title = $model->status_id_status;
$this->params['breadcrumbs'][] = ['label' => 'Status Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-animal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'status_id_status' => $model->status_id_status, 'animal_identificacion' => $model->animal_identificacion], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'status_id_status' => $model->status_id_status, 'animal_identificacion' => $model->animal_identificacion], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'status_id_status',
            'animal_identificacion',
            'fecha',
        ],
    ]) ?>

</div>
