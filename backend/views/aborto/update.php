<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Aborto */

$this->title = 'Update Aborto: ' . ' ' . $model->id_aborto;
$this->params['breadcrumbs'][] = ['label' => 'Abortos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_aborto, 'url' => ['view', 'id' => $model->id_aborto]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="aborto-update formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
