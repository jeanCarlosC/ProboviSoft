<?php

namespace backend\controllers;

use Yii;
use backend\models\Diagnostico;
use backend\models\Servicio;
use backend\models\Parto;
use backend\models\Animal;
use backend\models\DiagnosticoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use backend\models\Model;
use yii\widgets\ActiveForm;
use backend\models\AnimalSearch2;
use backend\models\StatusEliminacion;

/**
 * DiagnosticoController implements the CRUD actions for Diagnostico model.
 */
class DiagnosticoController extends Controller
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
                        'actions' => ['logout', 'index','create','view','update','delete','fecha','diagnosticos'],
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
     * Lists all Diagnostico models.
     * @return mixed
     */


    public function actionFecha()
        {

        $model = new Diagnostico();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Diagnostico'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }

            if($model->load(Yii::$app->request->post()))
            {
                $model->attributes = $_POST['Diagnostico'];

            $key = yii::$app->request->post('selection');
            $key = serialize($key);

            $fecha = $model->fecha;
            $diagnostico = $model->diagnostico_prenez;
            return $this->redirect(['diagnosticos', 'f' => $fecha, 'a' => $key,'d'=>$diagnostico]);


            }/*post*/

    }

        public function actionDiagnosticos($f,$a,$d)
    {

        $a=unserialize($a);
        foreach ($a as $key => $value) {
            $model[$key] = new Diagnostico();
            $model[$key]->fecha = $f;
            $model[$key]->animal_identificacion = (int)$value;


            if($d=='P')
            {
                $model[$key]->scenario='preñada';
            }
            else
            {
                $model[$key]->scenario='vacia';
            }

            $animales[$key] = array('animal_identificacion'=>$value,'fecha'=>$f, 'diagnostico'=>$d);
               
    }

    
        if(Yii::$app->request->isAjax)
        {
            
            $model = Model::createMultiple(Diagnostico::classname());
            Model::loadMultiple($model, Yii::$app->request->post());
            /*Yii::$app->response->format = Response::FORMAT_JSON;*/
            Yii::$app->response->format = 'json';
            return ActiveForm::validateMultiple($model); 
            
            
        }
        
    

    if (Yii::$app->request->post()) 
        {
            $model = Model::createMultiple(Diagnostico::classname());
            Model::loadMultiple($model, Yii::$app->request->post());
/*            echo "<pre>";
            print_r($animales);
            echo "</pre>";
            yii::$app->end();*/
            foreach ($model as $i => $model1) {
            $ultimo_servicio = Servicio::find()->where([ "animal_identificacion"=>$animales[$i]['animal_identificacion'] ])
            ->orderBy(["id_servicio"=>SORT_DESC])
            ->one();
/*            echo "<pre>";
            print_r($ultimo_servicio);
            echo "</pre>";
            yii::$app->end();*/
            $nuevafecha = date('Y-m-d',strtotime ( '+9 month' , strtotime ( $ultimo_servicio->fecha ) )) ;
            $model1->parto_esperado = $nuevafecha;
            $model1->animal_identificacion = $animales[$i]['animal_identificacion'];
            $model1->fecha=$f;
            $model1->diagnostico_prenez = $d;
            $model1->servicio_id_servicio = $ultimo_servicio->id_servicio;
            $model1->creado = date('Y-m-d h:m:s');
/*            echo "<pre>";
            print_r($model1);
            echo "</pre>";
            yii::$app->end();*/
            $model1->save(false); 

            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'success',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Diagnosticos Registrados exitosamente',
            'title' => '¡Registro Exitoso!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);           

            }

        }
        else
        {


        
        return $this->render('diagnosticos', [
            'dataProvider' => $animales,
            'model'=>(empty($model)) ? [new Servicio] : $model,
            'diagnostico'=>$d,
        ]);

        }
    }


    public function actionIndex()
    {
        $model = new Diagnostico();
/*        $searchModel2 = new AnimalSearch2();
        $dataProvider2= $searchModel2->search(Yii::$app->request->queryParams);*/

        $searchModel = new DiagnosticoSearch();
        $dataProvider= $searchModel->search(Yii::$app->request->queryParams);
        $animal_v = array();

        $animales = Animal::find()->select(["identificacion"])
        ->where(["sexo"=>"H"])
        ->orderBy(["fecha_nacimiento"=>SORT_DESC])
        ->all();
        


        foreach ($animales as $key => $value) {

            $ultimo_servicio = Servicio::find()->where(["animal_identificacion"=>$value['identificacion']])
            ->orderBy(["id_servicio"=>SORT_DESC])
            ->one();

            $status = StatusEliminacion::find()->where(["animal_identificacion"=>$value['identificacion']])->one(); 

            if(!empty($ultimo_servicio)){
            $parto = Parto::find()->where(["servicio_id_servicio"=>$ultimo_servicio->id_servicio])
            ->one();

            if( (empty($parto)) && (empty($status)) )
            {
                $animales_e[] = array("identificacion"=>$value['identificacion']);
            }

            }
    
        }


            $dataProviderAnimales = new ArrayDataProvider([
            'key'=>'identificacion',
            'allModels' => (empty($animales_e)) ? $animal_v : $animales_e,  
            'pagination' => [
            'pageSize' => 5,
            ],
            ]);

        return $this->render('index', [
/*            'searchModel2' => $searchModel2,
            'dataProvider2' => $dataProvider2,*/
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2'=>$dataProviderAnimales,
            'model'=>$model,
        ]);
    }

    /**
     * Displays a single Diagnostico model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Diagnostico::find()->select(["LPAD(animal_identificacion, 6, '0') as animal","fecha","diagnostico_prenez","dias_gestacion","parto_esperado","servicio_id_servicio","ovario_izq","ovario_der","utero","observacion","id_diagnostico"])->where(['id_diagnostico'=>$id])->one();
        $servicio = Servicio::find()->select(["LPAD(animal_identificacion, 6, '0') as animal","fecha","tipo_servicio","LPAD(semen_identificacion,6,'0') as semen","inseminador","LPAD(toro, 6, '0') as toro_monta"])->where(['id_servicio'=>$model->servicio_id_servicio])->one();
        return $this->render('view', [
            'model' => $model,
            'servicio'=> $servicio,
        ]);
    }

    /**
     * Creates a new Diagnostico model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Diagnostico();

            $servicios= Servicio::find()->count();

             if($servicios<1)
             {


            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Debe registrar servicios previamente para registrar un Diagnostico.',
            'title' => '¡No existen SERVICIOS!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);

             }

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Diagnostico'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
                
        if ($model->load(Yii::$app->request->post())) 
            {
            $validacion_servicio2 = Servicio::find()->where(['animal_identificacion'=>$_POST['Diagnostico']['animal_identificacion']])->orderBy(['id_servicio'=>SORT_DESC])->one();
            $validacion_servicio = Parto::find()
            ->where(['servicio_id_servicio'=>$validacion_servicio2->id_servicio])
            ->one();

            

            $val_animal = Animal::find()->where(['identificacion'=>$_POST['Diagnostico']['animal_identificacion']])->one();

            $validacion_parto = Servicio::find()->where(['animal_identificacion'=>$_POST['Diagnostico']['animal_identificacion']])
            ->orderBy(['id_servicio'=>SORT_DESC])
            ->one();

/*            echo "<pre>";
            print_r($validacion_servicio->servicio_id_servicio);
            print_r($validacion_servicio2->id_servicio);
            echo "</pre>";
            yii::$app->end();*/

