<?php

namespace backend\controllers;

use Yii;
use backend\models\StatusAnimal;
use backend\models\StatusAnimalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StatusAnimalController implements the CRUD actions for StatusAnimal model.
 */
class StatusAnimalController extends Controller
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
     * Lists all StatusAnimal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StatusAnimalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StatusAnimal model.
     * @param string $status_id_status
     * @param integer $animal_identificacion
     * @return mixed
     */
    public function actionView($status_id_status, $animal_identificacion)
    {
        return $this->render('view', [
            'model' => $this->findModel($status_id_status, $animal_identificacion),
        ]);
    }

    /**
     * Creates a new StatusAnimal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StatusAnimal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'status_id_status' => $model->status_id_status, 'animal_identificacion' => $model->animal_identificacion]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StatusAnimal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $status_id_status
     * @param integer $animal_identificacion
     * @return mixed
     */
    public function actionUpdate($status_id_status, $animal_identificacion)
    {
        $model = $this->findModel($status_id_status, $animal_identificacion);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'status_id_status' => $model->status_id_status, 'animal_identificacion' => $model->animal_identificacion]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing StatusAnimal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $status_id_status
     * @param integer $animal_identificacion
     * @return mixed
     */
    public function actionDelete($status_id_status, $animal_identificacion)
    {
        $this->findModel($status_id_status, $animal_identificacion)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StatusAnimal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $status_id_status
     * @param integer $animal_identificacion
     * @return StatusAnimal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($status_id_status, $animal_identificacion)
    {
        if (($model = StatusAnimal::findOne(['status_id_status' => $status_id_status, 'animal_identificacion' => $animal_identificacion])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
