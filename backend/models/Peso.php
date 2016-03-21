<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "peso".
 *
 * @property integer $id_peso
 * @property string $fecha
 * @property integer $peso
 * @property integer $animal_identificacion
 * @property string $tipo_id_tipo
 *
 * @property Animal $animalIdentificacion
 * @property Tipo $tipoIdTipo
 */
class Peso extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $animal="";
    public $peso_nacimiento;
    public static function tableName()
    {
        return 'peso';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'animal_identificacion', 'tipo_id_tipo'], 'required'],
            [['fecha'], 'checkDate1'],
            [['animal_identificacion'], 'checkAnimal'],
            [['creado'],'safe'],
            [['peso', 'animal','peso_nacimiento'], 'integer'],
            [['tipo_id_tipo'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_peso' => 'Id Peso',
            'fecha' => 'Fecha',
            'peso' => 'Peso',
            'animal' => 'Animal',
            'peso_nacimiento'=>'peso al nacer',
            'animal_identificacion' => 'Animal Identificacion',
            'tipo_id_tipo' => 'Tipo de pesaje',
        ];
    }

        public function checkDate1($attribute, $params)
    {
     
        $pesaje = Peso::find()->where(['animal_identificacion'=>$this->animal_identificacion])
        ->orderBy(['fecha'=>SORT_DESC])
        ->one();

        if( strtotime('now')<strtotime($this->fecha))
        {

            $this->addError($attribute,'La fecha del pesaje no puede ser mayor a la fecha actual');

        }

        if($pesaje->fecha>$this->fecha)
        {
            $this->addError($attribute,'La fecha del pesaje no puede ser menor a la fecha del ultimo pesaje');
        }

    }

     public function checkAnimal($attribute, $params)
        {
           $animal = Animal::find()->where(['identificacion'=>$this->animal_identificacion])->one();
            if(empty($animal))
            {

                $this->addError($attribute,'el animal '.$this->animal_identificacion.' no existe');
            }
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
    public function getTipoIdTipo()
    {
        return $this->hasOne(Tipo::className(), ['id_tipo' => 'tipo_id_tipo']);
    }
}
