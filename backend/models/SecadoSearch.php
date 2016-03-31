<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Secado;

/**
 * SecadoSearch represents the model behind the search form about `backend\models\Secado`.
 */
class SecadoSearch extends Secado
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_secado', 'animal_identificacion', 'parto_id_parto'], 'integer'],
            [['fecha', 'creado','dias','Inicio_Lactancia','Fin_Lactancia','identificacion'], 'safe'],
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
        $query = Secado::find();
        $query->select(["secado.animal_identificacion as identificacion","parto.fecha as Inicio_Lactancia","secado.fecha as Fin_Lactancia","DATEDIFF(secado.fecha,parto.fecha) as dias","id_secado"])
        ->join('JOIN','parto','parto.id_parto = secado.parto_id_parto')
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
            'id_secado' => $this->id_secado,
            'fecha' => $this->fecha,
            'animal_identificacion' => $this->animal_identificacion,
            'parto_id_parto' => $this->parto_id_parto,
            'creado' => $this->creado,
        ]);

        return $dataProvider;
    }
}
