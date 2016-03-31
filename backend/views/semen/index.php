<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SemenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Semen';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="semen-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $tis->render('_search', ['model' => $searchModel]); ?>

 <div class='row'>
    <p>
        <?= Html::a('Registrar Semen', ['create'], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Agregar Pajuelas', ['agregar'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Salida de Pajuelas', ['salida'], ['class' => 'btn btn-primary']) ?>
    </p>
    </div>
<?php Pjax::begin(); ?>
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

            'termo_identificacion',
            'canastilla_identificacion',
            'identificacion_otro',
            'raza',
            'pajuelas',            
            // 'salida',

            [
                'class' => 'yii\grid\ActionColumn',

                'header'=>'Opciones',

                'headerOptions' => ['width' => '20'],

                'template' => '{view}{update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) 
                {

                    if ($action === 'view') 
                    {
                    return \Yii::$app->getUrlManager()->createUrl(['semen/view', 'id' => $model['identificacion_otro']]);
                    }
                    else if ($action === 'update') {
                    // return \Yii::$app->getUrlManager()->createUrl(['nomina/update', 'id' => $model['idnomina']]);
                    return Url::toRoute(['semen/update', 'id' => $model['identificacion_otro']]);
                    } else if ($action === 'delete') {
                    return \Yii::$app->getUrlManager()->createUrl(['semen/delete', 'id' => $model['identificacion_otro']]);
                    }
                }
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
