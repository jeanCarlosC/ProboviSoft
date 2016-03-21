<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "raza_semen".
 *
 * @property string $raza_id_raza
 * @property integer $semen_identificacion
 * @property integer $porcentaje
 * @property string $creado
 *
 * @property Raza $razaIdRaza
 * @property Semen $semenIdentificacion
 */
class RazaSemen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'raza_semen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['raza_id_raza', 'semen_identificacion', 'porcentaje', 'creado'], 'required'],
            [['semen_identificacion', 'porcentaje'], 'integer'],
            [['creado'], 'safe'],
            [['raza_id_raza'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'raza_id_raza' => 'Raza Id Raza',
            'semen_identificacion' => 'Semen Identificacion',
            'porcentaje' => 'Porcentaje',
            'creado' => 'Creado',
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
    public function getSemenIdentificacion()
    {
        return $this->hasOne(Semen::className(), ['identificacion' => 'semen_identificacion']);
    }
}
