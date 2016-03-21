<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tipo".
 *
 * @property string $id_tipo
 * @property string $nombre
 *
 * @property Peso[] $pesos
 */
class Tipo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tipo', 'nombre'], 'required'],
            [['id_tipo'], 'string', 'max' => 2],
            [['nombre'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo' => 'Tipo',
            'nombre' => 'tipo nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPesos()
    {
        return $this->hasMany(Peso::className(), ['tipo_id_tipo' => 'id_tipo']);
    }
}
