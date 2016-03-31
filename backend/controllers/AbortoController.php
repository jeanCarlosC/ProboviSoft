<?php

namespace backend\controllers;

use Yii;
use backend\models\Aborto;
use backend\models\Parto;
use backend\models\AbortoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Servicio;
use backend\models\Animal;
use backend\models\Diagnostico;
use yii\filters\AccessControl;

/**
 * AbortoController implements the CRUD actions for Aborto model.
 */
class AbortoController extends Controller
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
     * Lists all Aborto models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AbortoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Aborto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Aborto::find()->select(["animal_identificacion as identificacion","fecha","observacion","servicio_id_servicio","id_aborto","sexo_feto"])->where(['id_aborto'=>$id])->one();

        $servicio = Servicio::find()->select(["animal_identificacion as animal","fecha","tipo_servicio","semen_identificacion semen","inseminador","toro as toro_monta"])->where(['id_servicio'=>$model->servicio_id_servicio])->one();

        return $this->render('view', [
            'model' => $model,
            'servicio'=>$servicio,
        ]);
    }

    /**
     * Creates a new Aborto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Aborto();

        $diagnosticos= Diagnostico::find()->count();

             if($diagnosticos<1)
             {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe registrar diagnosticos previamente para registrar un Aborto.',
            'title' => '¡NO existen DIAGNOSTICOS!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);

             }

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Aborto'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) 
        {


            $val_animal = Animal::find()
            ->where(['identificacion'=>$_POST['Aborto']['animal_identificacion']])
            ->one();

/****************************************************ANIMAL NO EXISTE*******************************************************/
                if(!($val_animal))
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Aborto']['animal_identificacion'].' no existe',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 
                }

            $validacion_diagnostico = Diagnostico::find()
            ->where(['animal_identificacion'=>$_POST['Aborto']['animal_identificacion']])
            ->orderBy(['id_diagnostico'=>SORT_DESC])
            ->one();

/******************************************* VACA/NOVILLA  NO ESTA PREÑADA*******************************************************/
            if( !($validacion_diagnostico) || $validacion_diagnostico->diagnostico_prenez == 'V')
            {
   
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Aborto']['animal_identificacion'].' no se escuentra preñada',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']);
            }

            $validacion_servicio = Servicio::find()
            ->where(['id_servicio'=>$validacion_diagnostico->servicio_id_servicio])
            ->one();

            $parto = Parto::find()
            ->where(['servicio_id_servicio'=>$validacion_servicio->id_servicio])
            ->one();

/*******************************************ULTIMO SERVICIO YA POSEE UN PARTO *****************************************************/
            if($parto)
            {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Aborto']['animal_identificacion'].' no ha sido servida desde su ultimo parto',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 

            }

            $aborto = Aborto::find()
            ->where(['servicio_id_servicio'=>$validacion_servicio->id_servicio])
            ->one();

/*******************************************ULTIMO SERVICIO YA POSEE UN ABORTO ***************************************************/
            if($aborto)
            {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Aborto']['animal_identificacion'].' no ha sido servida desde su ultimo aborto/parto',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 

            }

/************************************FECHA DE ABORTO MENOR A LA FECHA DE SERVICIO*************************************************/
            if($validacion_servicio->fecha>$_POST['Aborto']['fecha'])
            {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la fecha de aborto no puede ser menor a la fecha de servicio de la vaca/novilla',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 

            }
            $model->attributes=$_POST['Aborto'];
            $model->servicio_id_servicio=$validacion_servicio->id_servicio;
            $model->save(false);

             Yii::$app->getSession()->setFlash('notificacion-error', [
                'type' => 'success',
                'duration' => 5000,
                'icon' => 'glyphicon glyphicon-error-sign',
                'message' => 'Aborto Registrado exitosamente.',
                'title' => '¡Registrado!',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);
            return $this->redirect(['view', 'id' => $model->id_aborto]);
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Aborto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_aborto]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Aborto model.
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
     * Finds the Aborto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Aborto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Aborto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
