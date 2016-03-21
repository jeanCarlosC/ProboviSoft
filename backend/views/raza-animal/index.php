<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RazaAnimalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Raza Animals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="raza-animal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Raza Animal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'raza_id_raza',
            'porcentaje',
            'animal_identificacion',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
