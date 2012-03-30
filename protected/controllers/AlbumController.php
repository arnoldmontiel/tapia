<?php

class AlbumController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		

		if(isset($_POST['Album']))
		{
			$model=$this->loadModel($_POST['idAlbum']);
			$model->attributes=$_POST['Album'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Id));
		}
		else{
			$model=new Album;
			$transaction = $model->dbConnection->beginTransaction();
			try {
				$model->Id_customer = 1;
				$model->save();
				$modelWall = new Wall;
				$modelWall->attributes = array('Id_customer'=>$model->Id_customer,
												'Id_album'=>$model->Id, 
												'index_order'=>$this->getNextIndexOrder());
				$modelWall->save();
				
				$transaction->commit();
			} catch (Exception $e) {
				$transaction->rollback();
			}
		}
		
		$this->render('create',array(
			'model'=>$model,
		));
	}

	private function getNextIndexOrder()
	{
		$index = 1;
		$criteria=new CDbCriteria;
		$criteria->select = 'max(t.index_order) AS index_order';
			
		$singleRow = Wall::model()->find($criteria);
			
		if(isset($singleRow) && isset($singleRow['index_order']))
		$index = (int)$singleRow['index_order'] + $index;
	
		return $index;
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Album']))
		{
			$model->attributes=$_POST['Album'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionAjaxUpload($id)
	{

		$file = $_FILES['file'];
		
		$modelMultimedia = new Multimedia;
 		
 		$modelMultimedia->Id_album = $id;
 		$modelMultimedia->uploadedFile = $file;
 		$modelMultimedia->Id_multimedia_type = 1;
 		$modelMultimedia->Id_customer = 1;
 		
 		$modelMultimedia->save();
 		
 		$img = "<img alt='Click to follow' src='" ."images/" . $modelMultimedia->file_name_small . "'" ;
 		$size = round($modelMultimedia->size/1024,2);
 		
		echo json_encode(array("name" => $img,"type" => '',"size"=> $size, "id"=>$modelMultimedia->Id));
	}
	
	public function actionAjaxCancelAlbum()
	{
		$id = $_GET['id'];
		$model=$this->loadModel($id);
		
		$transaction = $model->dbConnection->beginTransaction();
		try {
			Multimedia::model()->deleteAllByAttributes(array('Id_album'=>$id));
			$model->delete();
			$transaction->commit();
			$this->redirect(array('index'));
		} catch (Exception $e) {
			$transaction->rollback();
		}
	}
	
	public function actionAjaxClose()
	{
		$this->redirect(array('index'));
	}
	
	public function actionAjaxUpdateTitle()
	{
		$title = $_POST['title'];
		$id = $_POST['id'];
		$model=$this->loadModel($id);
		if(isset($model))
		{
			$model->title = $title;
			$model->save();
		}
		
	}
	
	public function actionAjaxUpdateDescription()
	{
		$description = $_POST['description'];
		$id = $_POST['id'];
		$model=$this->loadModel($id);
		if(isset($model))
		{
			$model->description = $description;
			$model->save();
		}
	
	}
	
	public function actionAjaxRemoveImage()
	{	
			
		$idMultimedia = isset($_GET['IdMultimedia'])?$_GET['IdMultimedia']:null;
		$model = Multimedia::model()->findByPk($idMultimedia);
		$this->unlinkFile($model);
		$model->delete();
		
	}
	
	private function unlinkFile($model)
	{
		$imagePath = 'images/';
		$docPath = 'docs/';
		if($model->Id_multimedia_type == 1)
		unlink($imagePath.$model->file_name);
		else
		unlink($docPath.$model->file_name);
	
		unlink($imagePath.$model->file_name_small);
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model = $this->loadModel($id);

			$transaction = $model->dbConnection->beginTransaction();
			try {
				Multimedia::model()->deleteAllByAttributes(array('Id_album'=>$id));
				$model->delete();
				$transaction->commit();
				// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
				if(!isset($_GET['ajax']))
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			} catch (Exception $e) {
				$transaction->rollback();
			}
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Album');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Album('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Album']))
			$model->attributes=$_GET['Album'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Album::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='album-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
