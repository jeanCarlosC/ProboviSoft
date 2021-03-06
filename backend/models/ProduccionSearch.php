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
class ProduccionSearch extends Animal
{
    /**
     * @inheritdoc
     */
    
    public $arete;
    public $PAL;
    public $DL;
    public $Lac;
    public $PLL;
    public $PLD;
    public function rules()
    {
        return [
            [['identificacion', 'nuemro_arete', 'madre', 'padre','arete'], 'integer'],
            [['sexo', 'fecha_nacimiento','raza','PAL','DL','Lac','PLL','PLD'], 'safe'],
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
        $query->select(["identificacion","animal.sexo as sexo","animal.fecha_nacimiento","animal.nuemro_arete as arete","madre","padre"])
        ->join('JOIN','status_animal','status_animal.animal_identificacion = animal.identificacion')
        ->groupBy('animal_identificacion')
        ->all();
        
        /*$query=Animal::find()->select(["LPAD(identificacion, 6, '0') as identificacion","sexo","fecha_nacimiento","nuemro_arete","LPAD(madre, 6, '0') as madre","LPAD(padre, 6, '0') as padre"]);*/


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 10,
            ],
        ]);

/*
        $dataProvider->setSort([
        'attributes'=>[
            'identificacion',
            'Lac',
        ]
    ]);*/

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
        ->andFilterWhere(['like','nuemro_arete', $this->arete])
        /*->andFilterWhere(['like','raza', $this->raza])*/;

        return $dataProvider;
    }
}
