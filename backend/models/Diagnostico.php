<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "diagnostico".
 *
 * @property integer $id_diagnostico
 * @property string $fecha
 * @property string $diagnostico_prenez
 * @property integer $dias_gestacion
 * @property string $ovario_izq
 * @property string $ovario_der
 * @property string $utero
 * @property string $observacion
 * @property integer $servicio_id_servicio
 * @property integer $animal_identificacion
 *
 * @property Servicio $servicioIdServicio
 * @property Animal $animalIdentificacion
 */
class Diagnostico extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

 public $animal_identificacion_2;
 public $animal;
 public $toro_monta;
 
    public static function tableName()
    {
        return 'diagnostico';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'diagnostico_prenez', 'servicio_id_servicio', 'animal_identificacion'], 'required'],
            [['fecha','parto_esperado'], 'safe'],
            [['fecha'], 'checkDate'],
            [['animal_identificacion'],'checkAnimal'],
            [['creado'],'safe'],
            [['dias_gestacion', 'servicio_id_servicio', 'animal_identificacion','animal_identificacion_2'], 'integer'],
            [['diagnostico_prenez'], 'string', 'max' => 1],
            [['ovario_izq', 'ovario_der', 'utero', 'observacion'], 'required','on'=>'vacia'],
            [['dias_gestacion'],'required','on'=>'preñada'],
            [['ovario_izq', 'ovario_der', 'utero', 'observacion'], 'string'],
            /*[['dias_gestacion','ovario_izq', 'ovario_der', 'utero', 'observacion'],'default', 'value' => null],*/
            [['servicio_id_servicio'], 'unique']
        ];
    }

     public function checkDate($attribute, $params)
        {

            if( strtotime('now')<strtotime($this->fecha))
            {

                $this->addError($attribute,'La fecha del diagnostico no puede ser mayor a la fecha actual');
            }
        }

        public function checkAnimal($attribute, $params)
        {
           $animal = Animal::find()->where(['identificacion'=>$this->animal_identificacion])->one();
            if(empty($animal))
            {

                $this->addError($attribute,'el animal '.$this->animal_identificacion.' no existe');
            }

            elseif($animal->sexo=='M')
            {
                $this->addError($attribute,'el animal '.$this->animal_identificacion.' no es una hembra');
            }

        }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_diagnostico' => 'diagnostico',
            'fecha' => 'Fecha diagnostico',
            'diagnostico_prenez' => 'Diagnostico preñez',
            'dias_gestacion' => 'Dias gestacion',
            'ovario_izq' => 'Ovario izquierdo',
            'ovario_der' => 'Ovario derecho',
            'utero' => 'Utero',
            'observacion' => 'Observación',
            'servicio_id_servicio' => 'Servicio',
            'animal_identificacion' => 'Animal',
            'parto_esperado' => 'Fecha parto',
            'animal_identificacion_2' => 'Animal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicioIdServicio()
    {
        return $this->hasOne(Servicio::className(), ['id_servicio' => 'servicio_id_servicio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnimalIdentificacion()
    {
        return $this->hasOne(Animal::className(), ['identificacion' => 'animal_identificacion']);
    }
}
