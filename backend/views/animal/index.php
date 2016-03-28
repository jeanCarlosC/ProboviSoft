<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AnimalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Animales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  <p>
        <?= Html::a('Registar Animal', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'identificacion',
            'sexo',
            'raza',
            'fecha_nacimiento',
            'arete',

            [
                'class' => 'yii\grid\ActionColumn',

                'header'=>'Opciones',

                'headerOptions' => ['width' => '20'],

                'template' => '{view}{update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) 
                {

                    if ($action === 'view') 
                    {
                    return \Yii::$app->getUrlManager()->createUrl(['animal/view', 'id' => $model['identificacion']]);
                    }
                    else if ($action === 'update') {
                    // return \Yii::$app->getUrlManager()->createUrl(['nomina/update', 'id' => $model['idnomina']]);
                    return Url::toRoute(['animal/update', 'id' => $model['identificacion']]);
                    } else if ($action === 'delete') {
                    return \Yii::$app->getUrlManager()->createUrl(['animal/delete', 'id' => $model['identificacion']]);
                    }
                }

            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<?php
$script = <<< JS
$(function(){
    $('#modalButton').click(function(){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
});
JS;
$this->registerJs($script);
?>