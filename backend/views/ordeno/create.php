<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Ordeno */

$this->title = 'Registrar Pesaje leche';
?>

<div class="ordeno-create formulario">

 <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
<!-- <a href="index" > <i class="glyphicon glyphicon-arrow-left"></i> volver</a> -->

    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
