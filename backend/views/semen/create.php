<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Semen */

$this->title = 'Registrar Semen';
$this->params['breadcrumbs'][] = ['label' => 'Semens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semen-create formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model_raza_semen'=>$model_raza_semen,
    ]) ?>

</div>
