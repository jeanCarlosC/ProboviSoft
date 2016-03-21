<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Semen */

$this->title = 'Agregar Pajuelas';
?>
<div class="semen-create formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('agregar_pajuelas', [
        'model' => $model,
    ]) ?>

</div>
