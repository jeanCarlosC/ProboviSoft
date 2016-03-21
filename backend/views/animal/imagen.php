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
use backend\models\Raza;
use backend\models\RazaAnimal;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Animal */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="animal-form">

 <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'id' => 'dynamic-form',
    'method'=>'post',
    'options' => ['enctype' => 'multipart/form-data'],
 ]); ?>
<div class="row">
 <div class='col-md-12'>
<?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
'options' => ['accept' => 'image/*'],
]); ?>
</div>
</div>

<?php ActiveForm::end(); ?>
 </div>
