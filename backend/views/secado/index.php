<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\SecadoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vientres Secados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="secado-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Registrar Secado', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings'=>
        [
           'neverTimeout'=>true,
        ],
        'export'=>false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'identificacion',
            'Inicio_Lactancia',
            'Fin_Lactancia',
            'dias',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
