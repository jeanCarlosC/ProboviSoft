<?php

namespace backend\controllers;

use Yii;
use backend\models\Parto;
use backend\models\Servicio;
use backend\models\StatusAnimal;
use backend\models\Peso;
use backend\models\Model;
use backend\models\Aborto;
use backend\models\Animal;
use backend\models\Diagnostico;
use backend\models\Ordeno;
use backend\models\RazaAnimal;
use backend\models\PartoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

/**
 * PartoController implements the CRUD actions for Parto model.
 */
class PartoController extends Controller
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
     * Lists all Parto models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PartoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Parto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $model = Parto::find()->select(["LPAD(animal_identificacion, 6, '0') as identificacion_otro","fecha","cod_becerro","sexo_becerro","servicio_id_servicio","id_parto"])->where(['id_parto'=>$id])->one();

        $servicio = Servicio::find()->select(["LPAD(animal_identificacion, 6, '0') as animal","fecha","tipo_servicio","LPAD(semen_identificacion,6,'0') as semen","inseminador","LPAD(toro, 6, '0') as toro_monta"])->where(['id_servicio'=>$model->servicio_id_servicio])->one();

        return $this->render('view', [
            'model' => $model,
            'servicio'=> $servicio,
        ]);
    }

    /**
     * Creates a new Parto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Parto();
        $model_animal = new Animal();
        $model_peso = new Peso();
        $model_status = new StatusAnimal();
        $model_raza = new RazaAnimal();
        $model->tipo_parto=1;


        $diagnosticos= Diagnostico::find()->count();

             if($diagnosticos<1)
             {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe registrar diagnosticos previamente para registrar un Parto.',
            'title' => '¡NO existen DIAGNOSTICOS!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);

             }

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Parto'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model)+\yii\widgets\ActiveForm::validate($model_animal);
        }

        if ($model->load(Yii::$app->request->post())) 
        {
            /*$model_raza_becerro = Model::createMultiple(RazaAnimal::classname());
            Model::loadMultiple($model_raza, Yii::$app->request->post());*/

             $val_animal = Animal::find()
             ->where(['identificacion'=>$_POST['Parto']['animal_identificacion']])
             ->one();

/****************************************************ANIMAL NO EXISTE*******************************************************/
                if(!($val_animal))
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Parto']['animal_identificacion'].' no existe',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 
                }

            $validacion_diagnostico = Diagnostico::find()
            ->where(['animal_identificacion'=>$_POST['Parto']['animal_identificacion']])
            ->orderBy(['id_diagnostico'=>SORT_DESC])
            ->one();

