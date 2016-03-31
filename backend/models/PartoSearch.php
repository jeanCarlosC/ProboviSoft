<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Parto;

/**
 * PartoSearch represents the model behind the search form about `backend\models\Parto`.
 */
class PartoSearch extends Parto
{
    /**
     * @inheritdoc
     */
    public $identificacion_otro;
    public $becerro;
    public function rules()
    {
        return [
            [['id_parto', 'cod_becerro', 'peso_becerro', 'servicio_id_servicio', 'animal_identificacion','identificacion_otro','becerro'], 'integer'],
            [['fecha', 'sexo_becerro'], 'safe'],
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
        $query = Parto::find();
        $query->select(["animal_identificacion","fecha","cod_becerro","sexo_becerro","id_parto"])
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
            'id_parto' => $this->id_parto,
            'fecha' => $this->fecha,
            'sexo_becerro'=> $this->sexo_becerro,
        ]);

        $query->andFilterWhere(['like', 'sexo_becerro', $this->sexo_becerro])
        ->andFilterWhere(['like', 'cod_becerro', $this->becerro])
        ->andFilterWhere(['like', 'animal_identificacion', $this->identificacion_otro]);

        return $dataProvider;
    }
}
