<?php

namespace backend\controllers;

use Yii;
use backend\models\StatusEliminacion;
use backend\models\StatusEliminacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use backend\models\Animal;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;
use backend\models\Model;
/**
 * StatusEliminacionController implements the CRUD actions for StatusEliminacion model.
 */
class StatusEliminacionController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all StatusEliminacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        

        $searchModel = new StatusEliminacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $model = new StatusEliminacion();

        $animales1 = Animal::find()->all();
        $eliminados = StatusEliminacion::find()->all();
        $i=0;
         foreach ($animales1 as $key => $value) {
            $band=false;
           foreach ($eliminados as $key2 => $value2) 
           {
               if($value['identificacion'] == $value2['animal_identificacion'])
               {          
                   $band = true;
               }
           }
           if($band==false)
           {
            $animales[$i]['identificacion'] = $value['identificacion'];
            $i++;
/*            echo "<pre>";
            print_r($animales);
            echo "</pre>";
            yii::$app->end();*/
           }
        }

/*        echo "<pre>";
        print_r($animales);
        echo "</pre>";
        yii::$app->end();*/

        $query2 = new Query;
        $query2->select(["identificacion"])
        ->from('animal');

        $command = $query2->createCommand();
        $data_animales = $command->queryAll();

            $animalesT = new ArrayDataProvider([
            'key'=>'identificacion',
            'allModels' => (empty($animales)) ? array() : $animales,  
            'pagination' => [
            'pageSize' => 5,
            ],
            ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=> $model,
            'animales'=>$animalesT,
            'data'=> $data_animales,
            
        ]);
    }

    /**
     * Displays a single StatusEliminacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StatusEliminacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StatusEliminacion();

        $animal_1= Animal::find()->where(['sexo'=>'H'])->count();
        if(!($animal_1))
        {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe registrar previamente Animles para registrar una muerte',
            'title' => '¡NO existen Animales!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);

        }

        $query2 = new Query;
        $query2->select(["identificacion"])
        ->from('animal');
        $command = $query2->createCommand();
        $data_animales = $command->queryAll();

        if ($model->load(Yii::$app->request->post())) {

            $model->causa="M";
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'data' => $data_animales,
            ]);
        }
    }

    /**
     * Updates an existing StatusEliminacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $query2 = new Query;
        $query2->select(["identificacion"])
        ->from('animal');
        $command = $query2->createCommand();
        $data_animales = $command->queryAll();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'data' => $data_animales,
            ]);
        }
    }

    /**
     * Deletes an existing StatusEliminacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StatusEliminacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StatusEliminacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StatusEliminacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel2($id)
    {
        if (($model = StatusEliminacion::find()->where(['animal_identificacion'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('no se encontró un registro con ese animal');
        }
    }

    public function actionMuerte()
    {
        $model = new StatusEliminacion();

            if(Yii::$app->request->isAjax)
            {
                $model->attributes = $_POST['StatusEliminacion'];// asigna los valores al modelo

                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }
            
        if (Yii::$app->request->post()) {
            $model->attributes = $_POST['StatusEliminacion'];
            $model->status_id_status = 'I';
            $model->causa = 'M';
            $model->descripcion = "Animal inactivo por muerte";

/*            echo "<pre>";
            print_r($model);
            echo "</pre>";
            yii::$app->end();*/
            $model->save(false);



            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'success',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Animal Eliminado exitosamente',
            'title' => '¡Registro Exitoso!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);
            return $this->redirect(['index']);
        }
       
    }

    public function actionVenta()
    {
        
        $model_principal = new StatusEliminacion();

            if(Yii::$app->request->isAjax)
            {
                $model_principal->attributes = $_POST['StatusEliminacion'];// asigna los valores al modelo animal

                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model_principal);
            }

        if(Yii::$app->request->isAjax)
        {
            $model_principal->attributes = $_POST['StatusEliminacion'];// asigna los valores al modelo animal
            $model = Model::createMultiple(StatusEliminacion::classname());
            Model::loadMultiple($model, Yii::$app->request->post());
            /*Yii::$app->response->format = Response::FORMAT_JSON;*/
            Yii::$app->response->format = 'json';
            return ActiveForm::validateMultiple($model);    
        }

        if (Yii::$app->request->post()) 
        {
            $model_principal->attributes = $_POST['StatusEliminacion'];
            $keys = yii::$app->request->post('selection');
            
            foreach ($keys as $i => $value) {
            $model[$i] = new StatusEliminacion();
            $model[$i]->animal_identificacion = $value;
            $model[$i]->causa = 'V';
            $model[$i]->descripcion = $model_principal->descripcion;
            $model[$i]->fecha = $model_principal->fecha1;
            $model[$i]->status_id_status = 'I';
            $model[$i]->save(false);
               
        }
        Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'success',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Animales Eliminados exitosamente',
            'title' => '¡Registro Exitoso!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);
        }
       
    }
}
