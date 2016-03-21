<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Servicio;
use yii\db\Query;

/**
 * ServicioSearch represents the model behind the search form about `backend\models\Servicio`.
 */
class ServicioSearch extends Servicio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_servicio', 'toro', 'animal_identificacion', 'semen_identificacion','semen','toro_otro'], 'integer'],
            [['fecha', 'tipo_servicio', 'inseminador'], 'safe'],
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
       /* $query = Servicio::find();*/
        $query = Servicio::find();
        $query->select(["id_servicio","fecha","LPAD(toro, 6, '0') as toro_otro","tipo_servicio","inseminador","LPAD(animal_identificacion, 6, '0') as animal_identificacion","LPAD(semen_identificacion, 6, '0') as semen"])
        ->from('servicio');

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
            'id_servicio' => $this->id_servicio,
            'fecha' => $this->fecha,
            'toro' => $this->toro,
            'animal_identificacion' => $this->animal_identificacion,
            'semen_identificacion' => $this->semen_identificacion,
        ]);

        $query->andFilterWhere(['like', 'tipo_servicio', $this->tipo_servicio])
            ->andFilterWhere(['like', 'inseminador', $this->inseminador])
            ->andFilterWhere(['like', 'toro', $this->toro_otro])
            ->andFilterWhere(['like','animal_identificacion', $this->animal_identificacion])
            ->andFilterWhere(['like','id_servicio', $this->id_servicio])
            ->andFilterWhere(['like','semen_identificacion', $this->semen]);

        return $dataProvider;
    }
}
