<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "servicio".
 *
 * @property integer $id_servicio
 * @property string $fecha
 * @property integer $toro
 * @property string $tipo_servicio
 * @property string $inseminador
 * @property integer $animal_identificacion
 * @property integer $semen_identificacion
 *
 * @property Aborto $aborto
 * @property Diagnostico $diagnostico
 * @property Parto $parto
 * @property Animal $animalIdentificacion
 * @property Semen $semenIdentificacion
 */
class Servicio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $semen;
    public $toro_otro;
    public $animal;
    public $toro_monta;
    public $animales;

    public static function tableName()
    {
        return 'servicio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha','tipo_servicio', 'animal_identificacion','animales'], 'required'],
            [['fecha'], 'checkDate'],
            [['inseminador','semen_identificacion'],'required','on'=>'inseminacion'],
            [['toro'],'required','on'=>'monta'],
            [['toro', 'animal_identificacion', 'semen_identificacion','semen','toro_otro'], 'integer'],
            ['inseminador', 'string'],
            [['semen_identificacion'], 'checkSemen'],
            [['creado'],'safe'],
        ];
    }

     public function checkDate($attribute, $params)
        {

            if( strtotime('now')<strtotime($this->fecha))
            {

                $this->addError($attribute,'La fecha del servicio no puede ser mayor a la fecha actual');
            }
        }

     public function checkAnimal($attribute, $params)
        {
           $animal = Animal::find()->where(['identificacion'=>$this->animal_identificacion])->count();
           $status = StatusEliminacion::find()->where(['animal_identificacion'=>$this->animal_identificacion])->one();

            if($animal<1)
            {

                $this->addError($attribute,'el animal '.$this->animal_identificacion.' no existe');
            }
            
            if(!empty($status))
            {

                $this->addError($attribute,'el animal '.$this->animal_identificacion.' no se encuentra activo');
            }

        }

         public function checkSemen($attribute, $params)
        {
           $semen = Semen::find()->where(['identificacion'=>$this->semen_identificacion])->one();
            if(empty($semen))
            {

                $this->addError($attribute,'el toro '.$this->semen_identificacion.' no existe');
            }

            if($semen->pajuelas==0)
            {
                $this->addError($attribute,'el toro '.$this->semen_identificacion.' ya no posee pajuelas disponibles');
            }

        }

        public function checkToro($attribute, $params)
        {
           $Toro = Animal::find()->where(['identificacion'=>$this->semen_identificacion])->count();
            if($Toro<1)
            {

                $this->addError($attribute,'el toro '.$this->toro.' no existe');
            }

        }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_servicio' => 'servicio',
            'fecha' => 'Fecha de servicio',
            'tipo_servicio' => 'Tipo de servicio',
            'inseminador' => 'Inseminador',
            'animal_identificacion' => 'Animal',
            'semen_identificacion' => 'Semen',
            'toro_otro' => 'Toro',
            'animales'=> 'Hembras a servir',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAborto()
    {
        return $this->hasOne(Aborto::className(), ['servicio_id_servicio' => 'id_servicio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnostico()
    {
        return $this->hasOne(Diagnostico::className(), ['servicio_id_servicio' => 'id_servicio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParto()
    {
        return $this->hasOne(Parto::className(), ['servicio_id_servicio' => 'id_servicio']);
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
    public function getSemenIdentificacion()
    {
        return $this->hasOne(Semen::className(), ['identificacion' => 'semen_identificacion']);
    }
}
