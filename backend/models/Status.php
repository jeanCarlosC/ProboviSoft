<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "status".
 *
 * @property string $id_status
 * @property string $status
 *
 * @property StatusAnimal[] $statusAnimals
 * @property Animal[] $animalIdentificacions
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_status', 'status'], 'required'],
            [['id_status'], 'string', 'max' => 1],
            [['status'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_status' => 'id status',
            'status' => 'status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusAnimals()
    {
        return $this->hasMany(StatusAnimal::className(), ['status_id_status' => 'id_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalIdentificacions()
    {
        return $this->hasMany(Animal::className(), ['identificacion' => 'animal_identificacion'])->viaTable('status_animal', ['status_id_status' => 'id_status']);
    }
}
