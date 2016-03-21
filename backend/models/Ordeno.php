<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ordeno".
 *
 * @property integer $id_ordeno
 * @property string $fecha
 * @property integer $pesaje
 * @property integer $id_potrero
 * @property integer $animal_identificacion
 * @property string $turnos
 *
 * @property Potrero $idPotrero
 * @property Animal $animalIdentificacion
 */
class Ordeno extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $identificacion="";
    public $acumulado;
    public $Inicio_Lactancia;
    public $dias_acum;
    public $pesaje_p;
    public $numero_ordeños;
    public $lactancias;
    public $todo;
    public $pesaje_total;
    public $dias_acumula;
    public $dias_totales;
    public $leche;
    public $pro_media;
    public $promedio;
    public static function tableName()
    {
        return 'ordeno';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['animal_identificacion', 'fecha', 'turno_manana', 'turno_tarde', 'pesaje', 'dias', 'id_potrero', 'parto_id_parto', 'creado'], 'required'],
            [['fecha'], 'checkDate'],
            [['animal_identificacion'],'checkAnimal'],
            [['animal_identificacion', 'turno_manana', 'turno_tarde', 'dias', 'id_potrero', 'parto_id_parto'], 'integer'],
            [['fecha', 'creado'], 'safe'],
            [['pesaje'], 'number'],

        ];
    }


    public function checkDate($attribute, $params)
        {

            if( strtotime('now')<strtotime($this->fecha))
            {

                $this->addError($attribute,'La fecha del pesaje no puede ser mayor a la fecha actual');
            }

            $animal = Animal::find()->all();
            foreach ($animal as $key => $value) {
            $ordeño = StatusAnimal::find()->where(['animal_identificacion'=>$value['identificacion']])->orderBy(['fecha'=>SORT_DESC])->one();
            if(!empty($ordeño))
            {
            if($ordeño->status_id_status=='O')
            {
            $parto = Parto::find()->where(['animal_identificacion'=>$ordeño->animal_identificacion])
            ->orderBy(['fecha'=>SORT_DESC])
            ->one();
            $pesaje = Ordeno::find()->where(['parto_id_parto'=>$parto->id_parto])
            ->orderBy(['fecha'=>SORT_DESC])
            ->one();

            if($pesaje)
            {
                //$model->pesaje_anterior = $pesaje->fecha;

                $interval1 = date_diff(date_create($pesaje->fecha), date_create($this->fecha));
                $dias_pe = (int)$interval1->format('%R%a');
                if($dias_pe<1)
                {
                    $this->addError($attribute,'La fecha del pesaje es menor a la fecha del ultimo pesaje del animal '.$ordeño->animal_identificacion);
                }
                /*$model->dias=$dias_pe;*/

            }
            else
            {

                $interval2 = date_diff(date_create($parto->fecha), date_create($this->fecha));
                $dias_pa = (int)$interval2->format('%R%a');
                if($dias_pa<1)
                {
                    $this->addError($attribute,'La fecha del pesaje es menor a la fecha del parto del animal '.$ordeño->animal_identificacion);
                }
                /*$model->dias=$dias_pa;*/

            }

            }

            }
        }

    }

        public function checkAnimal($attribute, $params)
        {

            $animal = Animal::find()->where(['identificacion'=>$this->animal_identificacion])->one();
            if(empty($animal))
            {

                $this->addError($attribute,'el animal '.$this->animal_identificacion.' no existe');
            }
            elseif($animal->sexo=='M')
            {
                $this->addError($attribute,'el animal '.$this->animal_identificacion.' no es una hembra');
            }
        }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_ordeno' => 'Ordeno',
            'fecha' => 'Fecha del pesaje',
            'pesaje' => 'Pesaje',
            'id_potrero' => 'Potrero',
            'animal_identificacion' => 'Identificacion de la Vaca/novilla',
            'turno_manana' => 'Turno Mañana',
            'turno_tarde' => 'Turno Tarde',
            'pesaje_p'=> 'Pesaje promedio',
            'dias_acum' => 'Dias en produccion',
            'leche'=>'Produccion total de leche',
            'dias_totales'=>'Dias en producción',
            'parto_id_parto'=>'parto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPotrero()
    {
        return $this->hasOne(Potrero::className(), ['id_potrero' => 'id_potrero']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalIdentificacion()
    {
        return $this->hasOne(Animal::className(), ['identificacion' => 'animal_identificacion']);
    }

    public function getLactanciaas()
    {
        return StatusAnimal::find()->where(['animal_identificacion' => $this->animal_identificacion])->andWhere(['status_id_status'=>'O'])->count();
    }

    public function getProduccion_leche()
    {

        $datos = array("dias"=>0,"total"=>0,"acumulado"=>0,"pesaje_promedio"=>0,"dias_acumulados"=>0);

        $pesaje_ultimo = Ordeno::find()->where(['animal_identificacion'=>$this->animal_identificacion])
        ->where(['<', 'id_ordeno',$this->id_ordeno])
        ->andWhere(['parto_id_parto'=>$this->parto_id_parto])
        ->orderBy(['id_ordeno'=>SORT_DESC])
        ->one();

        $todos = Ordeno::find()
        ->select(["SUM(ordeno.dias*ordeno.pesaje) as todo","SUM(ordeno.dias) as dias_acumula","SUM(ordeno.dias*ordeno.pesaje)/SUM(ordeno.dias) as pesaje_p"])
        ->where(['<=', 'id_ordeno',$this->id_ordeno])
        ->andwhere(['parto_id_parto'=>$this->parto_id_parto])
        ->andwhere(['ordeno.animal_identificacion'=>$this->animal_identificacion])
        ->one();

        $parto = Parto::find()
        ->where(['animal_identificacion'=>$this->animal_identificacion])
        ->andwhere(['id_parto'=>$this->parto_id_parto])
        ->one();

/*
            echo "<pre>";
            print_r($this->parto_id_parto);
            echo "</pre>";
            yii::$app->end();*/

        if(!empty($pesaje_ultimo))
        {
            $fecha_anterior = $pesaje_ultimo->fecha;

            $interval1 = date_diff(date_create($pesaje_ultimo->fecha), date_create($this->fecha));
            $dias_pe = (int)$interval1->format('%R%a');

            $dias=$dias_pe;
            $pesaje_total=($dias*(float)$this->pesaje);
            $datos['total']=$pesaje_total;
            $datos['dias']=$dias;
        }
        else
        {
            $interval1 = date_diff(date_create($parto->fecha), date_create($this->fecha));
            $dias_pa = (int)$interval1->format('%R%a');

            $dias=$dias_pa;
            $pesaje_total=($dias*(float)$this->pesaje);
            $datos['total']=$pesaje_total;
            $datos['dias']=$dias;
        }

        $datos['acumulado']= round($todos->todo,2);
        $datos['pesaje_promedio'] = round($todos->pesaje_p,2);
        $datos['dias_acumulados'] = $todos->dias_acumula;


        return $datos;
    }

    public function getProduccion_total()
    {
        $todos = Ordeno::find()
        ->select(["SUM(ordeno.dias*ordeno.pesaje) as leche","SUM(ordeno.dias*ordeno.pesaje)/SUM(ordeno.dias) as pro_media","SUM(ordeno.dias) as dias_totales"])
        ->where(['animal_identificacion'=>$this->animal_identificacion])
        ->andWhere(['parto_id_parto'=>$this->parto_id_parto])
        ->groupBy('ordeno.animal_identificacion')
        ->one();

        return $pro = array("leche"=>round($todos->leche,2),"dias"=>$todos->dias_totales,"promedio"=>round($todos->pro_media,2));
    }
}
