<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "aborto".
 *
 * @property integer $id_aborto
 * @property string $fecha
 * @property string $observacion
 * @property integer $servicio_id_servicio
 * @property integer $animal_identificacion
 *
 * @property Servicio $servicioIdServicio
 * @property Animal $animalIdentificacion
 */
class Aborto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $identificacion="";
    public static function tableName()
    {
        return 'aborto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'observacion', 'servicio_id_servicio', 'animal_identificacion','sexo_feto'], 'required'],
            [['fecha'], 'date', 'format'=>'Y-m-d'],
            [['fecha'],'checkDate'],
            [['sexo_feto','identificacion'],'safe'],
            [['animal_identificacion'],'checkAnimal'],
            [['observacion'], 'string'],
            [['servicio_id_servicio', 'animal_identificacion'], 'integer'],
            [['servicio_id_servicio'], 'unique']
        ];
    }

            public function checkDate($attribute, $params)
        {
             
            if( strtotime("now") < strtotime($this->fecha) )
            {

                $this->addError($attribute,'La fecha de aborto no puede ser mayor a la fecha actual');
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
            'id_aborto' => 'Aborto',
            'fecha' => 'Fecha del aborto',
            'observacion' => 'Observación',
            'servicio_id_servicio' => 'Servicio',
            'animal_identificacion' => 'Animal que aborto',
            'sexo_feto'=>'Sexo del feto',
            'identificacion'=>'Madre',
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
}
