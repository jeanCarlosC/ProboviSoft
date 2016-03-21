<?php

namespace backend\controllers;

use Yii;
use backend\models\Ordeno;
use backend\models\OrdenoSearch;
use backend\models\Animal;
use backend\models\Parto;
use backend\models\ModelP;
use backend\models\Model;
use backend\models\StatusAnimal;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

/**
 * OrdenoController implements the CRUD actions for Ordeno model.
 */
class OrdenoController extends Controller
{
    public function behaviors()
    {
        return [

                    'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','create','view','update','delete','pesajes','fecha'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Ordeno models.
     * @return mixed
     */
    public function actionFecha()
    {
        $model = new Ordeno();
        $parto = Parto::find()->orderBy(['fecha'=>SORT_DESC])->one();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Ordeno'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if (Yii::$app->request->post()) 
        {
            /*if()
            {

            }*/
            return $this->redirect(['pesajes', 'f' => $_POST['Ordeno']['fecha'], 'p' => $_POST['Ordeno']['id_potrero']]);

        }

    }
    public function actionGuardar()
    {

    }


    public function actionPesajes($f,$p)
    {
    $i=0;    
    $animal = Animal::find()->all();
    foreach ($animal as $key => $value) {
     $ordeño = StatusAnimal::find()->select(["animal_identificacion","status_id_status","animal_identificacion"])->where(['animal_identificacion'=>$value['identificacion']])->orderBy(['fecha'=>SORT_DESC])->one();

     if(!empty($ordeño))
     {

        if($ordeño->status_id_status=='O')
        {
            $parto = Parto::find()->where(['animal_identificacion'=>$ordeño->animal_identificacion])
            ->orderBy(['fecha'=>SORT_DESC])
            ->one();
            $pesaje = Ordeno::find()->where(['parto_id_parto'=>$parto->id_parto])
            ->orderBy(['fecha'=>SORT_DESC])
            ->one();
            if($pesaje)
            {
            $interval1 = date_diff(date_create($pesaje->fecha), date_create($f));
            $dias_pe = (int)$interval1->format('%R%a');
            $dias_1=$dias_pe;

            }
            else
            {
            $interval2 = date_diff(date_create($parto->fecha), date_create($f));
            $dias_pa = (int)$interval2->format('%R%a');
            $dias_1=$dias_pa-5;

            }
            

            $model[$i] = new Ordeno();
            $model[$i]->fecha = $f;
            $model[$i]->animal_identificacion=$ordeño->identificacion_otro;
            $animales[$i] = array('animal_identificacion'=>$ordeño->animal_identificacion,'pesaje'=>'','fecha'=>$f,'potrero'=>$p,'dias'=>$dias_1,'parto'=>$parto->id_parto);
            $i++;
        }
     }
    }
/*    echo "<pre>";
    print_r($model);
    print_r($ordeño->identificacion);
    echo "</pre>";
    yii::$app->end();*/
    $dataProvider = new ArrayDataProvider([
        'allModels'=>$animales,
    ]);


if (Yii::$app->request->post()) 
        {

            $model = Model::createMultiple(Ordeno::classname());
            Model::loadMultiple($model, Yii::$app->request->post());
/*            echo "<pre>";
            print_r($model);
            echo "</pre>";
            yii::$app->end();*/
            foreach ($model as $i => $model1) {


                $model1->fecha = $animales[$i]['fecha'];
                
                $model1->animal_identificacion = $animales[$i]['animal_identificacion'];
                $model1->pesaje = $model1->turno_manana+$model1->turno_tarde;
                $model1->id_potrero = $animales[$i]['potrero'];

               
                $model1->parto_id_parto = $animales[$i]['parto'];
                $model1->dias = $animales[$i]['dias'];

            

            
                

                $model1->save(false);
            

            }
                /*echo "<pre>";
                print_r($model);
                echo "</pre>";
                yii::$app->end();*/
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'success',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Pesajes Registrados exitosamente',
            'title' => '¡Registro Exitoso!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);

                            /*echo "<pre>";
                            print_r($model);
                            echo "</pre>";
                            yii::$app->end();*/

        }
        else
        {


        
        return $this->render('pesajes', [
            'dataProvider' => $animales,
            'model'=>(empty($model)) ? [new Ordeno] : $model,
        ]);

        }
    }

    public function actionIndex()
    {
        $searchModel = new OrdenoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new ordeno();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model
        ]);
    }

