<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Tipo */

$this->title = 'Update Tipo: ' . ' ' . $model->id_tipo;
$this->params['breadcrumbs'][] = ['label' => 'Tipos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tipo, 'url' => ['view', 'id' => $model->id_tipo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tipo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
