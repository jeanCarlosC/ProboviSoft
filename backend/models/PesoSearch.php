<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Peso;
use yii\db\Query;

/**
 * PesoSearch represents the model behind the search form about `backend\models\Peso`.
 */
class PesoSearch extends Peso
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_peso', 'peso', 'animal_identificacion','animal'], 'integer'],
            [['fecha', 'tipo_id_tipo'], 'safe'],
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
        $query = Peso::find();
        $query->select(["LPAD(animal_identificacion, 6, '0') as animal","peso","tipo_id_tipo","fecha","id_peso","animal_identificacion"])
        ->from('peso')
        ->all();

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
            'id_peso' => $this->id_peso,
            'fecha' => $this->fecha,
            'peso' => $this->peso,
        ]);

        $query->andFilterWhere(['like', 'tipo_id_tipo', $this->tipo_id_tipo])
        ->andFilterWhere(['like', 'animal_identificacion', $this->animal_identificacion]);

        return $dataProvider;
    }
}
