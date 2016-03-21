<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SemenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Movimientos de Semen';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semen-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $tis->render('_search', ['model' => $searchModel]); ?>

 <div class='row'>
    <p>
        <?= Html::a('Volver', ['index'], ['class' => 'btn btn-success']) ?>

    </p>
    </div>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'termo_identificacion',
            'canastilla_identificacion',
            'identificacion',
            'raza',
            'pajuelas',            
            // 'salida',
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div><?php