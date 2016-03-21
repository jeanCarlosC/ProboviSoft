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
    <td style="font-size: 18px" align="center"><b>Diagnostico</td>
    <td style="font-size: 18px" align="center">
    <div class='Inseminacion-Wrapper' <?= $diagnostico == 'V' ? 'style="display:none"' : ''?> >
    <b>
    Dias
    </b>
    </div>
    </td>
    <td style="font-size: 18px" align="center">
    <div class='Inseminacion-Wrapper' <?= $diagnostico == 'P' ? 'style="display:none"' : ''?> >
    <b>
    ovario Izq
    </b>
    </div>
    </td> 
    <td style="font-size: 18px" align="center">
    <div class='Inseminacion-Wrapper' <?= $diagnostico == 'P' ? 'style="display:none"' : ''?> >
    <b>
    ovario dere
    </b>
    </div>
    </td>

    <td style="font-size: 18px" align="center">
    <div class='Inseminacion-Wrapper' <?= $diagnostico == 'P' ? 'style="display:none"' : ''?> >
    <b>
    utero
    </b>
    </div>
    </td> 

    <td style="font-size: 18px" align="center">
    <div class='Inseminacion-Wrapper' <?= $diagnostico == 'P' ? 'style="display:none"' : ''?> >
    <b>
    observación
    </b>
    </div>
    </td> 

    <td style="font-size: 18px" align="center"><b>Fecha del servicio</td>
    </tr>
            <?php foreach ($model as $i => $model1): ?>
          
                            <tr>
                            <td align="center"> <?php echo $dataProvider[$i]['animal_identificacion'];?> </td>
                            <td align="center"> <?php echo $dataProvider[$i]['diagnostico'];?> </td>
                            <!-- </div> -->
                            <!-- <div class="col-sm-4"> -->
                                <td style="padding-top: 15px; padding-right: 10px">
                                <div class='Monta-Wrapper' <?= $diagnostico == 'V' ? 'style="display:none"' : ''?> > 

                                
                                <?= $form->field($model1, "[{$i}]dias_gestacion")->textInput()->label(false); ?>
                             
                                </div>
                                </td>
                            
                                <td style="padding-top: 15px; padding-right: 10px">
                                <div class='Monta-Wrapper' <?= $diagnostico == 'P' ? 'style="display:none"' : ''?> > 

                                
                                <?= $form->field($model1, "[{$i}]ovario_izq")->textInput()->label(false); ?>
                             
                                </div>
                                </td>
                                <td style="padding-top: 15px; padding-right: 10px">
                                <div class='Monta-Wrapper' <?= $diagnostico == 'P' ? 'style="display:none"' : ''?> > 

                                
                                <?= $form->field($model1, "[{$i}]ovario_der")->textInput()->label(false); ?>
                             
                                </div>
                                </td>
                                <td style="padding-top: 15px; padding-right: 10px">
                                <div class='Monta-Wrapper' <?= $diagnostico == 'P' ? 'style="display:none"' : ''?> > 

                                
                                <?= $form->field($model1, "[{$i}]utero")->textInput()->label(false); ?>
                             
                                </div>
                                </td>
                                <td style="padding-top: 15px; padding-right: 10px">
                                <div class='Monta-Wrapper' <?= $diagnostico == 'P' ? 'style="display:none"' : ''?> > 

                                
                                <?= $form->field($model1, "[{$i}]observacion")->textInput()->label(false); ?>
                             
                                </div>
                                </td>
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