<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Ordeno;

/**
 * OrdenoSearch represents the model behind the search form about `backend\models\Ordeno`.
 */
class OrdenoSearch extends Ordeno
{
    /**
     * @inheritdoc
     */
    public $identificacion;
    public $fecha;
    public function rules()
    {
        return [
            [['id_ordeno', 'pesaje', 'id_potrero', 'animal_identificacion','dias','acumulado','parto_id_parto'], 'integer'],
            [['fecha','pesaje_total','identificacion'], 'safe'],
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
        $query = Ordeno::find();
        $query->select(["animal_identificacion as identificacion","fecha","pesaje","id_ordeno","dias","animal_identificacion","parto_id_parto"])
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
            'id_ordeno' => $this->id_ordeno,
            'id_potrero' => $this->id_potrero,
            'parto_id_parto' => $this->parto_id_parto,
        ]);

        $query->andFilterWhere(['like', 'animal_identificacion', $this->identificacion])
        ->andFilterWhere(['like', 'fecha', $this->fecha])
        ->andFilterWhere(['like', 'pesaje', $this->pesaje]);

        return $dataProvider;
    }
}
