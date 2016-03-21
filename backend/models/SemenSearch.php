<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Semen;
use yii\db\Query;

/**
 * SemenSearch represents the model behind the search form about `backend\models\Semen`.
 */
class SemenSearch extends Semen
{
    
    /**
     * @inheritdoc
     */
    public $identificacion_otro;
    public function rules()
    {
        return [
            [['identificacion','termo_identificacion','canastilla_identificacion', 'madre', 'padre', 'pajuelas','identificacion_otro'], 'integer'],
            [['raza'],'safe'],
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
        $query = new Query;
        $query->select(["termo_identificacion","canastilla_identificacion","LPAD(identificacion, 6, '0') as identificacion_otro","GROUP_CONCAT(raza_semen.raza_id_raza, CONCAT(raza_semen.porcentaje, ' ', '%') SEPARATOR ' y ') as raza","pajuelas"])
        ->from('semen')
        ->join('JOIN','raza_semen','raza_semen.semen_identificacion = semen.identificacion')
        ->groupBy('identificacion_otro')
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
            'madre' => $this->madre,
            'padre' => $this->padre,
            'pajuelas' => $this->pajuelas,
        ]);

        $query->andFilterWhere(['like','identificacion', $this->identificacion_otro])
        ->andFilterWhere(['like','termo_identificacion', $this->termo_identificacion])
        ->andFilterWhere(['like','canastilla_identificacion', $this->canastilla_identificacion]);

        return $dataProvider;
    }
}
