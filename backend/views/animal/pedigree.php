<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
?>
<?php
/*    echo "<pre>";
    print_r($madre);
    echo "</pre>";
    yii::$app->end();*/
    ?>



    <div class="col-md-4">

         <br>
         <br>
         <br>
         <div class="pedigree" >
            <p align="center">Madre</p>
            <h4 align="center" data-toggle="tooltip" data-placement="bottom" title="identificacion / raza"><b><?php echo $pedi['madre']['identificacion']."  /  ".$pedi['madre']['raza'];?> </b></h4>
         </div>

         <br>
         <br>
         <br>
         <br>
         <br>


         <div class="pedigree">
            <p align="center">Padre</p>
            <h4 align="center" data-toggle="tooltip" data-placement="bottom" title=" identificacion / Raza "><b><?php echo $pedi['padre']['identificacion']."  /  ".$pedi['padre']['raza']; ?> </b></h4>
            
        </div>

    </div>


    <div class="col-md-4">
        <br>

        <div class="pedigree">
            <p align="center">Abuelo</p>
            <h4 align="center" data-toggle="tooltip" data-placement="left" title="identificacion / raza"><b><?php echo $pedi['abuelo_padre']['identificacion']."  /  ".$pedi['abuelo_padre']['raza'];?></b></h4>
        </div>

        <br>

        <div class="pedigree">
            <p align="center">Abuela</p>
            <h4 align="center" data-toggle="tooltip" data-placement="left" title="identificacion / raza"><b><?php echo $pedi['abuela_padre']['identificacion']."  /  ".$pedi['abuela_padre']['raza'];?></b></h4>
        </div>

        <br>

        <div class="pedigree">
            <p align="center">Abuelo</p>
            <h4 align="center" data-toggle="tooltip" data-placement="left" title="identificacion / raza"><b><?php echo $pedi['abuelo_madre']['identificacion']."  /  ".$pedi['abuelo_madre']['raza'];?></b></h4>

        </div>

        <br>

        <div class="pedigree">
            <p align="center">Abuela</p>
            <h4 align="center" data-toggle="tooltip" data-placement="left" title="identificacion / raza" ><b><?php echo $pedi['abuela_madre']['identificacion']."  /  ".$pedi['abuela_madre']['raza'];?></b></h4>
        </div>
        <br>
    </div>
