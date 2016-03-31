<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "parto".
 *
 * @property integer $id_parto
 * @property string $fecha
 * @property integer $cod_becerro
 * @property integer $peso_becerro
 * @property string $sexo_becerro
 * @property integer $servicio_id_servicio
 * @property integer $animal_identificacion
 *
 * @property Servicio $servicioIdServicio
 * @property Animal $animalIdentificacion
 */
class Parto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $partos;
    public $identificacion_otro;
    public $lactancias;
    public $machos;
    public $hembras;
    public $becerro;
    public $estado_reproductivo;
    public $ultimo_parto;
    public $cod_becerro2;
    public $sexo_becerro2;
    public $peso_becerro2;
    public $tipo_parto;
    public static function tableName()
    {
        return 'parto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'servicio_id_servicio', 'animal_identificacion','cod_becerro', 'peso_becerro','sexo_becerro','id_parto'
            ], 'required'],
            [['fecha'],'checkdate_parto'],
            [['creado'],'safe'],
            [['fecha'], 'date', 'format'=>'Y-m-d'],
            [['animal_identificacion'], 'integer', 'min'=>1, 'max'=>999999],
            [['animal_identificacion'],'checkAnimal'],
            [['cod_becerro2', 'peso_becerro2','cod_becerro', 'peso_becerro', 'servicio_id_servicio', 'animal_identificacion','id_parto','identificacion_otro','becerro'], 'integer'],
            [['sexo_becerro','sexo_becerro2'], 'string', 'max' => 1],
            [['servicio_id_servicio'], 'unique'],
            [['cod_becerro'], 'unique']
        ];
    }

            public function checkdate_parto($attribute, $params)
            {

            if(strtotime('now')<strtotime($this->fecha))
            {
           
                $this->addError($attribute,'La fecha de parto no puede ser mayor a la fecha actual');
            
            }

            }

             public function checkAnimal($attribute, $params)
        {
           $animal = Animal::find()->where(['identificacion'=>$this->animal_identificacion])->one();
           $status = StatusEliminacion::find()->where(['animal_identificacion'=>$this->animal_identificacion])->one();
            
            if(empty($animal))
            {

                $this->addError($attribute,'el animal '.$this->animal_identificacion.' no existe');
            }
            elseif(!empty($status))
            {
                $this->addError($attribute,'el animal '.$this->animal_identificacion.' no se encuentra activo');
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
            'id_parto' => 'Parto',
            'fecha' => 'Fecha de parto',
            'cod_becerro' => 'becerro',
            'peso_becerro' => 'peso nacimiento',
            'sexo_becerro' => 'sexo becerro',
            'cod_becerro2' => 'becerro',
            'peso_becerro2' => 'peso nacimiento',
            'sexo_becerro2' => 'sexo becerro',
            'servicio_id_servicio' => 'Servicio',
            'animal_identificacion' => 'Animal',
            'identificacion'=> 'Madre',
            'tipo_parto'=> 'Tipo de parto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicioIdServicio()
    {
        return $this->hasOne(Servicio::className(), ['id_servicio' => 'servicio_id_servicio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalIdentificacion()
    {
        return $this->hasOne(Animal::className(), ['identificacion' => 'animal_identificacion']);
    }

    public function getPartoos()
    {
        $parto = Parto::find()->where(['animal_identificacion' => $this->animal_identificacion])->count();

        if($parto)
        {
            return $parto;
        }
        else
        {
            return 0;
        }

    }

    public function getHembraas()
    {
        $parto = Parto::find()->where(['animal_identificacion' => $this->animal_identificacion])->andWhere(['sexo_becerro'=>'H'])->count();
        if($parto)
        {
            return $parto;
        }
        else
        {
            return 0;
        }
    }

    public function getMachoos()
    {
        $parto = Parto::find()->where(['animal_identificacion' => $this->animal_identificacion])->andWhere(['sexo_becerro'=>'M'])->count();

        if($parto)
        {
            return $parto;
        }
        else
        {
            return 0;
        }
    }

    public function getLactanciaas()
    {
        return StatusAnimal::find()->where(['animal_identificacion' => $this->animal_identificacion])->andWhere(['status_id_status'=>'O'])->count();
    }

    public function getEstados()
    {
        $diagnostico = Diagnostico::find()->where(['animal_identificacion'=>$this->animal_identificacion])
        ->orderBy(['id_diagnostico'=>SORT_DESC])->one();


        if(/*!(empty($diagnostico)) &&*/ $diagnostico->diagnostico_prenez=='P'){
            $parto = Parto::find()->where(['servicio_id_servicio'=>$diagnostico->servicio_id_servicio])->one();
            if($parto)
                return 'Vacia';
            if(empty($parto))
                return 'Preñada';
        }
        else
        {
            return 'Vacia';
        }

    }

    public function getUltimo()
    {
        $parto = Parto::find()->where(['animal_identificacion' => $this->animal_identificacion])->andWhere(['animal_identificacion'=>$this->animal_identificacion])->orderBy(['id_parto'=>SORT_DESC])->one();

         return $parto->fecha;
    }
    
       
}
