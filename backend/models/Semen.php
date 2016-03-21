<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "semen".
 *
 * @property integer $identificacion
 * @property integer $madre
 * @property integer $padre
 * @property integer $pajuelas
 * @property integer $entrada
 * @property integer $salida
 *
 * @property Servicio[] $servicios
 */
class Semen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $raza="";
    public $identificacion_otro;
    public $hijos=0;
    public $hembras=0;
    public $machos=0;
    public $avatar;
    public static function tableName()
    {
        return 'semen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identificacion', 'pajuelas','termo_identificacion','canastilla_identificacion'], 'required'],
            ['avatar', 'file'],
            [['identificacion', 'madre', 'padre', 'pajuelas','termo_identificacion','canastilla_identificacion','identificacion_otro'], 'integer'],
            /*[['identificacion'], 'unique'],*/
            [['imagen'],'string', 'max' => 100],
            [['termo_identificacion','canastilla_identificacion'], 'integer', 'min'=>1, 'max'=>6],
            [['creado','raza'],'safe'],
            [['identificacion'], 'integer', 'min'=>1, 'max'=>999999],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'identificacion' => 'Toro identificación',
            'madre' => 'Madre',
            'padre' => 'Padre',
            'pajuelas' => 'Cantidad de Pajuelas',
            'salida' => 'Salida de pajuelas',
            'termo_identificacion'=>'Termo',
            'canastilla_identificacion'=>'Canastilla',
            'identificacion_otro'=>'Identificacion',
            'avatar'=> 'Imagen del animal',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicio::className(), ['semen_identificacion' => 'identificacion']);
    }

    public function getHijoos()
    {
        $hijo = Animal::find()->where(['padre' => $this->identificacion_otro])->count();

        if($hijo)
        {
            return $hijo;
        }
        else
        {
            return 0;
        }
    }
    public function getHembraas()
    {
        $hembra = Animal::find()->where(['padre' => $this->identificacion_otro])->andWhere(['sexo'=>'H'])->count();

        if($hembra)
        {
            return $hembra;
        }
        else
        {
            return 0;
        }
    }
    public function getMachoos()
    {
        $macho = Animal::find()->where(['padre' => $this->identificacion_otro])->andWhere(['sexo'=>'M'])->count();

        if($macho)
        {
            return $macho;
        }
        else
        {
            return 0;
        }
    }
    public function getRazaIdRazas()
    {
        $razas = RazaSemen::find()->where(['semen_identificacion'=>$this->identificacion])->all();
        return $razas;
        /*return $this->hasMany(Raza::className(), ['id_raza' => 'raza_id_raza'])->viaTable('raza_animal', ['animal_identificacion' => 'identificacion']);*/
    }
}
