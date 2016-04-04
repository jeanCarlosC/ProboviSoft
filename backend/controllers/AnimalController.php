<?php

namespace backend\controllers;

use Yii;
use backend\models\Animal;
use backend\models\AnimalSearch;
use yii\web\Controller;
use backend\models\Peso;
use backend\models\RazaAnimal;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\JSon;
use backend\models\Model;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use backend\models\Parto;
use backend\models\Ordeno;
use backend\models\Semen;
use backend\models\Raza;
use yii\filters\AccessControl;
use backend\models\StatusEliminacion;

/**
 * AnimalController implements the CRUD actions for Animal model.
 */
class AnimalController extends Controller
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
                        'actions' => ['logout', 'index','create','view','update','delete','imagen'],
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
     * Lists all Animal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AnimalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
     * Displays a single Animal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        
       $model = Animal::find()
        ->select(["LPAD(identificacion, 6, '0') as identificacion_otro","imagen","sexo","GROUP_CONCAT(CONCAT(raza_animal.porcentaje, '', '%'),' ', raza_animal.raza_id_raza ORDER BY raza_animal.porcentaje DESC SEPARATOR ' y ') as raza"," CONCAT(TIMESTAMPDIFF(MONTH,animal.fecha_nacimiento,CURDATE()), ' ','meses') as edad","nuemro_arete","LPAD(madre, 6, '0') as madre_1","LPAD(padre, 6, '0') as padre_1","fecha_nacimiento","identificacion","nombre"])
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->groupBy('animal.identificacion')
        ->where(['animal.identificacion'=>$id])
        ->one();

        $model_repro = Parto::find()
        ->where(['animal_identificacion'=>$id])
        ->one();

        $model_produ = Ordeno::find()
        ->select(["parto_id_parto","parto.fecha as Inicio_Lactancia","id_ordeno","ordeno.animal_identificacion"])
        ->where(['ordeno.animal_identificacion'=>$id])
        ->join('JOIN','parto','parto.id_parto=ordeno.parto_id_parto')
        ->orderBy(['id_ordeno'=>SORT_DESC])
        ->one();

        $animales = Animal::find()
        ->select(["GROUP_CONCAT(CONCAT(raza_animal.porcentaje, '', '%'),' ', raza_animal.raza_id_raza ORDER BY raza_animal.porcentaje DESC SEPARATOR ' y ') as raza","identificacion","sexo","madre","padre"])
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->groupBy('animal.identificacion')
        ->all();

        $semen = Semen::find()
        ->select(["identificacion","GROUP_CONCAT(raza_semen.raza_id_raza, CONCAT(raza_semen.porcentaje, ' ', '%') SEPARATOR ' y ') as raza"])
        ->from('semen')
        ->join('JOIN','raza_semen','raza_semen.semen_identificacion = semen.identificacion')
        ->groupBy('identificacion')
        ->all();

        /*$pedi[];*/
        $nada = 0;

        foreach ($animales as $i => $value) 
        {
            if($value['identificacion']==$id)
            {
                $pedi['hijo']= array('identificacion'=>$id,'raza'=>$value['raza']);

        /**************************************MADRE*****************************************/
            foreach ($animales as $i => $value2) 
            {

                if($value2['identificacion']==$value['madre'])
                {
                    $pedi['madre']=array('identificacion'=>$value['madre'],'raza'=>$value2['raza']);
        /**************************************ABUELOS MATERNOS*****************************************/
                    foreach ($animales as $key => $value3) 
                    {
                        if($value3['identificacion']==$value2['madre'])
                        {
                            $pedi['abuela_madre'] = array('identificacion'=>$value2['madre'],'raza'=>$value3['raza']);
                        }

                        if($value3['identificacion']==$value2['padre'])
                        {
                            $pedi['abuelo_madre'] = array('identificacion'=>$value2['padre'],'raza'=>$value3['raza']);
                        }
                    }
                }

        /**************************************PADRE*****************************************/        
                if($value2['identificacion']==$value['padre'])
                {
                    $pedi['padre']=array('identificacion'=>$value['padre'],'raza'=>$value2['raza']);

        /**************************************ABUELOS PATERNOS*****************************************/
                    foreach ($animales as $key => $value4) 
                    {
                        if($value4['identificacion']==$value2['madre'])
                        {
                            $pedi['abuela_padre'] = array('identificacion'=>$value2['madre'],'raza'=>$value4['raza']);
                        }

                        if($value4['identificacion']==$value2['padre'])
                        {
                            $pedi['abuelo_padre'] = array('identificacion'=>$value2['padre'],'raza'=>$value4['raza']);
                        }
                    }
                }
                else
                {
                    $pedi['padre']=array('identificacion'=>'N/A','raza'=>'N/A');
                    $nada=1;
                }
            }

          
            foreach ($semen as $key => $value5) {
                
                /**************************************PADRE*****************************************/        
                if($value5['identificacion']==$value['padre'])
                {
                    $pedi['padre']=array('identificacion'=>$value['padre'],'raza'=>$value5['raza']);

        /**************************************ABUELOS PATERNOS*****************************************/
                    foreach ($semen as $key => $value6) 
                    {
                        if($value6['identificacion']==$value5['madre'])
                        {
                            $pedi['abuela_padre'] = array('identificacion'=>$value5['madre'],'raza'=>$value6['raza']);
                        }

                        if($value6['identificacion']==$value5['padre'])
                        {
                            $pedi['abuelo_padre'] = array('identificacion'=>$value5['padre'],'raza'=>$value6['raza']);
                        }
                    }


                }
                
            }



            if(empty($pedi['madre']))
            {
                $pedi['madre']=array('identificacion'=>'N/A','raza'=>'N/A');
            }

            if(empty($pedi['padre']))
            {
                $pedi['padre']=array('identificacion'=>'N/A','raza'=>'N/A');
            }

            if(empty($pedi['abuelo_padre']))
            {
                $pedi['abuelo_padre']=array('identificacion'=>'N/A','raza'=>'N/A');
            }

            if(empty($pedi['abuela_padre']))
            {
                $pedi['abuela_padre']=array('identificacion'=>'N/A','raza'=>'N/A');
            }

            if(empty($pedi['abuelo_madre']))
            {
                $pedi['abuelo_madre']=array('identificacion'=>'N/A','raza'=>'N/A');
            }

            if(empty($pedi['abuela_madre']))
            {
                $pedi['abuela_madre']=array('identificacion'=>'N/A','raza'=>'N/A');
            }

            }
        }
        
        return $this->render('view', [
            'model' => $model,
            'model_repro'=>$model_repro,
            'model_produ'=>$model_produ,
            'pedi'=>$pedi,
            ]);
    }

    /**
     * Creates a new Animal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Animal(); 
        $model_raza=[new RazaAnimal];
        $model_peso = new Peso();

        
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes=$_POST['Animal'];
             Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if (Yii::$app->request->post()) 
        {

            $model_raza = Model::createMultiple(RazaAnimal::classname());
            Model::loadMultiple($model_raza, Yii::$app->request->post());

            $model->attributes = $_POST['Animal'];
            
            /*$valid = Model::validateMultiple($model_raza) && $valid;*/

            /*echo "<pre>";
            print_r(UploadedFile::getInstance($model,'avatar'));
            echo "</pre>";
            yii::$app->end();*/
            $model->avatar = UploadedFile::getInstance($model,'avatar');
            if(!(empty($model->avatar)))
            {
            
            $nombreImagen = $model->identificacion;            
            $model->imagen = $nombreImagen.'.'.$model->avatar->extension;
            $model->avatar->saveAs('uploads/'.$nombreImagen.'.'.$model->avatar->extension);
            }
            $model->creado=date('Y-m-d h:m:s');
            $model->isNewRecord;

            $valid = $model->validate();
            if($_POST['Peso']['peso_nacimiento'])
            {
            $model_peso->load(Yii::$app->request->post());
            $model_peso->animal_identificacion=$model->identificacion;
            $model_peso->fecha=$_POST['Animal']['fecha_nacimiento'];
            $model_peso->peso=$_POST['Peso']['peso_nacimiento'];  
            $model_peso->tipo_id_tipo="PN";
            $model_peso->creado=date('Y-m-d h:m:s');
            }

            if ($valid) {

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if($_POST['Peso']['peso_nacimiento']){$model_peso->save(false);}
                        foreach ($model_raza as $model_raza1) {
                            $raza_1 = Raza::find()->where(['id_raza'=>$model_raza1->raza_id_raza])->one();
                            if(!($raza_1))
                            {
                                Yii::$app->getSession()->setFlash('notificacion-error', [
                                'type' => 'danger',
                                'duration' => 5000,
                                'icon' => 'glyphicon glyphicon-error-sign',
                                'message' => 'la Raza '.$model_raza1->raza_id_raza.' no existe',
                                'title' => '¡Error Raza!',
                                'positonY' => 'top',
                                'positonX' => 'right'
                                ]);

                                return $this->redirect(['create']); 
                            }
                            $model_raza1->animal_identificacion= $model->identificacion;
                            $model_raza1->raza_id_raza = strtoupper($model_raza1->raza_id_raza);
                            $model_raza1->creado = date('Y-m-d h:m:s');
                            if (! ($flag = $model_raza1->save(false))) {
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
                        'message' => 'Animal Registrado exitosamente.',
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
        else{

                return $this->render('create', [
                'model' => $model,
                'model_raza'=> (empty($model_raza)) ? [new RazaAnimal] : $model_raza,
                'model_peso'=> $model_peso,
            ]);
    }
}

    /**
     * Updates an existing Animal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models_razas = $model->razaIdRazas;
        $model_peso = $this->findModel_peso($id);
        $status = StatusEliminacion::find()->where(['animal_identificacion'=>$id])->one();
        $parto_existe = Parto::find()->where(['animal_identificacion'=>$id])->count();

        if(!empty($status))
        {
            if($status->causa == 'V'){
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Esta animal se encuentra inactivo, motivo: VENTA',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']); 
        }

            if($status->causa == 'M'){
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Esta animal se encuentra inactivo, motivo: MUERTE',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']); 
        }

        }

        if($parto_existe>0)
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Esta vaca/novilla NO se puede EDITAR ya que posee partos registrados',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']); 
        }

        $hijos = Animal::find()->where(['padre'=>$id])->count();

        if($hijos>0)
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Esta Toro NO se puede EDITAR ya que posee hijos registrados',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']); 
        }

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model->attributes=$_POST['Animal'];
             Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }


    if ($model->load(Yii::$app->request->post())) 
    {
            $oldIDs = ArrayHelper::map($models_razas, 'raza_id_raza', 'raza_id_raza');
            $models_razas = Model::createMultiple(RazaAnimal::classname(), $models_razas);
            Model::loadMultiple($models_razas, Yii::$app->request->post());

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($models_razas, 'raza_id_raza', 'raza_id_raza')));

            /*            
            echo "<pre>";
            print_r($deletedIDs);
            echo "</pre>";
            yii::$app->end();
            */

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($models_razas),
                    ActiveForm::validate($model)
                );
            }

            /*****************************IMAGEN********************************************/
            $model->avatar = UploadedFile::getInstance($model,'avatar');
            if(!(empty($model->avatar)))
            {
            
            $nombreImagen = $model->identificacion;            
            $model->imagen = $nombreImagen.'.'.$model->avatar->extension;
            $model->avatar->saveAs('uploads/'.$nombreImagen.'.'.$model->avatar->extension);
            }
            $model->modificado = date('Y-m-d h:m:s');



            // validate all models
            $valid = $model->validate();
            //$valid = Model::validateMultiple($models_razas) && $valid;

            /*            
            echo "<pre>";
            print_r($model);
            print_r($models_razas);
            echo "</pre>";
            yii::$app->end();
            */

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        $model_peso->attributes = $_POST['Peso'];
                        $model_peso->save(false);
                        if (! empty($deletedIDs)) {
                            RazaAnimal::deleteAll(['animal_identificacion' => $id]);
                        }
                        foreach ($models_razas as $models_razas1) {
                            $models_razas1->animal_identificacion = $model->identificacion;
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
                        'message' => 'El Animal fue Actualizado exitosamente',
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

    }

        return $this->render('update', [
            'model' => $model,
            'model_raza' => (empty($models_razas)) ? [new RazaAnimal] : $models_razas,
            'model_peso' =>$model_peso,
        ]);
    }
    

    /**
     * Deletes an existing Animal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $parto_existe = Parto::find()->where(['animal_identificacion'=>$id])->count();
        $status = StatusEliminacion::find()->where(['animal_identificacion'=>$id])->one();
        
        if(!empty($status))
        {
            if($status->causa == 'V'){
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Esta animal se encuentra inactivo, motivo: VENTA',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']); 
        }

            if($status->causa == 'M'){
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Esta animal se encuentra inactivo, motivo: MUERTE',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']); 
        }

        }
        if($parto_existe>0)
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Esta vaca/novilla NO se puede ELIMINAR ya que posee partos registrados',
            'title' => '¡Error!',
            'positonY' => 'top',
            'positonX' => 'right'
            ]);

            return $this->redirect(['index']); 
        }

        $hijos = Animal::find()->where(['padre'=>$id])->count();

        if($hijos>0)
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
            'type' => 'danger',
            'duration' => 5000,
            'icon' => 'glyphicon glyphicon-error-sign',
            'message' => 'Este Toro NO se puede ELIMINAR ya que posee hijos registrados',
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
     * Finds the Animal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Animal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Animal::findOne($id)) !== null) {
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


    public function actionCrearPeso() {
        $modelo = new peso();
        $html = $this->render('crear-peso', [
        'model' => $modelo,
        ]);
        return Json::encode($html);
}

public function actionBasicos($id)
    {
        $model = Animal::find()
        ->select(["LPAD(identificacion, 6, '0') as identificacion_otro","imagen","sexo","GROUP_CONCAT(CONCAT(raza_animal.porcentaje, '', '%'),' ', raza_animal.raza_id_raza ORDER BY raza_animal.porcentaje DESC SEPARATOR ' y ') as raza"," CONCAT(TIMESTAMPDIFF(MONTH,animal.fecha_nacimiento,CURDATE()), ' ','meses') as edad","nuemro_arete","LPAD(madre, 6, '0') as madre_1","LPAD(padre, 6, '0') as padre_1","fecha_nacimiento"])
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->groupBy('animal.identificacion')
        ->where(['animal.identificacion'=>$id])
        ->one();

        /*$query=Animal::find()->select(["LPAD(identificacion, 6, '0') as identificacion","sexo","fecha_nacimiento","nuemro_arete","LPAD(madre, 6, '0') as madre","LPAD(padre, 6, '0') as padre"]);*/

        return $this->render('basicos', [
            'model' => $model,
            ]);
    }

    public function actionReproduccion($id)
    {

        $model_repro = Parto::find()
        ->where(['animal_identificacion'=>$id])
        ->one();

        $query = Animal::find();
        $query->select(["LPAD(animal.identificacion, 6, '0') as identificacion_otro","animal.sexo as sexo"," GROUP_CONCAT(CONCAT(raza_animal.porcentaje, '', '%'),' ', raza_animal.raza_id_raza ORDER BY raza_animal.porcentaje DESC SEPARATOR ' y ') as raza","animal.fecha_nacimiento","LPAD(animal.nuemro_arete,4,0) as arete","LPAD(animal.madre, 6, '0') as madre","LPAD(animal.padre, 6, '0') as padre","identificacion"])
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->where(['madre'=>$id])
        ->groupBy('identificacion_otro')
        ->orderBy('raza_animal.porcentaje')
        ->all();

        $hijos = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 10,
            ],
        ]);
       

        return $this->render('reproduccion', [
            'model_repro'=>$model_repro,
            'hijos'=>$hijos,
        ]);
    }

    public function actionPedigree($id)
    {
/*        $hijo = Animal::find()->where(['identificacion'=>$id])->one();

        $madre = Animal::find()
        ->select(["GROUP_CONCAT(CONCAT(raza_animal.porcentaje, '', '%'),' ', raza_animal.raza_id_raza ORDER BY raza_animal.porcentaje DESC SEPARATOR ' y ') as raza","identificacion","sexo"])
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->groupBy('animal.identificacion')
        ->where(['identificacion'=>$hijo->madre])
        ->one();

        echo "<pre>";
        print_r($madre);
        echo "</pre>";
        yii::$app->end();

        $padre = Animal::find()
        ->select(["GROUP_CONCAT(CONCAT(raza_animal.porcentaje, '', '%'),' ', raza_animal.raza_id_raza ORDER BY raza_animal.porcentaje DESC SEPARATOR ' y ') as raza","identificacion","sexo"])
        ->join('JOIN','raza_animal','raza_animal.animal_identificacion = animal.identificacion')
        ->groupBy('animal.identificacion')
        ->where(['identificacion'=>$hijo->padre])
        ->one();*/

        

      /*  return $this->render('pedigree', [
            'hijo'=>$hijo,
            'madre'=>$madre,
            'padre'=>$padre,
        ]);*/
    }


}
