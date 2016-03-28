<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Diagnostico;
use yii\db\Query;

/**
 * DiagnosticoSearch represents the model behind the search form about `backend\models\Diagnostico`.
 */
class DiagnosticoSearch extends Diagnostico
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_diagnostico', 'dias_gestacion', 'servicio_id_servicio', 'animal_identificacion'], 'integer'],
            [['fecha', 'diagnostico_prenez', 'ovario_izq', 'ovario_der', 'utero', 'observacion','parto_esperado'], 'safe'],
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
        /*$query = new Query;
        $query->select(["LPAD(animal_identificacion, 6, '0') as animal_identificacion","diagnostico_prenez","dias_gestacion","fecha","parto_esperado"])
        ->from('diagnostico')
        ->all();*/

        $query = Diagnostico::find();
        $query->select(["animal_identificacion","diagnostico_prenez","dias_gestacion","fecha","parto_esperado","id_diagnostico"])
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
            'id_diagnostico' => $this->id_diagnostico,
            'fecha' => $this->fecha,
            'dias_gestacion' => $this->dias_gestacion,
            'servicio_id_servicio' => $this->servicio_id_servicio,
            
        ]);

        $query->andFilterWhere(['like', 'diagnostico_prenez', $this->diagnostico_prenez])
            ->andFilterWhere(['like', 'animal_identificacion', $this->animal_identificacion])
            ->andFilterWhere(['like', 'ovario_izq', $this->ovario_izq])
            ->andFilterWhere(['like', 'ovario_der', $this->ovario_der])
            ->andFilterWhere(['like', 'utero', $this->utero])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
