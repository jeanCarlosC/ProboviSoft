<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Animal */

$this->title = 'Registrar Animal';
$this->params['breadcrumbs'][] = ['label' => 'Animals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animal-create formulario">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model_raza'=>$model_raza,
        'model_peso'=>$model_peso,
    ]) ?>

</div>
