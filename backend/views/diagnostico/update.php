<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Diagnostico */

$this->title = 'Actualizar Diagnostico ';
$this->params['breadcrumbs'][] = ['label' => 'Diagnosticos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_diagnostico, 'url' => ['view', 'id' => $model->id_diagnostico]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="diagnostico-update formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
