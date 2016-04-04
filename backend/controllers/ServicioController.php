<?php

namespace backend\controllers;

use Yii;
use backend\models\Servicio;
use backend\models\Diagnostico;
use backend\models\ServicioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use backend\models\Animal;
use backend\models\Salida;
use backend\models\Peso;
use backend\models\Parto;
use backend\models\Semen;
use backend\models\StatusEliminacion;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use backend\models\Model;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/**
 * ServicioController implements the CRUD actions for Servicio model.
 */
class ServicioController extends Controller
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
                        'actions' => ['logout', 'index','create','view','update','delete','fecha','servicios'],
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
     * Lists all Servicio models.
     * @return mixed
     */


        public function actionFecha()
        {

            $model = new Servicio();



            if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
            {
                $model->attributes = $_POST['Servicio'];// asigna los valores al modelo animal

                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            if($model->load(Yii::$app->request->post()))
            {
                $model->attributes = $_POST['Servicio'];
/*                  echo "<pre>";
            print_r($_POST['Servicio']['animales']);
            echo "</pre>";
            yii::$app->end();*/

            $key = $_POST['Servicio']['animales'];
            $key = serialize($key);

            $fecha = $model->fecha;
            $tipo = $model->tipo_servicio;
            return $this->redirect(['servicios', 'f' => $fecha, 'a' => $key,'t'=>$tipo]);


            }/*post*/

        }

    public function actionServicios($f,$a,$t)
    {

        $a=unserialize($a);
        foreach ($a as $key => $value) {
            $model[$key] = new Servicio();
            $model[$key]->fecha = $f;
            $model[$key]->animal_identificacion = (int)$value;
            $model[$key]->tipo_servicio = $t;

            if($t=='IA')
            {
                $model[$key]->scenario='inseminacion';
            }
            else
            {
                $model[$key]->scenario='monta';
            }

            $animales[$key] = array('animal_identificacion'=>$value,'fecha'=>$f,'tipo'=>$t);
               
    }

    
        if(Yii::$app->request->isAjax)
        {
            
            $model = Model::createMultiple(Servicio::classname());
            Model::loadMultiple($model, Yii::$app->request->post());
            /*Yii::$app->response->format = Response::FORMAT_JSON;*/
            Yii::$app->response->format = 'json';
            return ActiveForm::validateMultiple($model); 
            
            
        }
        
    

    if (Yii::$app->request->post()) 
        {
            $model = Model::createMultiple(Servicio::classname());
            Model::loadMultiple($model, Yii::$app->request->post());
/*            echo "<pre>";
            print_r($model);
            echo "</pre>";
            yii::$app->end();*/
            foreach ($model as $i => $model1) {

            $model1->animal_identificacion = $animales[$i]['animal_identificacion'];
            $model1->fecha=$f;
            $model1->tipo_servicio = $t;
            $model1->creado = date('Y-m-d h:m:s');
            $model1->save(false);            

            }

        }
        else
        {


        
        return $this->render('servicios', [
            'dataProvider' => $animales,
            'model'=>(empty($model)) ? [new Servicio] : $model,
            'tipo'=>$t,
        ]);

        }
    }

    public function actionIndex()
    {
        $searchModel = new ServicioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new Servicio();
        $animales = Animal::find()->select(["identificacion"])
        ->where(["sexo"=>"H"])
        ->orderBy(["fecha_nacimiento"=>SORT_DESC])
        ->all();
        $animal_v = array();
        /*$animales_e[] = array();*/

            $animal_1= Animal::find()->where(['sexo'=>'H'])->count();
             $animal_2= Animal::find()->where(['sexo'=>'M'])->count();
             $semen_1 = Semen::find()->count();

            if(!($animal_2))
            {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe registrar previamente un toro para registrar un servicio',
            'title' => '¡NO existen TOROS!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(Yii::$app->homeUrl);

            }
            
            if(!($semen_1))
            {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe registrar previamente Semen para registrar un servicio',
            'title' => '¡NO existe SEMEN!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return Url::toRoute(Yii::$app->homeUrl);

            }


        foreach ($animales as $key => $value) {

            
        $status = StatusEliminacion::find()->where(["animal_identificacion"=>$value['identificacion']])->one();  
        $peso= Peso::find()->where(["animal_identificacion"=>$value['identificacion']])->andwhere(['>', 'peso', 240])
        ->orderBy(['fecha'=>SORT_DESC])
        ->one();
            if(!empty($peso) && empty($status))
            {
            $ultimo_servicio = Servicio::find()->where(["animal_identificacion"=>$value['identificacion']])
            ->orderBy(["id_servicio"=>SORT_DESC])
            ->one();

            $parto = Parto::find()->where(["servicio_id_servicio"=>$ultimo_servicio->id_servicio])
            ->one();

            $vacias = Diagnostico::find()->where(["servicio_id_servicio"=>$ultimo_servicio->id_servicio])
            ->where(['diagnostico_prenez'=>'V'])
            ->one();

            if(empty($ultimo_servicio) || !(empty($parto)) || !(empty($vacias)))
            {
                $animales_e[] = array("identificacion"=>$value['identificacion']);
            }

            }/*fin if del peso*/
        }/*fin del ciclo*/
/*        
            $dataProviderAnimales = new ArrayDataProvider([
            'allModels' =>  (empty($animales_e)) ? $animal_v : $animales_e,
            ]);*/



        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'animales'=>empty($animales_e) ? array() : $animales_e,
            'model'=>$model,
        ]);
    }

    /**
     * Displays a single Servicio model.
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
     * Creates a new Servicio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Servicio();
        $model_salida = new Salida();

        
             $animal_1= Animal::find()->where(['sexo'=>'H'])->count();
             $animal_2= Animal::find()->where(['sexo'=>'M'])->count();
             $semen_1 = Semen::find()->count();

             if(!($animal_1))
             {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe registrar Vacas/Novillas previamente para registrar un servicio',
            'title' => '¡NO existen VACAS/NOVILLAS!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);

             }

            if(!($animal_2))
            {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe registrar previamente un toro para registrar un servicio',
            'title' => '¡NO existen TOROS!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);

            }
            
            if(!($semen_1))
            {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe registrar previamente Semen para registrar un servicio',
            'title' => '¡NO existe SEMEN!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);

            }
        

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Servicio'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
                /*---------verifica si el animal existe en la Basede Datos--------------*/
        if ($model->load(Yii::$app->request->post())) 
        {

        $animal= Animal::find()->where(['identificacion' => $_POST['Servicio']['animal_identificacion']])->count();
        $validacion_animal = Animal::find()->where(['identificacion' => $_POST['Servicio']['animal_identificacion']])
        ->one();

        if($animal == 0)
        {
            /**********************************Error Animal NO existe****************************************/

            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'La vaca/novilla '.$_POST['Servicio']['animal_identificacion'].' NO existe.',
            'title' => '¡Error Animal!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['create']);

        }

        if($validacion_animal->sexo =='M')
        {
            /**********************************Error sexo Animal****************************************/
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'El animal '.$_POST['Servicio']['animal_identificacion'].' NO es una hembra.',
            'title' => '¡Error de sexo!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

                    return $this->redirect(['create']);
        }

        $peso = Peso::find()->where(['animal_identificacion'=>$_POST['Servicio']['animal_identificacion']])
        ->orderBy(['id_peso' => SORT_DESC])
        ->one();

        if(!($peso) || $peso->peso <250)
        {
            /**********************************Error sexo Animal****************************************/
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 6000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'La vaca/novilla '.$_POST['Servicio']['animal_identificacion'].' NO posee el peso apto para ser servida (peso < 250kg).',
            'title' => '¡Error de Peso!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

                    return $this->redirect(['create']);
        }
        
        $fecha = $validacion_animal->fecha_nacimiento;

        $segundos= strtotime('now') - strtotime($fecha);
        $diferencia_dias=intval($segundos/60/60/24);
        if($diferencia_dias<540)
        {
            /**********************************Error Animal Edad del animal****************************************/
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'la vaca/novilla '.$_POST['Servicio']['animal_identificacion'].' NO tiene la edad apta para ser servida.',
            'title' => '¡Error de Edad!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

                    return $this->redirect(['create']);
        }

        if($_POST['Servicio']['toro']){
        $toro_apto = Animal::find()->where(['identificacion'=>$_POST['Servicio']['toro']])->one();

        $interval1 = date_diff(date_create($toro_apto->fecha_nacimiento), date_create("now"));
        $dias = (int)$interval1->format('%R%a');
        if($dias<365)
        {
          Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'El Toro '.$_POST['Servicio']['toro'].' NO tiene la edad apta para montar.',
            'title' => '¡Error de Edad!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

                    return $this->redirect(['create']);  
        }
    }

        $validacion_serv = Servicio::find()->where(['animal_identificacion' => $_POST['Servicio']['animal_identificacion']])
        ->orderBy(['id_servicio' => SORT_DESC])
        ->one();
        //$band_serv = $validacion_serv->one();
        if($validacion_serv)
        {

        
        $fecha = $validacion_serv->fecha;

        $segundos=strtotime('now') - strtotime($fecha);
        $diferencia_dias=intval($segundos/60/60/24)-1;

        if($diferencia_dias<20)
        {
            /**********************************Error Animal se servio menos de 50 dias****************************************/
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'El animal '.$_POST['Servicio']['animal_identificacion'].' fue servido hace '.$diferencia_dias.' dias.',
            'title' => '¡Servicio NO regsitrado!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

                    return $this->redirect(['create']);
        }

        $validacion_diagnostico = Diagnostico::find()->where(['servicio_id_servicio'=> $validacion_serv->id_servicio])
        ->one();
        $servicio_con_parto = Parto::find()->where(['servicio_id_servicio'=>$validacion_serv->id_servicio])->one();
        if($validacion_diagnostico && empty($servicio_con_parto))
        {
            $estado = $validacion_diagnostico->diagnostico_prenez;
            if($estado='P')
            {
            /**********************************Error vaca/novilla ya esta preñada****************************************/

            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Status del animal '.$_POST['Servicio']['animal_identificacion'].': preñada (P).',
            'title' => '¡Servicio NO registrado!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

                    return $this->redirect(['create']);  
            }
        }

        }

        if($_POST['Servicio']['toro'])
        {

        $validacion_Toro = Animal::find()->where(['identificacion'=>$_POST['Servicio']['toro']])
        ->one();

        if(!($validacion_Toro->identificacion))
        {
            /*********************************Toro(Monta Natural) no exite*********************************************/

            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'El toro '.$_POST['Servicio']['toro'].' NO existe.',
            'title' => '¡Error al Agregar!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

                    return $this->redirect(['create']);
        }

        }
        $band_semen =0;
        if($_POST['Servicio']['semen_identificacion'])
        {
            $band_semen=1;
        $validacion_semen = Semen::find()->where(['identificacion'=>$_POST['Servicio']['semen_identificacion']])
        ->one();
/*            echo "<pre>";
            print_r($_POST['Servicio']['semen_identificacion']);
            echo "</pre>";
            yii::$app->end();*/
         if(!$validacion_semen)
        {
            /**********************************Error Toro no existe en el termo de semen****************************************/

            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'El toro '.$_POST['Servicio']['toro'].' NO existe en el termo de semen',
            'title' => '¡Error al Agregar!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

                    return $this->redirect(['create']);
        }
        $band_pajuelas=0;
        if($validacion_semen->pajuelas==0)
        {
            /********************************Error no quedan pajuelas del Toro en el termo *********************************/ 

            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'No quedan pajuelas del toro: '.$_POST['Servicio']['toro'].' en el termo de semen',
            'title' => '¡Error al Agregar!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

                    return $this->redirect(['create']);
        }
        else
        {
            $band_pajuelas=1;
        }

        }
            
            $model->creado = date('Y-m-d h:m:s');
            $model->save();

            if($band_semen==1 && $band_pajuelas==1)
            {
            $semen = $this->findModel2($model->semen_identificacion);
            $semen->pajuelas-=1;    
            $semen->save(false);

            $model_salida->semen_identificacion=$_POST['Servicio']['semen_identificacion'];
            $model_salida->pajuelas_salida+=1;
            $model_salida->descripcion='Servicio a la vaca/novilla '.$_POST['Servicio']['animal_identificacion'];
            $model_salida->fecha =$_POST['Servicio']['fecha'];
            $model_salida->save(false);

            }
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'success',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Servicio Registrado Exitosamente.',
            'title' => '¡Registrado!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['view', 'id' => $model->id_servicio]);
        

        } 
        else 
        {

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Servicio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_anterior = $this->findModel($id);

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Servicio'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        $diagnosticos = Diagnostico::find()->where(['servicio_id_servicio'=>$id])->count();
        $partos = Parto::find()->where(['servicio_id_servicio'=>$id])->count();

        if($partos>0)
        {
           Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'El servicio no puede ser editado ya que posee diagnosticos y partos registrados',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);  
        }

        if($diagnosticos>0)
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'La Vaca/Novilla no puede ser editada ya que posee diagnosticos registrados',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post())) {


            if($model_anterior->tipo_servicio == $model->tipo_servicio)
            {

                if($model_anterior->tipo_servicio=='IA')
                {

                    if($model_anterior->semen_identificacion != $model->semen_identificacion){
                    
                    $model_semen = $this->findModel_semen($model_anterior->semen_identificacion);
/*
                    echo "<pre>";
                    print_r($model_semen);
                    echo "</pre>";
                    yii::$app->end();*/

                    $model_salida = $this->findModel_salida($model_anterior->semen_identificacion,$model_anterior->animal_identificacion,$model_anterior->fecha);

                    

                    $model_salida->delete();
                    $model_semen->pajuelas+=1;
                    $model_semen->save(false);

                    $model_semen_nuevo = $this->findModel_semen($model->semen_identificacion);



                    if($model_semen_nuevo->pajuelas>0){

                    
                    $model_semen_nuevo->pajuelas-=1;    
                    $model_semen_nuevo->save(false);

                    $model_salida = new Salida();
                    $model_salida->semen_identificacion = $model->semen_identificacion;
                    $model_salida->pajuelas_salida+=1;
                    $model_salida->descripcion='Servicio a la vaca/novilla '.$model->animal_identificacion;
                    $model_salida->fecha =$model->fecha;
                    $model_salida->save(false);
                    }

                    }
                    else
                    {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'No quedan pajuelas del toro: '.$model_semen_nuevo->identificacion.' en el termo de semen',
                    'title' => '¡Error al Agregar!',
                    'positonY' => 'top',
                    'positonX' => 'right'
            ]);

                    return $this->render('update',['model'=> $model->id_servicio,]);
                    }

                    /*echo "<pre>";
                    print_r($model_semen);
                    echo "</pre>";
                    yii::$app->end();*/
                }
            }
            else
            {
                

                if($model->tipo_servicio=='IA')
                {

                    $model_semen = $this->findModel_semen($model->semen_identificacion);

                  /*  echo "<pre>";
                print_r($model_semen->pajuelas);
                print_r($model_anterior->tipo_servicio);
                echo "</pre>";
                yii::$app->end();*/
                    if($model_semen->pajuelas>0){
                    $model_semen->pajuelas-=1;    
                    $model_semen->save(false);

                    $model_salida = new Salida();
                    $model_salida->semen_identificacion = $model->semen_identificacion;
                    $model_salida->pajuelas_salida+=1;
                    $model_salida->descripcion='Servicio a la vaca/novilla '.$model->animal_identificacion;
                    $model_salida->fecha =$model->fecha;
                    $model_salida->save(false);
                    }
                    else
                    {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'No quedan pajuelas del toro: '.$model_semen->identificacion.' en el termo de semen',
                    'title' => '¡Error al Agregar!',
                    'positonY' => 'top',
                    'positonX' => 'right'
            ]);

                    return $this->render('update',['model'=> $model->id_servicio,]);
                    }
                }
            }
            $model->save();
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'success',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'El Servicio fue Actualizado exitosamente',
            'title' => '¡Actualizacion exitosa!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);
            return $this->redirect(['view', 'id' => $model->id_servicio]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Servicio model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {


        $diagnosticos = Diagnostico::find()->where(['servicio_id_servicio'=>$id])->count();
        $partos = Parto::find()->where(['servicio_id_servicio'=>$id])->count();

        if($partos>0)
        {
           Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'La Vaca/Novilla no puede ser editada ya que posee diagnosticos y partos registrados',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);  
        }

        if($diagnosticos>0)
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'La Vaca/Novilla no puede ser editada ya que posee diagnosticos registrados',
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
     * Finds the Servicio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Servicio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Servicio::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

        protected function findModel_semen($id)
    {
        if (($model = Semen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
        protected function findModel_salida($id,$d,$f)
    { /*andFilterWhere(['like', 'descripcion', $d])*/
        if (($model = Salida::find()->where(['semen_identificacion'=>$id])->andFilterWhere(['like', 'descripcion', $d])->andwhere(['fecha'=>$f])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

        protected function findModel2($id)
    {
        if (($model = Semen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
