<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Ordeno */

$this->title = 'Actualizar Pesaje de leche: ' . ' ' . $model->id_ordeno;
$this->params['breadcrumbs'][] = ['label' => 'Ordenos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_ordeno, 'url' => ['view', 'id' => $model->id_ordeno]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ordeno-update formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_update', [
        'model' => $model,
    ]) ?>

</div>
