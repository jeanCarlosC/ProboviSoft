<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Raza */

$this->title = 'Update Raza: ' . ' ' . $model->id_raza;
$this->params['breadcrumbs'][] = ['label' => 'Razas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_raza, 'url' => ['view', 'id' => $model->id_raza]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="raza-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