/****************************************************ANIMAL NO EXISTE*******************************************************/
                if(!($val_animal))
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Diagnostico']['animal_identificacion'].' no existe',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 
                }
/****************************************************NO POSEE SERVICIO*******************************************************/
                if(!($validacion_servicio2))
                {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Diagnostico']['animal_identificacion'].' no se encuentra servida.',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 
                }
/********************************************SU ULTIMO SERVICIO YA POSEE UN PARTO**************************************************/
                if($validacion_servicio)
                {

                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'danger',
                    'duration' => 5000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'la vaca/novilla '.$_POST['Diagnostico']['animal_identificacion'].' no se encuentra servida desde su ultimo parto.',
                    'title' => '¡Error de registro!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['create']); 
                }
                else

                {
                    if($_POST['Diagnostico']['diagnostico_prenez']=='P')
                    {
                        
                        if($validacion_parto)
                        {
                        $dias = 270/*-$_POST['Diagnostico']['dias_gestacion']*/;
                        if($validacion_parto->tipo_servicio=='IA')
                        {
                        $nuevafecha = date('Y-m-d',strtotime ( '+9 month' , strtotime ( $validacion_parto->fecha ) )) ;
                        }
                        else
                        {
                           $fecha_monta=date('Y-m-d',strtotime ( '-'.$_POST['Diagnostico']['dias_gestacion'].' days' , strtotime ( $_POST['Diagnostico']['fecha'] ) ));

                           $nuevafecha = date('Y-m-d',strtotime ( '+9 month' , strtotime ( $fecha_monta ) )) ;

                        }
                        //$fecha_parto = date('Y-m-d',strtotime($validacion_parto->fecha. ' + '.$dias.' days'));
                        $model->parto_esperado = $nuevafecha;
                        }
                    
                    }

                    if($validacion_parto){
                    $model->servicio_id_servicio = $validacion_parto->id_servicio;
                    }
                    $model->creado = date('Y-m-d h:m:s');
                    $model->save();

                    Yii::$app->getSession()->setFlash('notificacion-error', [
                    'type' => 'success',
                    'duration' => 2000,
                    'icon' => 'glyphicon glyphicon-error-sign',
                    'message' => 'Diagnostico Registrado Exitosamente.',
                    'title' => '¡Registrado!',
                    'positonY' => 'top',
                    'positonX' => 'right'
                    ]);

                    return $this->redirect(['view', 'id' => $model->id_diagnostico]);
                }
            }
             else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Diagnostico model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $consulta = Diagnostico::find()->where(['id_diagnostico'=>$id])->one();
        
        $partos = Parto::find()->where(['servicio_id_servicio'=>$consulta->servicio_id_servicio])->one();

        if(!(empty($partos)))
        {
           Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Este diagnostico no puede ser editado ya que posee un parto registrado',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']);  
        }
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes = $_POST['Diagnostico'];// asigna los valores al modelo animal

            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            $validacion_parto = Servicio::find()->where(['animal_identificacion'=>$model->animal_identificacion])
            ->orderBy(['id_servicio'=>SORT_DESC])
            ->one();

            if($model->diagnostico_prenez=='P')
                    {
                        
                        if($validacion_parto)
                        {
                        $dias = 270/*-$_POST['Diagnostico']['dias_gestacion']*/;
                        if($validacion_parto->tipo_servicio=='IA')
                        {
                        $nuevafecha = date('Y-m-d',strtotime ( '+9 month' , strtotime ( $validacion_parto->fecha ) )) ;
                        }
                        else
                        {
                           $fecha_monta=date('Y-m-d',strtotime ( '-'.$_POST['Diagnostico']['dias_gestacion'].' days' , strtotime ( $_POST['Diagnostico']['fecha'] ) ));

                           $nuevafecha = date('Y-m-d',strtotime ( '+9 month' , strtotime ( $fecha_monta ) )) ;

                        }
                        //$fecha_parto = date('Y-m-d',strtotime($validacion_parto->fecha. ' + '.$dias.' days'));
                        $model->parto_esperado = $nuevafecha;
                        }
                    
                    }
            $model->save();
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'success',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'El diagnostico fue Actualizado exitosamente',
            'title' => '¡Actualizacion exitosa!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);
            return $this->redirect(['view', 'id' => $model->id_diagnostico]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Diagnostico model.
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
     * Finds the Diagnostico model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Diagnostico the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Diagnostico::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
