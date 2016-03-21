    
<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;
use backend\models\Secado;
use backend\models\Ordeno;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;

$this->title = 'Animal: '.$model->identificacion_otro;
?>
    <div class='col-md-12'>
    <br>
      <?= DetailView::widget([
            'model' => $ordeno,
            'attributes' => [
            [
            'attribute' => 'promedio',
            'value' => $ordeno->produccion_total['promedio'],

            ],

            [
                'attribute' => 'dias_totales',
            'value' => $ordeno->produccion_total['dias'],

            ],

            [

            'attribute' => 'leche',
            'value' => $ordeno->produccion_total['leche'],

            ],

            ],
        ]) ?>
    </div>
    <div class="col-md-12">
    <?php
 Pjax::begin();
echo Highcharts::widget([
    'scripts' => [
        'highcharts-3d',
        'modules/exporting',
        'modules/offline-exporting',
        'themes/sand-signika',
    ],
   'options' => [
                'chart' => [
                'type' => 'area',
                'width' => 1100,
                'zoomType' => 'x',
            ],
      'credits' => ['enabled' => TRUE],
      'title' => ['text' => 'Lactancia: '.$inicio.'/'.$fin],
      'xAxis' => [
        'crosshair'=> [
                'width'=> 0.3,
                'color'=> 'black'
            ],
        'title'=>['text'=>'dias en produccion'],
         'categories' => $dias,
      ],
      'yAxis' => [

                'labels'=> [
                'format'=> '{value} Kg'
            ],

      'crosshair'=> [
                'width'=> 0.3,
                'color'=> 'black'
            ],
         'title' => ['text' => 'Kg de Leche']
      ],
      'tooltip' => [
                        'pointFormat' => 'PESAJE: <b>{point.y:.1f}</b>',
                        'valueSuffix'=> 'kg',
                        /*'animation'=>true,*/
                        'backgroundColor'=> [
                'linearGradient'=> [0, 0, 0, 50],
                'stops'=>  [
                    [0, '#FFFFFF'],
                    [1, '#999']
                ],

            ],
            'borderWidth'=>  1,
            'borderRadius'=>  20,
            /*'crosshairs'=> true,*/
                        ],
              /*  ],*/
                'plotOptions' => [
                        'series' => [
                         
                'dataLabels'=> [
                    'enabled'=> true,
                    'format'=> '{point.label}'
                
                        ],
                ],
                ],
      'series' => [$serie],
   ],
]);
 Pjax::end();
?>
 </div>