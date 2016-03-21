<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "animal".
 *
 * @property integer $identificacion
 * @property string $sexo
 * @property string $fecha_nacimiento
 * @property integer $nuemro_arete
 * @property integer $madre
 * @property integer $padre
 *
 * @property Aborto[] $abortos
 * @property Diagnostico[] $diagnosticos
 * @property Parto[] $partos
 * @property Peso[] $pesos
 * @property RazaAnimal[] $razaAnimals
 * @property Raza[] $razaIdRazas
 * @property Servicio[] $servicios
 * @property StatusAnimal[] $statusAnimals
 * @property Status[] $statusIdStatuses
 */
class Animal extends \yii\db\ActiveRecord
{

    public $raza="";
    public $raza_animal="";
    public $identificacion_otro="";
    public $avatar;
    public $edad;
    public $arete;
    public $madre_1;
    public $padre_1;
    public $estado;
    public $hijosp;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'animal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identificacion', 'sexo', 'fecha_nacimiento', 'nuemro_arete'], 'required'],
            [['identificacion', 'nuemro_arete', 'madre', 'padre','identificacion_otro'], 'integer'],
            [['avatar'], 'file'],
            [['fecha_nacimiento','creado','modificado','raza'], 'safe'],
            [['identificacion'],'integer'],
            [['raza_animal'],'string'],
            [['nombre', 'imagen'], 'string', 'max' => 100],
            [['sexo'], 'string', 'max' => 1],
            [['fecha_nacimiento'],'checkDate'],
        ];
    }

        public function checkDate($attribute, $params)
        {

            if( strtotime('now')<strtotime($this->fecha_nacimiento))
            {

                $this->addError($attribute,'La fecha de nacimiento no puede ser mayor a la fecha actual');
            }

        }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'identificacion' => 'Identificación',
            'sexo' => 'Sexo',
            'fecha_nacimiento' => 'Fecha de nacimiento',
            'nuemro_arete' => 'Número o arete',
            'madre' => 'Madre',
            'padre' => 'Padre',
            'raza_animal'=> 'Raza',
            'identificacion_otro'=> 'identificación',
            'avatar'=> 'Imagen del animal',
            'edad' => 'Edad',
            'madre_1'=>'Madre',
            'padre_1'=>'Padre',
            'hijosp'=>'Hijos',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAbortos()
    {
        return $this->hasMany(Aborto::className(), ['animal_identificacion' => 'identificacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiagnosticos()
    {
        return $this->hasMany(Diagnostico::className(), ['animal_identificacion' => 'identificacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartos()
    {
        return $this->hasMany(Parto::className(), ['animal_identificacion' => 'identificacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPesos()
    {
        return $this->hasMany(Peso::className(), ['animal_identificacion' => 'identificacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRazas()
    {
        return RazaAnimal::find()->where(['animal_identificacion'=>$this->identificacion])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRazaIdRazas()
    {
        $razas = RazaAnimal::find()->where(['animal_identificacion'=>$this->identificacion])->all();
        return $razas;
        /*return $this->hasMany(Raza::className(), ['id_raza' => 'raza_id_raza'])->viaTable('raza_animal', ['animal_identificacion' => 'identificacion']);*/
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicio::className(), ['animal_identificacion' => 'identificacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusAnimals()
    {
        return $this->hasMany(StatusAnimal::className(), ['animal_identificacion' => 'identificacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusIdStatuses()
    {
        return $this->hasMany(Status::className(), ['id_status' => 'status_id_status'])->viaTable('status_animal', ['animal_identificacion' => 'identificacion']);
    }

    public function getEstados()
    {
        if($this->edad <= 9 && $this->sexo=='H')
        return 'Becerra';

        if($this->edad <= 9 && $this->sexo=='M')
        return 'Becerro';

        if($this->edad>=10 && $this->edad<=30 && $this->sexo=='H')
        return 'Novilla';

        if($this->edad>=10 && $this->edad<=20 && $this->sexo=='M')
        return 'Novillo';

        if($this->edad>20 && $this->sexo=='M')
        return 'Toro';

        if($this->edad>30 && $this->sexo=='H')
        return 'Vaca';
    }

    public function getHijos_padre()
    {
        $hijos = Animal::find()->where(['padre'=>$this->identificacion_otro])->count();
        return $hijos;
    }

    public function getRaza_concatenada()
    {
        $query = Animal::find();
        $query->select([" GROUP_CONCAT(CONCAT(raza_animal.porcentaje, '', '%'),' ', raza_animal.raza_id_raza ORDER BY raza_animal.porcentaje DESC SEPARATOR ' y ') as raza"])
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->where(['animal_identificacion'=>$this->identificacion_otro])
        ->groupBy('raza_animal.animal_identificacion')
        ->orderBy('raza_animal.porcentaje')
        ->all();
        return $query;
    }

}
