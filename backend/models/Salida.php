<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "salida".
 *
 * @property integer $id_salida
 * @property integer $semen_identificacion
 * @property integer $pajuelas_salida
 * @property string $descripcion
 * @property string $creado
 *
 * @property Semen $semenIdentificacion
 */
class Salida extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'salida';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['semen_identificacion', 'pajuelas_salida', 'descripcion', 'fecha'], 'required'],
            [['semen_identificacion', 'pajuelas_salida'], 'integer'],
            [['descripcion'], 'string'],
            [['fecha'], 'checkDate'],
        ];
    }

            public function checkDate($attribute, $params)
    {

        if( strtotime('now')<strtotime($this->fecha))
        {

            $this->addError($attribute,'La fecha de salida de semen no puede ser mayor a la fecha actual');

        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_salida' => 'Id Salida',
            'semen_identificacion' => 'Semen Identificacion',
            'pajuelas_salida' => 'Pajuelas Salida',
            'descripcion' => 'Descripcion',
            'fecha' => 'fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemenIdentificacion()
    {
        return $this->hasOne(Semen::className(), ['identificacion' => 'semen_identificacion']);
    }
}