    /**
     * Displays a single Ordeno model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Ordeno::find()->where(['id_ordeno'=>$id])->one();

        $parto = Parto::find()->where(['id_parto'=>$model->parto_id_parto])->one();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'parto'=> $parto,
        ]);
    }

    /**
     * Creates a new Ordeno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ordeno();

        $Partos= Parto::find()->count();

             if($Partos<1)
             {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe registrar Partos previamente para registrar un Pesaje de Leche.',
            'title' => '¡NO existen PARTOS!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);

             }


        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Ordeno'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }


        if ($model->load(Yii::$app->request->post())) {

            $val_animal = Animal::find()
            ->where(['identificacion'=>$_POST['Ordeno']['animal_identificacion']])
            ->one();

            $parto = Parto::find()
            ->where(['animal_identificacion'=>$_POST['Ordeno']['animal_identificacion']])
            ->orderBy(['fecha'=>SORT_DESC])
            ->one();

/****************************************************ANIMAL NO EXISTE*******************************************************/
                if(!($val_animal))
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Ordeno']['animal_identificacion'].' no existe',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 
                }

             $validacion_status = StatusAnimal::find()
            ->where(['animal_identificacion'=>$_POST['Ordeno']['animal_identificacion']])
            ->orderBy(['fecha'=>SORT_DESC])
            ->one();

/******************************************* VACA/NOVILLA  NO ESTA EN ORDEÑO*******************************************************/
            if( !($validacion_status) || $validacion_status->status_id_status != 'O')
            {
   
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Ordeno']['animal_identificacion'].' no está en Ordeño',
                    'title' => '¡Error de fecha!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']);
            }
            $pesaje = Ordeno::find()
            ->where(['animal_identificacion'=>$_POST['Ordeno']['animal_identificacion']])
            ->where(['parto_id_parto'=>$parto->id_parto])
            ->orderBy(['fecha'=>SORT_DESC])
            ->one();



            if($pesaje)
            {
                //$model->pesaje_anterior = $pesaje->fecha;

                $interval1 = date_diff(date_create($pesaje->fecha), date_create($_POST['Ordeno']['fecha']));
                $dias_pe = (int)$interval1->format('%R%a');
                if($dias_pe<1)
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la fecha del pesaje no es mayor a la fecha del ultimo pesaje',
                    'title' => '¡Error de fecha!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']);
                }
                $model->dias=$dias_pe;
                //$model->pesaje_total=($dias_pe*(float)$_POST['Ordeno']['pesaje'])+$pesaje->pesaje_total;

            }
            else
            {

                $interval2 = date_diff(date_create($parto->fecha), date_create($_POST['Ordeno']['fecha']));
                $dias_pa = (int)$interval2->format('%R%a');
                if($dias_pa<1)
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la fecha del pesaje no es mayor a la fecha del parto',
                    'title' => '¡Error de fecha!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']);
                }
                $model->dias=$dias_pa;
                //$model->pesaje_total=($dias_pa*(float)$_POST['Ordeno']['pesaje']);



            }
            $model->parto_id_parto = $parto->id_parto;
            $model->creado = date('Y-m-d h:m:s');
            $model->save();
             Yii::$app->getSession()->setFlash('notificacion-error', [
                'type' => 'success',
                'duration' => 5000,
                'icon' => 'glyphicon glyphicon-error-sign',
                'message' => 'Pesaje de leche Registrado exitosamente.',
                'title' => '¡Registrado!',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);
            return $this->redirect(['view', 'id' => $model->id_ordeno]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Ordeno model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $id = $model->animal_identificacion;

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Ordeno'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        } 
        

        if ($model->load(Yii::$app->request->post())) {
            $model->attributes = $_POST['Ordeno'];
            $pesaje = Ordeno::find()
            ->where(['animal_identificacion'=>$id])
            ->andwhere(['<','id_ordeno',$model->id_ordeno])
            ->orderBy(['fecha'=>SORT_DESC])
            ->one();

        $parto = Parto::find()
            ->where(['animal_identificacion'=>$id])
            ->orderBy(['fecha'=>SORT_DESC])
            ->one();

            if($pesaje)
            {
                //$model->pesaje_anterior = $pesaje->fecha;

                $interval1 = date_diff(date_create($pesaje->fecha), date_create($_POST['Ordeno']['fecha']));
                $dias_pe = (int)$interval1->format('%R%a');
                if($dias_pe<1)
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la fecha del pesaje no es mayor a la fecha del ultimo pesaje',
                    'title' => '¡Error de fecha!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']);
                }
                $model->dias=$dias_pe;
                //$model->pesaje_total=($dias_pe*(float)$_POST['Ordeno']['pesaje'])+$pesaje->pesaje_total;

            }
            else
            {

                $interval2 = date_diff(date_create($parto->fecha), date_create($_POST['Ordeno']['fecha']));
                $dias_pa = (int)$interval2->format('%R%a');
                if($dias_pa<1)
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la fecha del pesaje no es mayor a la fecha del parto',
                    'title' => '¡Error de fecha!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']);
                }
                $model->dias=$dias_pa;
                //$model->pesaje_total=($dias_pa*(float)$_POST['Ordeno']['pesaje']);

            }
            $model->animal_identificacion=$id;
            $model->save();
            Yii::$app->getSession()->setFlash('notificacion-error', [
                'type' => 'success',
                'duration' => 5000,
                'icon' => 'glyphicon glyphicon-error-sign',
                'message' => 'Pesaje de leche se ha actualizado exitosamente.',
                'title' => '¡Actualizado!',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);
            return $this->redirect(['view', 'id' => $model->id_ordeno]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ordeno model.
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
     * Finds the Ordeno model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ordeno the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ordeno::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
