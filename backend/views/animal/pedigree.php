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
            <p align="center"><b>Madre</b></p>
            <h4 align="center"><?php echo"Id: ".$pedi['madre']['identificacion']?></h4>
            <h4 align="center"><?php echo"Raza: ".$pedi['madre']['raza']?> </h4>
         </div>

         <br>
         <br>
         <br>
         <br>
         <br>
         <br>
         <br>


         <div class="pedigree">
            <p align="center"><b>Padre</b></p>
            <h4 align="center"><?php echo"Id: ".$pedi['padre']['identificacion']?></h4>
            <h4 align="center"><?php echo"Raza: ".$pedi['padre']['raza']?></h4>
            
        </div>

    </div>


    <div class="col-md-4">
        <br>

        <div class="pedigree">
            <p align="center"><b>Abuelo</b></p>
            <h4 align="center"><?php echo"Id: ".$pedi['abuelo_padre']['identificacion']?></h4>
            <h4 align="center"><?php echo"Raza: ".$pedi['abuelo_padre']['raza']?></h4>
        </div>

        <br>

        <div class="pedigree">
            <p align="center"><b>Abuela</b></p>
            <h4 align="center"><?php echo"Id: ".$pedi['abuela_padre']['identificacion']?></h4>
            <h4 align="center"><?php echo"Raza: ".$pedi['abuela_padre']['raza']?></h4>
        </div>

        <br>

        <div class="pedigree">
            <p align="center"><b>Abuelo</b></p>
            <h4 align="center"><?php echo"Id: ".$pedi['abuelo_madre']['identificacion']?></h4>
            <h4 align="center"><?php echo"Raza: ".$pedi['abuelo_madre']['raza']?></h4>

        </div>

        <br>

        <div class="pedigree">
            <p align="center"><b>Abuela</b></p>
            <h4 align="center"><?php echo"Id: ".$pedi['abuela_madre']['identificacion']?></h4>
            <h4 align="center"><?php echo"Raza: ".$pedi['abuela_madre']['raza']?></h4>
        </div>
        <br>
    </div>
