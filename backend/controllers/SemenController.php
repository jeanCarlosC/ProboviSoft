<?php

namespace backend\controllers;

use Yii;
use backend\models\Semen;
use backend\models\Salida;
use backend\models\SemenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\RazaSemen;
use yii\helpers\JSon;
use backend\models\Model;
use backend\models\Raza;
use backend\models\Entrada;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\UploadedFile;

/*use backend\models\Entrada;*/

/**
 * SemenController implements the CRUD actions for Semen model.
 */
class SemenController extends Controller
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
                        'actions' => ['logout', 'index','create','view','update','agregar','salida','imagen'],
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
     * Lists all Semen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SemenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Semen model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Semen::find()
        ->select(["LPAD(identificacion, 6, '0') as identificacion_otro"," GROUP_CONCAT(raza_semen.raza_id_raza, CONCAT(raza_semen.porcentaje, ' ', '%') SEPARATOR ' y ') as raza","termo_identificacion","canastilla_identificacion","pajuelas","identificacion","imagen"])
        ->join('JOIN','raza_semen','raza_semen.semen_identificacion = semen.identificacion')
        ->groupBy('semen.identificacion')
        ->where(['semen.identificacion'=>$id])
        ->one();
        $query = Salida::find();
        $query->select(["fecha","pajuelas_salida","descripcion"])
        ->where(['semen_identificacion'=>$id])
        ->all();

        $dataProvider_salida = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
        'pageSize' => 10,
        ],
        ]);

        $query2 = Entrada::find();
        $query2->select(["fecha","pajuelas_entrada","descripcion","id_entrada"])
        ->where(['semen_identificacion'=>$id])
        ->all();

        $dataProvider_entrada = new ActiveDataProvider([
        'query' => $query2,
        'pagination' => [
        'pageSize' => 10,
        ],
        ]);

