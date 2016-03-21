<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Parto */

$this->title = 'Actualizar Parto: ' . ' ' . $model->id_parto;
$this->params['breadcrumbs'][] = ['label' => 'Partos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_parto, 'url' => ['view', 'id' => $model->id_parto]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="parto-update formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_update', [
        'model' => $model,
    ]) ?>

</div>
