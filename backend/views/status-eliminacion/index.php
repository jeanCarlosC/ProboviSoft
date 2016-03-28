<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\StatusEliminacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Eliminar Animal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-eliminacion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Eliminación por Animal', ['create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminación por Grupo', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'animal_identificacion',
            'causa',
            'status_id_status',
            'fecha',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
