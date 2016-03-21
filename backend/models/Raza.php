<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "raza".
 *
 * @property string $id_raza
 * @property string $raza
 *
 * @property RazaAnimal[] $razaAnimals
 * @property Animal[] $animalIdentificacions
 */
class Raza extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'raza';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_raza', 'raza'], 'required'],
            [['id_raza'], 'string', 'max' => 2],
            [['raza'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_raza' => 'Id Raza',
            'raza' => 'Raza',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRazaAnimals()
    {
        return $this->hasMany(RazaAnimal::className(), ['raza_id_raza' => 'id_raza']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalIdentificacions()
    {
        return $this->hasMany(Animal::className(), ['identificacion' => 'animal_identificacion'])->viaTable('raza_animal', ['raza_id_raza' => 'id_raza']);
    }
}
