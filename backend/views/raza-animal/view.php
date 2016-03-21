<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\RazaAnimal */

$this->title = $model->raza_id_raza;
$this->params['breadcrumbs'][] = ['label' => 'Raza Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="raza-animal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'raza_id_raza' => $model->raza_id_raza, 'animal_identificacion' => $model->animal_identificacion], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'raza_id_raza' => $model->raza_id_raza, 'animal_identificacion' => $model->animal_identificacion], [
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
            'raza_id_raza',
            'porcentaje',
            'animal_identificacion',
        ],
    ]) ?>

</div>
