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
            [['status_id_status', 'animal_identificacion', 'fecha', 'causa'], 'required'],
            [['animal_identificacion'], 'integer'],
            [['fecha'], 'safe'],
            [['status_id_status'], 'string', 'max' => 1],
            [['causa'], 'string', 'max' => 45],
            [['animal_identificacion'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_id_status' => 'Status Id Status',
            'animal_identificacion' => 'Animal Identificacion',
            'fecha' => 'Fecha',
            'causa' => 'Causa',
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
