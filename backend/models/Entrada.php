<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "entrada".
 *
 * @property integer $id_entrada
 * @property integer $semen_identificacion
 * @property integer $pajuelas_entrada
 * @property string $descripcion
 * @property string $fecha
 *
 * @property Semen $semenIdentificacion
 */
class Entrada extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entrada';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['semen_identificacion', 'pajuelas_entrada', 'descripcion', 'fecha'], 'required'],
            [['semen_identificacion', 'pajuelas_entrada'], 'integer'],
            [['descripcion'], 'string'],
            [['fecha'], 'checkDate']
        ];
    }

    /**
     * @inheritdoc
     */

    public function checkDate($attribute, $params)
        {

            if( strtotime('now')<strtotime($this->fecha))
            {

                $this->addError($attribute,'La fecha de entrada del semen no puede ser mayor a la fecha actual');
            }

        }

    public function attributeLabels()
    {
        return [
            'id_entrada' => 'Id Entrada',
            'semen_identificacion' => 'Semen Identificacion',
            'pajuelas_entrada' => 'Pajuelas Entrada',
            'descripcion' => 'Descripcion',
            'fecha' => 'Fecha',
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
