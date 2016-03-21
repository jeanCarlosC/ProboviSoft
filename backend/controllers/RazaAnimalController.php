<?php

namespace backend\controllers;

use Yii;
use backend\models\RazaAnimal;
use backend\models\RazaAnimalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * RazaAnimalController implements the CRUD actions for RazaAnimal model.
 */
class RazaAnimalController extends Controller
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
     * Lists all RazaAnimal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RazaAnimalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RazaAnimal model.
     * @param string $raza_id_raza
     * @param integer $animal_identificacion
     * @return mixed
     */
    public function actionView($raza_id_raza, $animal_identificacion)
    {
        return $this->render('view', [
            'model' => $this->findModel($raza_id_raza, $animal_identificacion),
        ]);
    }

    /**
     * Creates a new RazaAnimal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RazaAnimal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'raza_id_raza' => $model->raza_id_raza, 'animal_identificacion' => $model->animal_identificacion]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RazaAnimal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $raza_id_raza
     * @param integer $animal_identificacion
     * @return mixed
     */
    public function actionUpdate($raza_id_raza, $animal_identificacion)
    {
        $model = $this->findModel($raza_id_raza, $animal_identificacion);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'raza_id_raza' => $model->raza_id_raza, 'animal_identificacion' => $model->animal_identificacion]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RazaAnimal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $raza_id_raza
     * @param integer $animal_identificacion
     * @return mixed
     */
    public function actionDelete($raza_id_raza, $animal_identificacion)
    {
        $this->findModel($raza_id_raza, $animal_identificacion)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RazaAnimal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $raza_id_raza
     * @param integer $animal_identificacion
     * @return RazaAnimal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($raza_id_raza, $animal_identificacion)
    {
        if (($model = RazaAnimal::findOne(['raza_id_raza' => $raza_id_raza, 'animal_identificacion' => $animal_identificacion])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
