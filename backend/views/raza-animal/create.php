<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\RazaAnimal */

$this->title = 'Create Raza Animal';
$this->params['breadcrumbs'][] = ['label' => 'Raza Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="raza-animal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
