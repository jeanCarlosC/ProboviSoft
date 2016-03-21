<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "raza_animal".
 *
 * @property string $raza_id_raza
 * @property string $porcentaje
 * @property integer $animal_identificacion
 *
 * @property Raza $razaIdRaza
 * @property Animal $animalIdentificacion
 */
class RazaAnimal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'raza_animal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[/*'raza_id_raza',*/ 'porcentaje', 'animal_identificacion'], 'required'],
            [['animal_identificacion'], 'integer'],
            [['creado'], 'safe'],
            [['raza_id_raza'], 'string', 'max' => 1],
            [['porcentaje'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'raza_id_raza' => 'Raza',
            'porcentaje' => 'Porcentaje',
            'animal_identificacion' => 'Animal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRazaIdRaza()
    {
        return $this->hasOne(Raza::className(), ['id_raza' => 'raza_id_raza']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalIdentificacion()
    {
        return $this->hasOne(Animal::className(), ['identificacion' => 'animal_identificacion']);
    }
}
