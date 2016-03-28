<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Animal;
use yii\db\Query;
use yii\data\ArrayDataProvider;

/**
 * AnimalSearch represents the model behind the search form about `backend\models\Animal`.
 */
class AnimalSearch2 extends Animal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identificacion', 'nuemro_arete', 'madre', 'padre','arete'], 'integer'],
            [['sexo', 'fecha_nacimiento','raza'], 'safe'],
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
        $animales2 = Animal::find();
        $animales2->select(["identificacion","madre","padre","fecha_nacimiento","nuemro_arete","sexo"])
        ->where(["sexo"=>"H"])
        ->orderBy(["fecha_nacimiento"=>SORT_DESC])
        ->all();

      $animales = Animal::find()->select(["identificacion","madre","padre","fecha_nacimiento","nuemro_arete","sexo"])
        ->where(["sexo"=>"H"])
        ->orderBy(["fecha_nacimiento"=>SORT_DESC])
        ->all();
        /*$animales_e[] = array();*/

  /*      echo "<pre>";
            print_r($animales);
            echo "</pre>";
            yii::$app->end();*/

        foreach ($animales as $key => $value) {

            

            $ultimo_servicio = Servicio::find()->where(["animal_identificacion"=>$value['identificacion']])
            ->orderBy(["id_servicio"=>SORT_DESC])
            ->one();

            

            if(!empty($ultimo_servicio)){
            $parto = Parto::find()->where(["servicio_id_servicio"=>$ultimo_servicio->id_servicio])
            ->one();

            if( (empty($parto)) )
            {
                $animales_e[] = array("identificacion"=>$value['identificacion']);
            }

            }
    
        }/*fin cilco*/

            $dataProvider = new ActiveDataProvider([
            'query' => $animales2,
            'key'=>'identificacion',
            'pagination' => [
            'pageSize' => 10,
            ],
        ]);

/*            $dataProvider = new ArrayDataProvider([
            'key'=>'identificacion',
            'allModels' => $animales_e,
            'pagination' => [
            'pageSize' => 10,
            ],
            ]);*/

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }



        $animales2->andFilterWhere([
            'madre' => $this->madre,
            'padre' => $this->padre,
        ]);

        $animales2->andFilterWhere(['like', 'sexo', $this->sexo])
        ->andFilterWhere(['like','identificacion', $this->identificacion]);

        return $dataProvider;
    }
}
