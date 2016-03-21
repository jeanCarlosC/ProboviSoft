<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Raza;

/**
 * RazaSearch represents the model behind the search form about `backend\models\Raza`.
 */
class RazaSearch extends Raza
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_raza', 'raza'], 'safe'],
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
        $query = Raza::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'id_raza', $this->id_raza])
            ->andFilterWhere(['like', 'raza', $this->raza]);

        return $dataProvider;
    }
}