/*        $query2 = E::find();
        $query2->select(["fecha","pajuelas_salida","descripcion"])
        ->where(['semen_identificacion'=>$id])
        ->all();

        $dataProvider_salida = new ActiveDataProvider([
        'query' => $query2,
        'pagination' => [
        'pageSize' => 10,
        ],
        ]);*/

        return $this->render('view', [
            'model' => $model,
            'salida'=> $dataProvider_salida,
            'entrada'=>$dataProvider_entrada,
        ]);
    }

        public function actionImagen($id)
    {
        $model = $this->findModel($id);
        $model->avatar = UploadedFile::getInstance($model,'avatar');
            if(!(empty($model->avatar)))
            {
            
            $nombreImagen = $id;            
            $model->imagen = $nombreImagen.'.'.$model->avatar->extension;
            $model->avatar->saveAs('uploads/'.$nombreImagen.'.'.$model->avatar->extension);
            $model->save(false);

        Yii::$app->getSession()->setFlash('notificacion-error', [
        'type' => 'success',
        'duration' => 5000,
        'icon' => 'glyphicon glyphicon-error-sign',
        'message' => 'Imagen Actualizada con exito',
        'title' => '¡Cambio de Imagen!',
        'positonY' => 'top',
        'positonX' => 'right'
        ]);

        return $this->redirect(['view','id' => $model->identificacion]); 
            }
            else
            {
        Yii::$app->getSession()->setFlash('notificacion-error', [
        'type' => 'danger',
        'duration' => 5000,
        'icon' => 'glyphicon glyphicon-error-sign',
        'message' => 'No se actualizo imagen',
        'title' => '¡No has cargado ninguna Imagen!',
        'positonY' => 'top',
        'positonX' => 'right'
        ]);

        return $this->redirect(['view','id' => $model->identificacion]); 
            }

        
    }

    /**
     * Creates a new Semen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
            $model = new Semen();
            $model_raza_semen=[new RazaSemen];
            $model_entrada = new Entrada();


        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes=$_POST['Semen'];

             Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) 
        {

        $model->avatar = UploadedFile::getInstance($model,'avatar');
            if(!(empty($model->avatar)))
            {
            
            $nombreImagen = $model->identificacion;            
            $model->imagen = $nombreImagen.'.'.$model->avatar->extension;
            $model->avatar->saveAs('uploads/'.$nombreImagen.'.'.$model->avatar->extension);
            }

        $model_raza_semen = Model::createMultiple(RazaSemen::classname());
        Model::loadMultiple($model_raza_semen, Yii::$app->request->post());
        $model->attributes=$_POST['Semen'];

        $model_entrada->pajuelas_entrada = $_POST['Semen']['pajuelas'];
        $model_entrada->semen_identificacion= $_POST['Semen']['identificacion'];
        $model_entrada->descripcion = 'Registro inicial de semen';
        $model_entrada->fecha = date('Y-m-d');


        $model->creado = date('Y-m-d h:m:s');
        $model->isNewRecord;

        $valid = $model->validate();

                if ($valid) {

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($model_raza_semen as $model_raza_semen1) {
                            $model_entrada->save(false);
                            $raza_1 = Raza::find()->where(['id_raza'=>$model_raza_semen1->raza_id_raza])->one();
                            if(!($raza_1))
                            {
                                Yii::$app->getSession()->setFlash('notificacion-error', [
                                'type' => 'danger',
                                'duration' => 5000,
                                'icon' => 'glyphicon glyphicon-error-sign',
                                'message' => 'la Raza '.$model_raza_semen1->raza_id_raza.' no existe',
                                'title' => '¡Error Raza!',
                                'positonY' => 'top',
                                'positonX' => 'right'
                                ]);

                                return $this->redirect(['create']); 
                            }
                            $model_raza_semen1->semen_identificacion= $model->identificacion;
                            $model_raza_semen1->raza_id_raza = strtoupper($model_raza_semen1->raza_id_raza);
                            $model_raza_semen1->creado = date('Y-m-d h:m:s');
                            if (! ($flag = $model_raza_semen1->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Toro Registrado exitosamente.',
                        'title' => '¡Registrado!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
                        return $this->redirect(['view', 'id' => $model->identificacion]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

        } 
        else 
        {

        return $this->render('create', [
            'model' => $model,
            'model_raza_semen'=> (empty($model_raza_semen)) ? [new RazaSemen] : $model_raza_semen,
        ]);

        }
    }


        public function actionAgregar()
    {
        $model = new Semen();
        $model_entrada = new Entrada();

            $semen_1 = Semen::find()->count();
            if(!($semen_1))
            {
                Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Para Agregar pajuelas debe registrar previamente el Semen',
                        'title' => 'Error!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
                        return $this->redirect(['index']);    
            }

            if(Yii::$app->request->isAjax && $model_entrada->load(Yii::$app->request->post()))
        {
            $model_entrada->attributes=$_POST['Entrada'];

             Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model_entrada);
        }

        if ($model_entrada->load(Yii::$app->request->post())) 
        {

        $semen= Semen::find()->where(['identificacion' => $_POST['Entrada']['semen_identificacion']])->count();

        if(!($semen))
            {
                Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'El toro '.$_POST['Entrada']['semen_identificacion'].' no existe',
                        'title' => '¡Error!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
                        return $this->redirect(['agregar']);    
            }

        $model= $this->findModel($_POST['Entrada']['semen_identificacion']);
        $model->pajuelas+=$_POST['Entrada']['pajuelas_entrada'];
        $model->save(false);

        $model_entrada->pajuelas_entrada = $_POST['Entrada']['pajuelas_entrada'];
        $model_entrada->semen_identificacion= $_POST['Entrada']['semen_identificacion'];
        $model_entrada->descripcion = $_POST['Entrada']['descripcion'];
        $model_entrada->fecha = $_POST['Entrada']['fecha'];
        $model_entrada->save(false);

        Yii::$app->getSession()->setFlash('notificacion-error', [
        'type' => 'success',
        'duration' => 5000,
        'icon' => 'glyphicon glyphicon-error-sign',
        'message' => $_POST['Entrada']['pajuelas_entrada'].' Pajuelas Registradas al Toro '.$_POST['Entrada']['semen_identificacion'].' Exitosamente.',
        'title' => '¡Registrado!',
        'positonY' => 'top',
        'positonX' => 'right'
        ]);

        return $this->redirect(['view', 'id' => $model_entrada->semen_identificacion]);
    }
     else 
    {

    return $this->render('agregar', [
        'model' => $model_entrada,
    ]);

    }

    }
    public function actionSalida()
    {
        $model = new Salida();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes=$_POST['Salida'];

             Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
                    $semen_1 = Semen::find()->count();
            if(!($semen_1))
            {
                Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Para Registrar una salida de pajuelas debe registrar previamente el Semen',
                        'title' => 'Error!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
                        return $this->redirect(['index']);    
            }

        if ($model->load(Yii::$app->request->post())) 
        {
             $semen= Semen::find()->where(['identificacion' => $_POST['Salida']['semen_identificacion']])->count();

        if($semen == 0)
        {
        /**********************************Error Animal NO existe****************************************/

        Yii::$app->getSession()->setFlash('notificacion-error', [
        'type' => 'danger',
        'duration' => 5000,
        'icon' => 'glyphicon glyphicon-error-sign',
        'message' => 'El Toro '.$_POST['Salida']['identificacion'].' NO existe.',
        'title' => '¡Error al Agregar!',
        'positonY' => 'top',
        'positonX' => 'right'
        ]);

        return $this->redirect(['salida']);

        }

        $model->semen_identificacion = $_POST['Salida']['semen_identificacion'];
        $model->pajuelas_salida=$_POST['Salida']['pajuelas_salida'];
        $model->descripcion=$_POST['Salida']['descripcion'];
        $model->fecha = $_POST['Salida']['fecha'];
        $model->save(false);

        $model= $this->findModel($_POST['Salida']['semen_identificacion']);
        $model->pajuelas-=$_POST['Salida']['pajuelas_salida'];
        $model->save(false);

        Yii::$app->getSession()->setFlash('notificacion-error', [
        'type' => 'success',
        'duration' => 5000,
        'icon' => 'glyphicon glyphicon-error-sign',
        'message' => 'Salida de Pajuelas Registradas Exitosamente.',
        'title' => '¡Registrado!',
        'positonY' => 'top',
        'positonX' => 'right'
        ]);

        return $this->redirect(['index']);
    }
     else 
    {

    return $this->render('salida', [
        'model' => $model,
    ]);

    }

    }

    /**
     * Updates an existing Semen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models_razas = $model->razaIdRazas;

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes=$_POST['Semen'];
             Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($models_razas, 'raza_id_raza', 'raza_id_raza');
            $models_razas = Model::createMultiple(RazaSemen::classname(), $models_razas);
            Model::loadMultiple($models_razas, Yii::$app->request->post());

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($models_razas, 'raza_id_raza', 'raza_id_raza')));

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($models_razas),
                    ActiveForm::validate($model)
                );
            }

            $model->avatar = UploadedFile::getInstance($model,'avatar');
            if(!(empty($model->avatar)))
            {
            
            $nombreImagen = $model->identificacion;            
            $model->imagen = $nombreImagen.'.'.$model->avatar->extension;
            $model->avatar->saveAs('uploads/'.$nombreImagen.'.'.$model->avatar->extension);
            }
            /*$model->modificado = date('Y-m-d h:m:s');*/

            $valid = $model->validate();

             if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            RazaSemen::deleteAll(['semen_identificacion' => $id]);
                        }
                        foreach ($models_razas as $models_razas1) {
                            $models_razas1->semen_identificacion = $model->identificacion;
                            if (! ($flag = $models_razas1->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'El Toro fue Actualizado exitosamente',
                        'title' => '¡Actualizacion exitosa!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
                        return $this->redirect(['view', 'id' => $model->identificacion]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'model_raza_semen' => (empty($models_razas)) ? [new RazaSemen] : $models_razas,
            ]);
        }
    }

    /**
     * Deletes an existing Semen model.
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
     * Finds the Semen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Semen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Semen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}
