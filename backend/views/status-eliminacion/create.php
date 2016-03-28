<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\StatusEliminacion */

$this->title = 'Eliminar Animal';
$this->params['breadcrumbs'][] = ['label' => 'Status Eliminacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perfil">

    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'data'=> $data,
    ]) ?>

</div>
