<?php

namespace backend\controllers;

use Yii;
use backend\models\Peso;

use backend\models\Animal;
use backend\models\PesoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PesoController implements the CRUD actions for Peso model.
 */
class PesoController extends Controller
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
     * Lists all Peso models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PesoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Peso model.
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
     * Creates a new Peso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Peso();

        $animales = Animal::find()->count();
        if($animales<1)
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Registre Animales para poder cargar pesajes.',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']); 
        }

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes=$_POST['Peso'];
             Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            $peso_nacimiento = Peso::find()->where(['animal_identificacion'=>$_POST['Peso']['animal_identificacion']])
            ->andWhere(['tipo_id_tipo'=>'PN'])
            ->one();
            if($_POST['Peso']['tipo_id_tipo']=='PN' && $peso_nacimiento)
            {

                Yii::$app->getSession()->setFlash('notificacion-error', [
                'type' => 'danger',
                'duration' => 5000,
                'icon' => 'glyphicon glyphicon-error-sign',
                'message' => 'el animal '.$_POST['Peso']['animal_identificacion'].' ya posee peso de nacimiento (PN)',
                'title' => '¡Error Raza!',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);

                return $this->redirect(['create']); 

            }

            $peso_destete = Peso::find()->where(['animal_identificacion'=>$_POST['Peso']['animal_identificacion']])
            ->andWhere(['tipo_id_tipo'=>'PD'])
            ->one();
            if($_POST['Peso']['tipo_id_tipo']=='PD' && $peso_destete)
            {

                Yii::$app->getSession()->setFlash('notificacion-error', [
                'type' => 'danger',
                'duration' => 5000,
                'icon' => 'glyphicon glyphicon-error-sign',
                'message' => 'el animal '.$_POST['Peso']['animal_identificacion'].' ya posee peso de destete (PD)',
                'title' => '¡Error Raza!',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);

                return $this->redirect(['create']); 

            }



            $model->creado = date('Y-m-d h:m:s');
            $model->save();

            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'success',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Pesaje del animal registrado exitosamente',
            'title' => 'Registro Exitoso!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);
            
            return $this->redirect(['view', 'id' => $model->id_peso]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Peso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_peso]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Peso model.
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
     * Finds the Peso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Peso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Peso::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
