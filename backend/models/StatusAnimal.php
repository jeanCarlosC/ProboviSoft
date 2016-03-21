<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "status_animal".
 *
 * @property string $status_id_status
 * @property integer $animal_identificacion
 * @property string $fecha
 *
 * @property Status $statusIdStatus
 * @property Animal $animalIdentificacion
 */
class StatusAnimal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $identificacion_otro;
    public static function tableName()
    {
        return 'status_animal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id_status', 'animal_identificacion', 'fecha'], 'required'],
            [['animal_identificacion','identificacion_otro'], 'integer'],
            [['creado'],'safe'],
            [['fecha'], 'safe'],
            [['status_id_status'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status_id_status' => 'Status',
            'animal_identificacion' => 'Animal',
            'fecha' => 'Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusIdStatus()
    {
        return $this->hasOne(Status::className(), ['id_status' => 'status_id_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalIdentificacion()
    {
        return $this->hasOne(Animal::className(), ['identificacion' => 'animal_identificacion']);
    }
}
