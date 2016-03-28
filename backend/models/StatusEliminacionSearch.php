<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StatusEliminacion;

/**
 * StatusEliminacionSearch represents the model behind the search form about `backend\models\StatusEliminacion`.
 */
class StatusEliminacionSearch extends StatusEliminacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'animal_identificacion'], 'integer'],
            [['status_id_status', 'fecha', 'causa'], 'safe'],
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
        $query = StatusEliminacion::find();

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
            'id' => $this->id,
            'animal_identificacion' => $this->animal_identificacion,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['like', 'status_id_status', $this->status_id_status])
            ->andFilterWhere(['like', 'causa', $this->causa]);

        return $dataProvider;
    }
}
