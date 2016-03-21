<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Aborto;

/**
 * AbortoSearch represents the model behind the search form about `backend\models\Aborto`.
 */
class AbortoSearch extends Aborto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_aborto', 'servicio_id_servicio', 'animal_identificacion'], 'integer'],
            [['fecha', 'observacion','sexo_feto','identificacion'], 'safe'],
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
        $query = Aborto::find();
        $query->select(["LPAD(animal_identificacion, 6, '0') as identificacion","fecha","observacion","id_aborto","sexo_feto"])
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
            'id_aborto' => $this->id_aborto,
            'fecha' => $this->fecha,
            'servicio_id_servicio' => $this->servicio_id_servicio,
            'animal_identificacion' => $this->animal_identificacion,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
