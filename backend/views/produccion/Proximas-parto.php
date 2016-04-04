<?php

/* @var $this yii\web\View */
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use miloschuman\highcharts\Highcharts;
use yii\db\Query;
use yii\helpers\ArrayHelper;

$this->title = 'Hembras proximas a parto';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produccion-index">
<h1>Producción</h1>
    <div class="body-content">


    <?php

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
                'columns' => 
                [
                    ['class' => 'yii\grid\SerialColumn'],

                    'identificacion',
                    [
                    'attribute' => 'raza',
                    'value' => 'raza_concatenada.raza',
                    ],
                    [
                    'attribute' => 'PAL',
                    'value' => 'pro_Acum_Lac.PAL',
                    ],
                    [
                    'attribute' => 'DL',
                    'value' => 'pro_Acum_Lac.DL',
                    ],
                    [
                    'attribute' => 'Lac',
                    'value' => 'pro_Acum_Lac.L',
                    ],
                    [
                    'attribute' => 'PDL',
                    'value' => 'pro_Acum_Lac.PDL',
                    ],
                    [
                    'attribute' => 'PLL',
                    'value' => 'pro_Acum_Lac.PLL',
                    ],
                    [
                    'attribute' => 'PLD',
                    'value' => 'pro_Acum_Lac.PLD',
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
                                return \Yii::$app->getUrlManager()->createUrl(['produccion/view', 'id' => $model['identificacion']]);
                                }
                            }

                        ],

                ],


                ]); 
            ?>


   

    </div>
</div>
