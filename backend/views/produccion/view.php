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
use backend\models\Parto;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;

$this->title = 'Animal: '.$model->identificacion;
?>
<div class="produccion-index">
<?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
<h1>Producción por lactancia</h1>
<div class="body-content">

<div class="row perfil">
<h1 class="titulo2"><?= Html::encode($this->title) ?></h1>
<div class="row col-md-12 ">

    <div class='col-md-4'>

    <a class="perfilanimalv"><img src="../../uploads/<?php echo $model->imagen; ?>" alt=""></a>

    </div>
<br>
    <div class='col-md-8'>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'arete',
                'raza',
                'fecha_nacimiento',
                'edad',
                [

                'attribute' => 'estado',
                'value' => $model->estados,

                ],

            ],
        ]) ?>

    </div>

</div>
    </div>

<div class="row perfil">
<h1 class="titulo">Curva de lactancia</h1>
<?php


$partos = Parto::find()->where(['animal_identificacion'=>$model->identificacion])->orderBy(['id_parto'=>SORT_ASC])->all();
$conteo = Parto::find()->where(['animal_identificacion'=>$model->identificacion])->orderBy(['id_parto'=>SORT_ASC])->count();

    foreach ($partos as $key2 => $value2) {
    $query2 = Ordeno::find()->where(['animal_identificacion'=>$model->identificacion])
    ->where(['parto_id_parto'=>$value2['id_parto']])->all();
    $query = Secado::find()->select(["secado.fecha Fin_Lactancia"])
    ->where(['parto_id_parto'=>$value2["id_parto"]])->one();

    /*************************COLORES ALEATORIOS GRAFICA**********************************/
    $parte1=str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    $parte2=str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    $parte3=str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    
    $color[$key2]= '#'.$parte1.$parte2.$parte3;

 /*   echo "<pre>";
            print_r($color[$key2]);
            echo "</pre>";
            yii::$app->end();*/

    if(empty($query))
    {
        $final = 'En Produccion';
    }
    else
    {
        $final = $query['Fin_Lactancia'];
    }
    $dias = array();
    $pesajes = array();
    $dias[0] = 0;
    $pesajes[0]=0;
    $anterior=0;
    
    foreach ($query2 as $key => $value) {
    if($key==0)
    {
       /* $pesajes[0]=$value['pesaje'];
        $dias[0] = 0.001;*/
    }
    $pesajes[]=$value['pesaje'];
    $anterior += $value['dias'];
    $dias[]=$anterior;
    }

    $serie[$key2] = array('color'=>$color[$key2],'name'=>$model->identificacion,'data'=>$pesajes);
    if($key2==0){

    $tabs[$key2]= array('label'=>'Lactancia '.($key2+1),'content'=> $this->render('lactancia', [
            'model' => $model,
            'serie'=>$serie[$key2],
            'dias'=>$dias,
            'ordeno'=>$value,
            'inicio'=>$value2['fecha'],
            'fin'=>$final,

            ]),
    'active' => true);
 
}else
{

   $tabs[$key2]= array('label'=>'Lactancia '.($key2+1),'content'=> $this->render('lactancia', [
            'model' => $model,
            'serie'=>$serie[$key2],
            'dias'=>$dias,
            'ordeno'=>$value,
            'inicio'=>$value2['fecha'],
            'fin'=>$final,

            ])); 

}
}
?>
 <div class="col-md-12">
<?php
    echo Tabs::widget([
    'items' => $tabs,
    ]);
?>
</div>
<div class="col-md-12"><br></div>
</div>
    
   

    </div>
    </div>
