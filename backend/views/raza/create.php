<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Raza */

$this->title = 'Create Raza';
$this->params['breadcrumbs'][] = ['label' => 'Razas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="raza-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
