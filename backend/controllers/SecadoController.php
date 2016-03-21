<?php

namespace backend\controllers;

use Yii;
use backend\models\Secado;
use backend\models\Servicio;
use backend\models\Parto;
use backend\models\StatusAnimal;
use backend\models\Animal;
use backend\models\Ordeno;
use backend\models\SecadoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SecadoController implements the CRUD actions for Secado model.
 */
class SecadoController extends Controller
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
                        'actions' => ['logout', 'index','create','view','update','delete'],
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
     * Lists all Secado models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SecadoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Secado model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Secado::find()->select(["secado.animal_identificacion as animal","secado.fecha as secado_fecha","parto.fecha as parto_fecha","cod_becerro as cria","sexo_becerro as sexo_cria","id_secado"])->where(['id_secado'=>$id])->join('JOIN','parto','secado.parto_id_parto=parto.id_parto')->one();

           /* echo "<pre>";
            print_r($model);
            echo "</pre>";
            yii::$app->end();*/

        $model_ordeño = Ordeno::find()->where(['ordeno.animal_identificacion'=>$model->animal])
        ->join('JOIN','secado','secado.parto_id_parto = ordeno.parto_id_parto')
        ->one();
        return $this->render('view', [
            'model' => $model,
            'model_ordeño' => $model_ordeño,
        ]);
    }

    /**
     * Creates a new Secado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Secado();
        $model_status = new StatusAnimal();

        $ORDEÑO= StatusAnimal::find()->where(['status_id_status'=>'O'])->count();

             if($ORDEÑO<1)
             {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe existir Vacas/Novillas en ordeño para registrar secados.',
            'title' => '¡NO existen Vacas/Novillas en ORDEÑO!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);

             }

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Secado'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            
            $val_animal = Animal::find()
             ->where(['identificacion'=>$_POST['Secado']['animal_identificacion']])
             ->one();

/****************************************************ANIMAL NO EXISTE*******************************************************/
                if(!($val_animal))
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Secado']['animal_identificacion'].' no existe',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 
                }

                $status = StatusAnimal::find()
                ->where(['animal_identificacion'=>$_POST['Secado']['animal_identificacion']])
                ->orderBy(['fecha'=>SORT_DESC])
                ->one();
                 if( !($status) || $status->status_id_status == 'E')
                {
       
                        Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'la vaca/novilla '.$_POST['Secado']['animal_identificacion'].' no se escuentra en ordeño',
                        'title' => '¡Error de registro!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);

                        return $this->redirect(['create']);
                }

/*                $servicio = Servicio::find()
                ->where(['animal_identificacion'=>$_POST['Secado']['animal_identificacion']])
                ->orderBy(['fecha'=>SORT_DESC])
                ->one();*/

                $parto = Parto::find()
                ->where(['animal_identificacion'=>$_POST['Secado']['animal_identificacion']])
                ->orderBy(['id_parto'=>SORT_DESC])
                ->one();

                $secado = Secado::find()
                ->where(['parto_id_parto'=>$parto->id_parto])
                ->orderBy(['id_secado'=>SORT_DESC])
                ->one();


                 if($secado)
                {
       
                        Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'la vaca/novilla '.$_POST['Secado']['animal_identificacion'].' no ha sido servida nuevamente',
                        'title' => '¡Error de registro!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);

                        return $this->redirect(['create']);
                }

                $ordeno = Ordeno::find()->where(['animal_identificacion'=>$_POST['Secado']['animal_identificacion']])
                ->orderBy(['id_ordeno'=>SORT_DESC])
                ->one();

/*                echo "<pre>";
            print_r($_POST['Secado']['fecha']);
            echo "</pre>";
            yii::$app->end();*/

                if($ordeno->fecha != $_POST['Secado']['fecha'])
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'la fecha del secado debe ser igual a la fecha del ultimo pesaje: '.$ordeno->fecha.'.',
                        'title' => '¡Error de registro!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);

                        return $this->redirect(['create']);
                }

                $parto_actual = Parto::find()
                ->where(['animal_identificacion'=>$_POST['Secado']['animal_identificacion']])
                ->orderBy(['id_parto'=>SORT_DESC])
                ->one();

                $model->parto_id_parto = $parto_actual->id_parto;
                $model->creado = date('Y-m-d h:m:s');
                $model->save();

                $model_status->status_id_status='E';
                $model_status->animal_identificacion = $_POST['Secado']['animal_identificacion'];
                $model_status->fecha = $_POST['Secado']['fecha'];
                $model_status->creado = date('Y-m-d h:m:s');
                $model_status->save(false);

                 Yii::$app->getSession()->setFlash('notificacion-error', [
                'type' => 'success',
                'duration' => 5000,
                'icon' => 'glyphicon glyphicon-error-sign',
                'message' => 'Secado de vientre Registrado exitosamente.',
                'title' => '¡Registrado!',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);
                return $this->redirect(['view', 'id' => $model->id_secado]);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Secado model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $id = $model->animal_identificacion;
        $fecha = $model->fecha;
        $model_status = $this->findModel_status($id,$fecha);

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Secado'];// asigna los valores al modelo animal
            $model->animal_identificacion=$id;

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }


        if ($model->load(Yii::$app->request->post())) 
        {
            $model->attributes = $_POST['Secado'];
             
            if($fecha != $model->fecha)
            {
                   
                    $model_status->fecha = $model->fecha;
                    $model_status->save(false);
            }

            $model->save();
             Yii::$app->getSession()->setFlash('notificacion-error', [
                'type' => 'success',
                'duration' => 5000,
                'icon' => 'glyphicon glyphicon-error-sign',
                'message' => 'Secado de vientre Actualizado exitosamente.',
                'title' => '¡Actualizado!',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);
            return $this->redirect(['view', 'id' => $model->id_secado]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Secado model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        
        $secado = $this->findModel($id);
        $status = StatusAnimal::find()->where(['animal_identificacion'=>$secado->animal_identificacion])
        ->andwhere(['status_id_status'=>'E'])->andwhere(['fecha'=>$secado->fecha])->one();

        if(!empty($status))
        {
        $status->delete();
        $secado->delete(); 
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Secado model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Secado the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Secado::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel_status($id,$f)
    {
        if (($model = StatusAnimal::find()->where(['animal_identificacion'=>$id])->andwhere(['fecha'=>$f])->one())!==null){
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
