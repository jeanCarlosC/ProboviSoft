<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\StatusAnimal */

$this->title = 'Update Status Animal: ' . ' ' . $model->status_id_status;
$this->params['breadcrumbs'][] = ['label' => 'Status Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->status_id_status, 'url' => ['view', 'status_id_status' => $model->status_id_status, 'animal_identificacion' => $model->animal_identificacion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="status-animal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
