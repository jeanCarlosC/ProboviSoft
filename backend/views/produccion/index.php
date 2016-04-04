<?php

/* @var $this yii\web\View */
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use miloschuman\highcharts\Highcharts;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use kartik\export\ExportMenu;

$this->title = 'Producción';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produccion-index">
<h1>Producción</h1>
    <div class="body-content">


    <?php

                $gridColumns = 
                [
                    ['class' => 'yii\grid\SerialColumn'],

                    'identificacion',

                    [
                    'attribute' => 'raza',
                    'value' => 'raza_concatenada.raza',
                    ],

                    'PAL',

                    'Lac',

                    'PLL',

                    'PDL',

                    'PLD',
                ];

                $gridColumns2 = 
                [
                    ['class' => 'yii\grid\SerialColumn'],

                    'identificacion',

                    [
                    'attribute' => 'raza',
                    'value' => 'raza_concatenada.raza',
                    ],

                    'PAL',

                    'Lac',

                    'PLL',

                    'PDL',

                    'PLD',
                    [
                            'class' => 'yii\grid\ActionColumn',

                            'header'=>'Ver',

                            'headerOptions' => ['width' => '10'],

                            'template' => '{view}',
                            'buttons' => 
                            [

                                    'view' => function ($url) 
                                    {

                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('app', 'Ver Producción'),
                                    ]);

                                    },
                            ],
                                    'urlCreator' => function ($action, $model, $key, $index) 
                                    {

                                    if ($action === 'view') 
                                    {

                                        return \Yii::$app->getUrlManager()->createUrl(['produccion/view', 'id' => $model['identificacion']]);
                                    }

                                    }

                            ],
                ];

                echo ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns
                ]);

               echo GridView::widget([
                'dataProvider' => $dataProvider,
               /* 'filterModel' => $searchModel,*/
                'pjax'=>true,
                'responsive'=>true,
                'hover'=>true,
                'pjaxSettings'=>
                [
                'neverTimeout'=>true,
                ],
                'export'=>false,
                'columns' => $gridColumns2,

                ]); 
            ?>


   

    </div>
</div>
