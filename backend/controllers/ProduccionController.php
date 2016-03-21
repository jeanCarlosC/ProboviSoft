<?php
namespace backend\controllers;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use backend\models\Animal;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Secado;
use backend\models\Ordeno;
use backend\models\ProduccionSearch;

/**
 * Site controller
 */
class ProduccionController extends Controller
{

    public function actionIndex()
    {
        $searchModel = new ProduccionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
   
    }


    public function actionView($id)
    {
        $model = Animal::find()->select(["LPAD(animal.identificacion, 6, '0') as identificacion_otro"," GROUP_CONCAT(CONCAT(raza_animal.porcentaje, '', '%'),' ', raza_animal.raza_id_raza ORDER BY raza_animal.porcentaje DESC SEPARATOR ' y ') as raza","animal.fecha_nacimiento","LPAD(animal.nuemro_arete,4,0) as arete","animal.imagen","animal.fecha_nacimiento","animal.sexo as sexo"," CONCAT(TIMESTAMPDIFF(MONTH,animal.fecha_nacimiento,CURDATE()), ' ','meses') as edad"])
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->where(['animal.identificacion'=>$id])->one();

       /* $model_ordeño = Ordeno::find()->where(['ordeno.animal_identificacion'=>$model->animal])
        ->join('JOIN','secado','secado.parto_id_parto = ordeno.parto_id_parto')
        ->one();*/
        return $this->render('view', [
            'model' => $model,
            /*'model_ordeño' => $model_ordeño,*/
        ]);
    }

    public function actionLactancia($id)
    {
        $model = Animal::find()->select(["LPAD(animal.identificacion, 6, '0') as identificacion_otro"," GROUP_CONCAT(CONCAT(raza_animal.porcentaje, '', '%'),' ', raza_animal.raza_id_raza ORDER BY raza_animal.porcentaje DESC SEPARATOR ' y ') as raza","animal.fecha_nacimiento","LPAD(animal.nuemro_arete,4,0) as arete","animal.imagen"])
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->where(['animal.identificacion'=>$id])->one();


       /* $model_ordeño = Ordeno::find()->where(['ordeno.animal_identificacion'=>$model->animal])
        ->join('JOIN','secado','secado.parto_id_parto = ordeno.parto_id_parto')
        ->one();*/
        return $this->render('lactancia', [
            'model' => $model,
            /*'model_ordeño' => $model_ordeño,*/
        ]);
    }


 /*   protected function findModel($id)
    {
        if (($model = Animal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }*/




}


?>