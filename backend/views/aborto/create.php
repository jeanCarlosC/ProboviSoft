<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Aborto */

$this->title = 'Registrar Aborto';
$this->params['breadcrumbs'][] = ['label' => 'Abortos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aborto-create formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
