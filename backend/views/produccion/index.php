<?php

/* @var $this yii\web\View */
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use miloschuman\highcharts\Highcharts;
use yii\db\Query;
use yii\helpers\ArrayHelper;

$this->title = 'Producción';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produccion-index">
<h1>Producción</h1>
    <div class="body-content">


    <?php

               echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => 
                [
                    ['class' => 'yii\grid\SerialColumn'],

                     'identificacion_otro',
                     'arete',
                    [
                    'attribute' => 'raza',
                    'value' => 'raza_concatenada.raza',
                    ],
                       [
                            'class' => 'yii\grid\ActionColumn',

                            'header'=>'Ver',

                            'headerOptions' => ['width' => '10'],

                            'template' => '{view}',
                            'buttons' => 
                            [

                                    'view' => function ($url) {

                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'Ver Producción'),
                                    ]);

                                    },
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {

                                if ($action === 'view') 
                                {
                                return \Yii::$app->getUrlManager()->createUrl(['produccion/view', 'id' => $model['identificacion_otro']]);
                                }
                            }

                        ],

                ],


                ]); 
            ?>


   

    </div>
</div>
