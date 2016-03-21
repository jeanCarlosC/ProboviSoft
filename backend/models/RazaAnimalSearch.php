<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RazaAnimal;

/**
 * RazaAnimalSearch represents the model behind the search form about `backend\models\RazaAnimal`.
 */
class RazaAnimalSearch extends RazaAnimal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['raza_id_raza', 'porcentaje'], 'safe'],
            [['animal_identificacion'], 'integer'],
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
        $query = RazaAnimal::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'animal_identificacion' => $this->animal_identificacion,
        ]);

        $query->andFilterWhere(['like', 'raza_id_raza', $this->raza_id_raza])
            ->andFilterWhere(['like', 'porcentaje', $this->porcentaje]);

        return $dataProvider;
    }
}
