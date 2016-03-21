<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StatusAnimal;

/**
 * StatusAnimalSearch represents the model behind the search form about `backend\models\StatusAnimal`.
 */
class StatusAnimalSearch extends StatusAnimal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id_status', 'fecha'], 'safe'],
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
        $query = StatusAnimal::find();

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
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['like', 'status_id_status', $this->status_id_status]);

        return $dataProvider;
    }
}
