<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RazaAnimal */

$this->title = 'Update Raza Animal: ' . ' ' . $model->raza_id_raza;
$this->params['breadcrumbs'][] = ['label' => 'Raza Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->raza_id_raza, 'url' => ['view', 'raza_id_raza' => $model->raza_id_raza, 'animal_identificacion' => $model->animal_identificacion]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="raza-animal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
