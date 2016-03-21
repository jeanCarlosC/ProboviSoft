<?php
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use backend\models\StatusAnimal;
use backend\models\Animal;
use yii\db\Query;

?>
    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'id' => 'form',
    'method'=>'post',
    'options' => ['enctype' => 'multipart/form-data']
    ]); 
    ?>
    <div class="row formularioP">
    <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Atras', ['index'], ['class' => 'btn btn-link']) ?>
    <h1 class="titulo">Registro de Servicios</h1>
   <!--  <div class="col-md-12"> -->
<div class="container-items"><!-- widgetContainer -->
    
    <div class="col-md-12">
    <table border="1" style="width:100%">
    <tr>
    <td style="font-size: 18px" align="center"><b>Vaca/Novilla</td>
    <td style="font-size: 18px" align="center"><b>tipo_servicio</td>
    <td style="font-size: 18px" align="center">
    <div class='Inseminacion-Wrapper' <?= $tipo == 'IA' ? 'style="display:none"' : ''?> >
    <b>
    toro
    </b>
    </div>
    </td>
    <td style="font-size: 18px" align="center">
    <div class='Inseminacion-Wrapper' <?= $tipo == 'MN' ? 'style="display:none"' : ''?> >
    <b>
    semen
    </b>
    </div>
    </td> 
    <td style="font-size: 18px" align="center">
    <div class='Inseminacion-Wrapper' <?= $tipo == 'MN' ? 'style="display:none"' : ''?> >
    <b>
    Inseminador
    </b>
    </div>
    </td>    
    <td style="font-size: 18px" align="center"><b>Fecha del servicio</td>
    </tr>
            <?php foreach ($model as $i => $model1): ?>
          
                            <tr>
                            <td align="center"> <?php echo $dataProvider[$i]['animal_identificacion'];?> </td>
                            <td align="center"> <?php echo $dataProvider[$i]['tipo'];?> </td>
                            <!-- </div> -->
                            <!-- <div class="col-sm-4"> -->
                                <td style="padding-top: 15px; padding-right: 10px">
                                <div class='Monta-Wrapper' <?= $model1->tipo_servicio == 'IA' ? 'style="display:none"' : ''?> > 

                                <?php
                                $query1 = new Query;
                                $query1->select(["identificacion"])
                                ->from('animal')
                                ->where("animal.sexo='M'");
                                $command = $query1->createCommand();
                                $data1 = $command->queryAll();

                                echo $form->field($model1, "[{$i}]toro")->widget(TypeaheadBasic::classname(), [
                                'data' =>  ArrayHelper::map($data1,'identificacion','identificacion'),
                                'options' => ['placeholder' => 'Seleccione el toro ...'],
                                'pluginOptions' => ['highlight'=>true],
                                ])->label(false);
                                ?>
                                </div>
                                </td>
                            
                            <td style="padding-top: 15px; padding-right: 10px">
                            <div class='Inseminacion-Wrapper' <?= $model1->tipo_servicio == 'MN' ? 'style="display:none"' : ''?> >
                            
                            
                            <?php
                            $query2 = new Query;
                            $query2->select(["identificacion"])
                            ->from('semen');
                            $command = $query2->createCommand();
                            $data2 = $command->queryAll();
                            /*      echo "<pre>";
                            print_r($data);
                            echo "</pre>";
                            yii::$app->end();
                            */

                            echo $form->field($model1, "[{$i}]semen_identificacion")->widget(TypeaheadBasic::classname(), [
                            'data' =>  ArrayHelper::map($data2,'identificacion','identificacion'),
                            'options' => ['placeholder' => 'Seleccione el toro ...'],
                            'pluginOptions' => ['highlight'=>true],
                            ])->label(false);
                            ?>
                            </div>
                           </td>
                            <td style="padding-top: 15px; padding-right: 10px">
                            <div class='Inseminacion-Wrapper2' <?= $model1->tipo_servicio == 'MN' ? 'style="display:none"' : ''?> >
                            <?= $form->field($model1, "[{$i}]inseminador")->textInput(['maxlength' => true])->label(false); ?>
                            </div>
                            </td>
                            <!-- </div> -->
                            <!-- <div class="col-sm-4" align="center"> -->
                            <td align="center"><?php echo $dataProvider[$i]['fecha'];?></td>
                            </tr>
           
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