/******************************************* VACA/NOVILLA  NO ESTA PREÑADA*******************************************************/
            if( !($validacion_diagnostico) || $validacion_diagnostico->diagnostico_prenez == 'V')
            {
   
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Parto']['animal_identificacion'].' no se escuentra preñada',
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

/*******************************************ULTIMO SERVICIO YA POSEE UN PARTO *******************************************************/
            if($parto)
            {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Parto']['animal_identificacion'].' no ha sido servida desde su ultimo parto',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 

            }

            $aborto = Aborto::find()
            ->where(['servicio_id_servicio'=>$validacion_servicio->id_servicio])
            ->one();

            if($aborto)
            {
                Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 6000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Parto']['animal_identificacion'].' no ha sido servida nuevamente, su ultimo servicio posee un aborto registrado',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 
            }

/*************************************FECHA DE PARTO MENOR A LA FECHA DE SERVICIO**************************************************/
            if($validacion_servicio->fecha>$_POST['Parto']['fecha'])
            {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la fecha de parto no puede ser menor a la fecha de servicio de la vaca/novilla',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 

            }

            $fecha = $validacion_servicio->fecha;

            $interval1 = date_diff(date_create($fecha), date_create($_POST['Parto']['fecha']));
            $dias_gestacion = (int)$interval1->format('%R%a');

            $segundos= strtotime($_POST['Parto']['fecha']) - strtotime($fecha);
            $diferencia_dias=intval($segundos/60/60/24)-1;

/******************************************* TIEMPO GESTACION NO VALIDO*******************************************************/
            if($dias_gestacion<210 || $dias_gestacion>285)
            {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la fecha de parto no cumple con el tiempo de gestacion de la vaca/novilla',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 

            }

            else
            {
                /********************************guardando datos de parto***************************************/
                $valid = $model->validate();
                $model->creado = date('Y-m-d h:m:s');
                $model->attributes = $_POST['Parto'];
                $model->servicio_id_servicio = $validacion_diagnostico->servicio_id_servicio;
                $model->isNewRecord;
                $model->save(false);
                /********************************guardando datos de STATUS***************************************/
                $model_status->load(Yii::$app->request->post());
                $model_status->animal_identificacion = $_POST['Parto']['animal_identificacion'];
                $model_status->status_id_status = 'O';
                $model_status->fecha = $_POST['Parto']['fecha'];
                $model_status->creado = date('Y-m-d h:m:s');
                $model_status->isNewRecord;
                $model_status->save(false);

/*                echo "<pre>";
                print_r($model);
                echo "</pre>";
                yii::$app->end();*/



                /*********************************gardando datos del Becerro************************************/

                $array_cod = str_split((string) $_POST['Parto']['cod_becerro']);
                $codigo='';
                $i=0;
                foreach ($array_cod as $key) {

                    if($i>1)
                    {
                        $codigo = (string)$codigo.(string)$key;
                    }
                    $i++;
                }
                $model_animal->load(Yii::$app->request->post());
                $model_animal->identificacion = $_POST['Parto']['cod_becerro'];
                $model_animal->sexo = $_POST['Parto']['sexo_becerro'];
                $model_animal->fecha_nacimiento = $_POST['Parto']['fecha'];
                $model_animal->nuemro_arete = (int)$codigo;
                $model_animal->madre = $_POST['Parto']['animal_identificacion'];
                $model_animal->padre = $validacion_servicio->semen_identificacion;
                $model_animal->creado = date('Y-m-d h:m:s');
                $model_animal->isNewRecord;
                $model_animal->save();

                /*******************************guardando datos del peso del becerro******************************/

                $model_peso->load(Yii::$app->request->post());
                $model_peso->fecha = $_POST['Parto']['fecha'];
                $model_peso->peso = $_POST['Parto']['peso_becerro'];
                $model_peso->animal_identificacion = $_POST['Parto']['cod_becerro'];
                $model_peso->tipo_id_tipo = 'PN';
                $model_peso->creado = date('Y-m-d h:m:s');
                $model_peso->isNewRecord;
                $model_peso->save(false);

                /*******************************guardando datos de la raza del becerro******************************/
/*                $raza_madre = RazaAnimal::find()->where(['animal_identificacion'=>$_POST['Parto']['animal_identificacion']])->all();
                $array = ArrayHelper::map($raza_madre,'identificacion','identificacion');
                $raza_T ="";
                foreach ($array as $key => $value) {
                   $raza_T =$raza_T.$value
                }
                $model_raza_madre */

                /*******************************RAZAS DE LA MADRE***********************************/
                $query = new Query;
                $query->select(["raza_id_raza as raza","porcentaje"])
                ->from('raza_animal')
                ->where(['animal_identificacion'=>$_POST['Parto']['animal_identificacion']]);
                /********************************************************************************/


                /*******************************RAZAS DEL PADRE***********************************/
                $query2 = new Query;
                $query2->select(["raza_id_raza as raza","porcentaje"])
                ->from('raza_semen')
                ->where(['semen_identificacion'=>$validacion_servicio->semen_identificacion]);
                /********************************************************************************/

                if(!($query) || !($query2))
                {
                   Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'el padre o la madre no poseen razas verifique la info..',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']);  
                }

            $dataProvider2 = new ActiveDataProvider([

            'query'=>$query
            ]);
            $dataProvider1 = new ActiveDataProvider([

            'query'=>$query2
            ]);


            $raza_p= "";
            $porcentP=0;
            $j=0;
            $arr= array();
            foreach ($dataProvider1->getModels() as $key => &$val_padre) 
            {
                    $porcentP=$val_padre['porcentaje']/2;
                    $raza_p = $val_padre['raza'];
                    $i=0;
                    $band=0;
                    foreach ($dataProvider2->getModels() as $key2 => $val_madre) 
                    {
                        if($raza_p==$val_madre['raza'])
                        {
                            $porcentP+=($val_madre['porcentaje']/2);
 /*                           $arr[$i]['raza'] = $val_madre['raza'];
                            $arr[$i]['porcentaje'] = $porcentP;*/
                            $model_raza = new RazaAnimal();
                            $model_raza->raza_id_raza = $val_madre['raza'];
                            $model_raza->porcentaje = $porcentP;
                            $model_raza->animal_identificacion = $_POST['Parto']['cod_becerro'];
                            $model_raza->creado = date('Y-m-d h:m:s');
                            $model_raza->save(false);
                             $band=1;  
                        }
                        else
                        {
                            $model_raza = new RazaAnimal();
                            $model_raza->raza_id_raza = $val_madre['raza'];
                            $model_raza->porcentaje = $val_madre['porcentaje']/2;
                            $model_raza->animal_identificacion = $_POST['Parto']['cod_becerro'];
                            $model_raza->creado = date('Y-m-d h:m:s');
                            $model_raza->save(false);

                        }
                     $i++;
                    }

                    if($band==0)
                                { 
                                $model_raza = new RazaAnimal();  
                                $model_raza->raza_id_raza = $raza_p;
                                $model_raza->porcentaje = $porcentP;
                                $model_raza->animal_identificacion = $_POST['Parto']['cod_becerro'];
                                $model_raza->creado = date('Y-m-d h:m:s');
                                $model_raza->save(false);
                                }
                                 
            }                      

                        


                /*****************************************Mensaje de registrado******************************************/
                
                 Yii::$app->getSession()->setFlash('notificacion-error', [
                'type' => 'success',
                'duration' => 5000,
                'icon' => 'glyphicon glyphicon-error-sign',
                'message' => 'Parto Registrado exitosamente.',
                'title' => '¡Registrado!',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);
                return $this->redirect(['view', 'id' => $model->id_parto]);
            }
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Parto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_anterior = $this->findModel($id);
        $model_animal = new Animal();
        $model_peso = new Peso();
        $model_raza = new RazaAnimal();




        $pesaje = Ordeno::find()->where(['parto_id_parto'=>$model->id_parto])->count();

        if($pesaje>0)
        {
           Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Este Parto no puede ser editado ya que posee pesajes de leche registrado',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);  
        }

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Parto'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

/*           $validacion_diagnostico = Diagnostico::find()
            ->where(['animal_identificacion'=>$model->animal_identificacion])
            ->orderBy(['id_diagnostico'=>SORT_DESC])
            ->one();*/

            $validacion_servicio = Servicio::find()
            ->where(['id_servicio'=>$model_anterior->servicio_id_servicio])
            ->one(); 

/*                echo "<pre>";
                print_r($validacion_servicio);
                echo "</pre>";
                yii::$app->end();*/
            
            if($model->cod_becerro!=$model_anterior->cod_becerro || $model->fecha != $model_anterior->fecha || $model->sexo_becerro!=$model_anterior->sexo_becerro)
            {

                $model_becerro = $this->findModel_becerro($model_anterior->cod_becerro);
                RazaAnimal::deleteAll(['animal_identificacion' => $model_becerro->identificacion]);
                $model_peso = $this->findModel_peso($model_becerro->identificacion);
                $model_peso->delete();
                $model_becerro->delete();
            

                $model->creado = date('Y-m-d h:m:s');
                $model->servicio_id_servicio = $model_anterior->servicio_id_servicio;
                $model->isNewRecord;
                $model->save(false);

/*                echo "<pre>";
                print_r($model);
                echo "</pre>";
                yii::$app->end();*/



                /*********************************gardando datos del Becerro************************************/

                $array_cod = str_split((string) $_POST['Parto']['cod_becerro']);
                $codigo='';
                $i=0;
                foreach ($array_cod as $key) {

                    if($i>1)
                    {
                        $codigo = (string)$codigo.(string)$key;
                    }
                    $i++;
                }
                $model_animal->load(Yii::$app->request->post());
                $model_animal->identificacion = $_POST['Parto']['cod_becerro'];
                $model_animal->sexo = $_POST['Parto']['sexo_becerro'];
                $model_animal->fecha_nacimiento = $_POST['Parto']['fecha'];
                $model_animal->nuemro_arete = (int)$codigo;
                $model_animal->madre = $model_anterior->animal_identificacion;
                $model_animal->padre = $validacion_servicio->semen_identificacion;
                $model_animal->creado = date('Y-m-d h:m:s');
                $model_animal->isNewRecord;
                $model_animal->save(false);

                /*******************************guardando datos del peso del becerro******************************/

                $model_peso->load(Yii::$app->request->post());
                $model_peso->fecha = $_POST['Parto']['fecha'];
                $model_peso->peso = $_POST['Parto']['peso_becerro'];
                $model_peso->animal_identificacion = $_POST['Parto']['cod_becerro'];
                $model_peso->tipo_id_tipo = 'PN';
                $model_peso->creado = date('Y-m-d h:m:s');
                $model_peso->isNewRecord;
                $model_peso->save(false);

                /*******************************guardando datos de la raza del becerro******************************/
/*                $raza_madre = RazaAnimal::find()->where(['animal_identificacion'=>$_POST['Parto']['animal_identificacion']])->all();
                $array = ArrayHelper::map($raza_madre,'identificacion','identificacion');
                $raza_T ="";
                foreach ($array as $key => $value) {
                   $raza_T =$raza_T.$value
                }
                $model_raza_madre */

                /*******************************RAZAS DE LA MADRE***********************************/
                $query = new Query;
                $query->select(["raza_id_raza as raza","porcentaje"])
                ->from('raza_animal')
                ->where(['animal_identificacion'=>$model_anterior->animal_identificacion]);
                /********************************************************************************/


                /*******************************RAZAS DEL PADRE***********************************/
                $query2 = new Query;
                $query2->select(["raza_id_raza as raza","porcentaje"])
                ->from('raza_semen')
                ->where(['semen_identificacion'=>$validacion_servicio->semen_identificacion]);
                /********************************************************************************/

                if(!($query) || !($query2))
                {
                   Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'el padre o la madre no poseen razas verifique la info..',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']);  
                }

            $dataProvider2 = new ActiveDataProvider([

            'query'=>$query
            ]);
            $dataProvider1 = new ActiveDataProvider([

            'query'=>$query2
            ]);


            $raza_p= "";
            $porcentP=0;
            $j=0;
            $arr= array();
            foreach ($dataProvider1->getModels() as $key => &$val_padre) 
            {
                    $porcentP=$val_padre['porcentaje']/2;
                    $raza_p = $val_padre['raza'];
                    $i=0;
                    $band=0;
                    foreach ($dataProvider2->getModels() as $key2 => $val_madre) 
                    {
                        if($raza_p==$val_madre['raza'])
                        {
                            $porcentP+=($val_madre['porcentaje']/2);
 /*                           $arr[$i]['raza'] = $val_madre['raza'];
                            $arr[$i]['porcentaje'] = $porcentP;*/
                            $model_raza = new RazaAnimal();
                            $model_raza->raza_id_raza = $val_madre['raza'];
                            $model_raza->porcentaje = $porcentP;
                            $model_raza->animal_identificacion = $_POST['Parto']['cod_becerro'];
                            $model_raza->creado = date('Y-m-d h:m:s');
                            $model_raza->save(false);
                             $band=1;  
                        }
                        else
                        {
                            $model_raza = new RazaAnimal();
                            $model_raza->raza_id_raza = $val_madre['raza'];
                            $model_raza->porcentaje = $val_madre['porcentaje']/2;
                            $model_raza->animal_identificacion = $_POST['Parto']['cod_becerro'];
                            $model_raza->creado = date('Y-m-d h:m:s');
                            $model_raza->save(false);

                        }
                     $i++;
                    }

                    if($band==0)
                                { 
                                $model_raza = new RazaAnimal();  
                                $model_raza->raza_id_raza = $raza_p;
                                $model_raza->porcentaje = $porcentP;
                                $model_raza->animal_identificacion = $_POST['Parto']['cod_becerro'];
                                $model_raza->creado = date('Y-m-d h:m:s');
                                $model_raza->save(false);
                                }

                            }

            }

            $model->save();
            return $this->redirect(['view', 'id' => $model->id_parto]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Parto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $pesajes = Ordeno::find()->where(['parto_id_parto'=>$id])->count();
        if($pesajes>0)
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'Este parto no puede ser eliminado ya que posee pesajes de leche registrados',
                    'title' => '¡Error!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);
            return $this->redirect(['index']);
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Parto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Parto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Parto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel_becerro($id)
    {
        if (($model = Animal::find()->where(['identificacion'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel_peso($id)
    {
        if (($model = Peso::find()->where(['animal_identificacion'=>$id])->andWhere(['tipo_id_tipo'=>'PN'])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('El Animal no posee peso al nacer.');
        }
    }
}
