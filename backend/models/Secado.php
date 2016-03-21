<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "secado".
 *
 * @property integer $id_secado
 * @property string $fecha
 * @property integer $animal_identificacion
 * @property integer $parto_id_parto
 * @property string $creado
 *
 * @property Parto $partoIdParto
 * @property Animal $animalIdentificacion
 */
class Secado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $Inicio_Lactancia;
    public $Fin_Lactancia;
    public $identificacion;
    public $dias;
    public $animal;
    public $secado_fecha;
    public $parto_fecha;
    public $cria;
    public $sexo_cria;
    public $meses; 
    public $parto; 
    public static function tableName()
    {
        return 'secado';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'animal_identificacion', 'parto_id_parto', 'creado'], 'required'],
            [['fecha', 'creado','identificacion','dias'], 'safe'],
            [['animal_identificacion'],'checkAnimal'],
            [['fecha'],'checkDate'],
            [['animal_identificacion', 'parto_id_parto','Inicio_Lactancia','Fin_Lactancia','animal'], 'integer']
        ];
    }

    public function checkDate($attribute, $params)
        {
           /* $ordeño = Ordeno::find()*/
            if( strtotime('now')<strtotime($this->fecha))
            {

                $this->addError($attribute,'La fecha del secado no puede ser mayor a la fecha actual');
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
            'id_secado' => 'Id Secado',
            'fecha' => 'Fecha de secado',
            'animal_identificacion' => 'Animal Identificacion',
            'parto_id_parto' => 'Parto Id Parto',
            'creado' => 'Creado',
            'dias'=>'dias de Lactancia',
            'parto_fecha'=>'Fecha del parto',
            'secado_fecha'=>'Fecha del secado',
            'sexo_cria'=>'Sexo de la cria',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartoIdParto()
    {
        return $this->hasOne(Parto::className(), ['id_parto' => 'parto_id_parto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalIdentificacion()
    {
        return $this->hasOne(Animal::className(), ['identificacion' => 'animal_identificacion']);
    }
}
