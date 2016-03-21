<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Peso */

$this->title = 'Actualizar Peso ';
$this->params['breadcrumbs'][] = ['label' => 'Pesos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_peso, 'url' => ['view', 'id' => $model->id_peso]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="peso-update formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
