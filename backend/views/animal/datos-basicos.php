<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use kartik\date\DatePicker;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use kartik\tabs\TabsX;
use backend\models\Peso;
use backend\models\RazaAnimal;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model backend\models\Animal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="animal-form">

    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'id' => 'dynamic-form',
    'method'=>'post',
    'action'=>['crear-animal'],
    'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

<div class="row">
<div class="col-md-4">
    <?= $form->field($model, 'identificacion')->textInput() ?>
</div>

<div class="col-md-4">
    <?= $form->field($model, 'sexo')->dropDownList([ 'M' => 'Macho (M)', 'H' => 'Hemabra (H)', ], ['prompt' => 'Seleccione el sexo..'])?>
</div> 

<div class="col-md-4">
<?= $form->field($model, 'fecha_nacimiento')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'seleccione la fecha ...'],
    'pluginOptions' => [
        'format' => 'yyyy-m-d',
        'autoclose'=>true
    ]
    ]);
    ?>
</div>

</div>

<div class="row">
<div class="col-md-4">
    <?= $form->field($model, 'nuemro_arete')->textInput() ?>
</div>

<div class="col-md-4">
    <?= $form->field($model, 'madre')->textInput() ?>
</div>

<div class="col-md-4">
    <?= $form->field($model, 'padre')->textInput() ?>
</div>

</div>

        <div class="row">

        <div class="panel panel-default">
        <div class="panel-heading"><h4>Raza</h4></div>
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 3, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $model_raza[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'raza_id_raza',
                    'porcentaje',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($model_raza as $i => $model_raza1): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">Razas</h3>
                        <div class="pull-right">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $model_raza1->isNewRecord) {
                                echo Html::activeHiddenInput($model_raza1, "[{$i}]id");
                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model_raza1, "[{$i}]raza_id_raza")->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model_raza1, "[{$i}]porcentaje")->textInput(['maxlength' => true]) ?>
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>