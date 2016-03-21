<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Secado */

$this->title = 'Actualizar Secado del animal: ' . ' ' . $model->animal_identificacion;
$this->params['breadcrumbs'][] = ['label' => 'Secados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_secado, 'url' => ['view', 'id' => $model->id_secado]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="secado-update formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_update', [
        'model' => $model,
    ]) ?>

</div>
