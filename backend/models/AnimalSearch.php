<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Animal;
use yii\db\Query;

/**
 * AnimalSearch represents the model behind the search form about `backend\models\Animal`.
 */
class AnimalSearch extends Animal
{
    /**
     * @inheritdoc
     */
    public $identificacion_otro;
    public $arete;
    public function rules()
    {
        return [
            [['identificacion', 'nuemro_arete', 'madre', 'padre','identificacion_otro','arete'], 'integer'],
            [['sexo', 'fecha_nacimiento','raza'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
 /*       $query = new Query;
        $query->select(["LPAD(animal.identificacion, 6, '0') as identificacion","animal.sexo"," GROUP_CONCAT(raza_animal.raza_id_raza, CONCAT(raza_animal.porcentaje, ' ', '%') SEPARATOR ' y ') as raza","animal.fecha_nacimiento","animal.nuemro_arete","LPAD(animal.madre, 6, '0') as madre","LPAD(animal.padre, 6, '0') as padre"])
        ->from('animal')
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->groupBy('animal.identificacion')
        ->all();*/

        $query = Animal::find();
        $query->select(["LPAD(animal.identificacion, 6, '0') as identificacion_otro","animal.sexo as sexo"," GROUP_CONCAT(CONCAT(raza_animal.porcentaje, '', '%'),' ', raza_animal.raza_id_raza ORDER BY raza_animal.porcentaje DESC SEPARATOR ' y ') as raza","animal.fecha_nacimiento","LPAD(animal.nuemro_arete,4,0) as arete","LPAD(animal.madre, 6, '0') as madre","LPAD(animal.padre, 6, '0') as padre","identificacion"])
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->groupBy('identificacion_otro')
        ->orderBy('raza_animal.porcentaje')
        ->all();
        
        /*$query=Animal::find()->select(["LPAD(identificacion, 6, '0') as identificacion","sexo","fecha_nacimiento","nuemro_arete","LPAD(madre, 6, '0') as madre","LPAD(padre, 6, '0') as padre"]);*/


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }



        $query->andFilterWhere([
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'madre' => $this->madre,
            'padre' => $this->padre,
        ]);

        $query->andFilterWhere(['like', 'sexo', $this->sexo])
        ->andFilterWhere(['like','identificacion', $this->identificacion])
        ->andFilterWhere(['like','sexo', $this->sexo])
        ->andFilterWhere(['like','nuemro_arete', $this->arete]);

        return $dataProvider;
    }
}
