<?php
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use backend\models\StatusAnimal;
use backend\models\Animal;/*
use yii\widgets\ActiveForm;
*/
/*use kartik\form\ActiveForm;*/
?>
<?php $form = ActiveForm::begin([
    'id' => 'form',
    'method'=>'post',
    'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>
    <?php 

   /* echo "<pre>";
            print_r($dataProvider);
            echo "</pre>";
            yii::$app->end();*/
    ?>
    <div class="row formularioP">
    <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo">Pesajes de Leche</h1>
   <!--  <div class="col-md-12"> -->
<div class="container-items"><!-- widgetContainer -->
    
    <div class="col-md-12">
    <table border="1" style="width:100%">
    <tr>
    <td style="font-size: 18px" align="center"><b>Vaca/Novilla</td>
    <td style="font-size: 18px" align="center"><b>Pesaje Mañana</td> 
    <td style="font-size: 18px" align="center"><b>Pesaje Tarde</td> 
    <td style="font-size: 18px" align="center"><b>dias</td>      
    <td style="font-size: 18px" align="center"><b>Fecha del pesaje</td>
    </tr>
            <?php foreach ($model as $i => $model1): ?>
                <!-- widgetBody -->
                   <!--  <div class="panel-heading">
                        
                      <div class="clearfix"></div>

                    </div> -->
                    <!-- <div class="panel-body"> -->


                    <!--     <div class="row">
                            <div class="col-sm-4" align="center"> -->

                            <tr>
                            <td align="center"> <?php echo $dataProvider[$i]['animal_identificacion'];?> </td>
                            <!-- </div> -->
                            <!-- <div class="col-sm-4"> -->
                            <td style="padding-top: 15px; padding-right: 10px" align="center"><?= $form->field($model1, "[{$i}]turno_manana")->textInput(['maxlength' => true])->label(false); ?></td>

                            <td style="padding-top: 15px" align="center"><?= $form->field($model1, "[{$i}]turno_tarde")->textInput(['maxlength' => true])->label(false); ?></td>

                            <td align="center"> <?php echo $dataProvider[$i]['dias'];?> </td>   
                            <!-- </div> -->
                            <!-- <div class="col-sm-4" align="center"> -->
                            <td align="center"><?php echo $dataProvider[$i]['fecha'];?></td>
                            </tr>
                            <!-- </div> -->
                     <!--   </div> .row -->
                    <!-- </div> -->
                
            <?php endforeach; ?>
            </table>
            </div>
            <div class="col-md-12"><br></div>

            </div>
            <div class="form-group">
            <?= Html::submitButton('Guardar',['class' =>'btn btn-success']) ?>
            </div>
            <!-- </div> -->
            </div>
            <?php ActiveForm::end(); ?>