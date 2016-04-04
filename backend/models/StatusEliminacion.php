<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "status_eliminacion".
 *
 * @property integer $id
 * @property string $status_id_status
 * @property integer $animal_identificacion
 * @property string $fecha
 * @property string $causa
 *
 * @property Animal $animalIdentificacion
 * @property Status $statusIdStatus
 */
class StatusEliminacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $fecha1;
    public $animales;
    public static function tableName()
    {
        return 'status_eliminacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id_status', 'animal_identificacion', 'fecha','fecha1', 'causa', 'descripcion','animales'], 'required'],
            [['animal_identificacion'], 'integer'],
            [['fecha','fecha1'], 'safe'],
            [['descripcion'], 'string'], 
            [['status_id_status'], 'string', 'max' => 1],
            [['causa'], 'string', 'max' => 45],
            [['animal_identificacion'], 'checkAnimal'],
            [['fecha'],'checkDate'],
            [['fecha1'],'checkDate1'],
        ];
    }

            public function checkDate($attribute, $params)
        {

            if( strtotime('now')<strtotime($this->fecha))
            {

                $this->addError($attribute,'La fecha no puede ser mayor a la fecha actual');
            }

        }

        public function checkDate1($attribute, $params)
        {

            if( strtotime('now')<strtotime($this->fecha1))
            {

                $this->addError($attribute,'La fecha no puede ser mayor a la fecha actual');
            }

        }

        public function checkAnimal($attribute, $params)
        {

            $animal = StatusEliminacion::find()->where(['animal_identificacion'=>$this->animal_identificacion])->one();

            if(!empty($animal))
            {
                $this->addError($attribute,'Este animal no se encuentra activo por muerte o venta');
            }

        }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_id_status' => 'Status',
            'animal_identificacion' => 'Animal',
            'fecha' => 'Fecha',
            'fecha1' => 'Fecha',
            'causa' => 'Causa',
            'descripcion' => 'Descripcion',
            'animales'=>'Animales a vender', 
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalIdentificacion()
    {
        return $this->hasOne(Animal::className(), ['identificacion' => 'animal_identificacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusIdStatus()
    {
        return $this->hasOne(Status::className(), ['id_status' => 'status_id_status']);
    }
}
