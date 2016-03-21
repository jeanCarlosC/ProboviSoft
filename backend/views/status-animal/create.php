<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\StatusAnimal */

$this->title = 'Create Status Animal';
$this->params['breadcrumbs'][] = ['label' => 'Status Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-animal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
