<?php

/* @var $this yii\web\View */
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = 'ProboviSoft';
?>
<div class="site-index">
<br>
    <div class="jumbotron">
        <h1>ProboviSoft</h1>

        <p class="lead">Una excelente opción para el control productivo de tu finca o hacienda </p>
    </div>

    <div class="body-content">
    <div class="row">
    <div class="col-md-12"><h1 style="font-size:30px">Registros - Eventos</h1><HR></div>
    <!-- <div class="linea col-md-10"></div> -->
    </div>
        <div class="row">
            <br> 
            <?php
            $arr1 = array("animal", "peso","semen","servicio","diagnostico","parto");
            $arr2 = array("animal" => "Animales", "peso"=>"Pesos Corporales","semen"=>"Semen","servicio" => "Servicios", "diagnostico" => "Diagnósticos", "parto" => "Partos","aborto" => "Abortos","ordeno" => "Pesajes de leche","secado" => "Secados","produccion" => "Producción");
                foreach ($arr1 as $key => &$val):   
                
                ?>

                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" >
                    <div class="thumbnail">
                        <a href="<?= Url::toRoute(['/'."$val".'/'])?>"><img src="../../imgprueba/<?php echo $val ?>.jpg" width=300 height=300 ></a>
                        <h4 style="font-size: 10px"><?php echo $arr2[$val]; ?></h4>
                    </div>  
                    
                </div>
            <?php
            endforeach;
            ?>

        </div>

        <div class="row">
            <br> 
            <?php
            $arr1 = array("aborto","ordeno","secado","produccion");
            $arr2 = array("animal" => "Animales", "peso"=>"Pesos Corporales","semen"=>"Semen","servicio" => "Servicios", "diagnostico" => "Diagnosticos", "parto" => "Partos","aborto" => "Abortos","ordeno" => "Pesajes de leche","secado" => "Secados","produccion" => "Producción");
                foreach ($arr1 as $key => &$val):   
                
                ?>

                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" >
                    <div class="thumbnail">
                        <a href="<?= Url::toRoute(['/'."$val".'/'])?>"><img src="../../imgprueba/<?php echo $val ?>.jpg" width=300 height=300 ></a>
                        <h4 style="font-size: 10px"><?php echo $arr2[$val]; ?></h4>
                    </div>  
                    
                </div>
            <?php
            endforeach;
            ?>

        </div>

    </div>
</div>

<?php

$js = <<<JS
$(function(){

$("[data-toggle='tooltip']").tooltip();

});
JS;
 
$this->registerJs($js);

?>
