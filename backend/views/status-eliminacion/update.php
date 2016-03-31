<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\StatusEliminacion */

$this->title = 'Actualizar Eliminacion: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Status Eliminacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>

    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
    ]) ?>

</div>
