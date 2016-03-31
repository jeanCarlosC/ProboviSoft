<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PartoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Partos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parto-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Registrar Parto', ['create'], ['class' => 'btn btn-primary']) ?>
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

            'animal_identificacion',
            'fecha',
            'cod_becerro',
            'sexo_becerro',

            [
                'class' => 'yii\grid\ActionColumn',

                'header'=>'Opciones',

                'headerOptions' => ['width' => '20'],

                'template' => '{view}{update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) 
                {

                    if ($action === 'view') 
                    {
                    return \Yii::$app->getUrlManager()->createUrl(['parto/view', 'id' => $model['id_parto']]);
                    }
                    else if ($action === 'update') {
                    // return \Yii::$app->getUrlManager()->createUrl(['nomina/update', 'id' => $model['idnomina']]);
                    return Url::toRoute(['parto/update', 'id' => $model['id_parto']]);
                    } else if ($action === 'delete') {
                    return \Yii::$app->getUrlManager()->createUrl(['parto/delete', 'id' => $model['id_parto']]);
                    }
                }

            ],
        ],
    ]); ?>

</div>
