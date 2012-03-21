<?php

class WallController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
		$model=new Wall;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Wall']))
		{
			$model->attributes=$_POST['Wall'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['Wall']))
		{
			$model->attributes=$_POST['Wall'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$_FILES;
		
		$criteria=new CDbCriteria;
		$criteria->order = 't.Id desc';
		$dataProvider = new CActiveDataProvider ('Wall', array (
				    	'criteria' => $criteria,
				    	'pagination' => array ( 
				        					'PageSize' => 7, 
		)
		));
		$model = new Multimedia;
		$this->render('index',array('model'=>$model,
												'dataProvider'=>$dataProvider));
		
	}
	
	public function actionManage()
	{
		$ddlSource = Customer::model()->findAll();
	
		$criteria=new CDbCriteria;
		$criteria->order = 't.Id desc';
		$dataProvider = new CActiveDataProvider ('Wall', array (
					    	'criteria' => $criteria,
					    	'pagination' => array ( 
					        					'PageSize' => 7, 
		)
		));
	
		$model=new Wall('search');
		$model->unsetAttributes();  // clear any default values
	
		$this->render('manage',array(
					'model'=>$model,
					'ddlSource'=>$ddlSource,
					'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionUpload()
	{
		Yii::import("ext.EAjaxUpload.qqFileUploader");
	
		$folder='./images/';// folder for uploaded files
		$allowedExtensions = array("jpg");//array("jpg","jpeg","gif","exe","mov" and etc...
		$sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($folder);
		$result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	
		$fileSize=filesize($folder.$result['filename']);//GETTING FILE SIZE
		$fileName=$result['filename'];//GETTING FILE NAME
	
		echo $result;// it's array
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Wall('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Wall']))
			$model->attributes=$_GET['Wall'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionAjaxShareImage()
	{
		$multi = $_POST['Multimedia'];
		if(isset($multi))
		{
			$model = new Multimedia;
			
			$transaction = $model->dbConnection->beginTransaction();
			
			try {

				$model->attributes = $multi;
				$model->Id_customer = 1;
				$model->Id_multimedia_type = 1;
				$file= CUploadedFile::getInstance($model,'uploadedFile');
				
				$model->save();
				//$_FILES
					
				$modelWall = new Wall;
				$modelWall->attributes = array('Id_customer'=>1,
													'Id_multimedia'=>$model->Id, 
													'index_order'=>$this->getNextIndexOrder());
				$modelWall->save();
				
			} catch (Exception $e) {
				$transaction->rollback();
			}
			
			
		}
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
	
	public function actionAjaxShareNote()
	{
		$note = trim($_POST['notes']);
		if(!empty($note))
		{
			$modelNote = new Note;
				
			$transaction = $modelNote->dbConnection->beginTransaction();
			try {
	
				$modelNote->attributes = array('Id_customer'=>1,'note'=>$note);
				$modelNote->save();
					
				$modelWall = new Wall;
				$modelWall->attributes = array('Id_customer'=>1,
																'Id_note'=>$modelNote->Id, 
																'index_order'=>$this->getNextIndexOrder());
				$modelWall->save();
				$transaction->commit();
			} catch (Exception $e) {
				$transaction->rollback();
			}
				
		}
	}
	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Wall::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='wall-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}