<?php

class NoteController extends Controller
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
		$model=new Note;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Note']))
		{
			$model->attributes=$_POST['Note'];
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

		if(isset($_POST['Note']))
		{
			$model->attributes=$_POST['Note'];
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
		$dataProvider=new CActiveDataProvider('Note');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Note('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Note']))
			$model->attributes=$_GET['Note'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionAjaxUpdateNoteDesc()
	{
		$note = $_POST['note'];
		$id = $_POST['id'];
		$model=$this->loadModel($id);
		if(isset($model))
		{
			$model->note = $note;
			$model->save();
		}
	
	}
	
	public function actionAjaxRemoveResourceFromNote()
	{
			
		$idMultimedia = isset($_GET['IdMultimedia'])?$_GET['IdMultimedia']:null;
		$idNote = isset($_GET['IdNote'])?$_GET['IdNote']:null;
		MultimediaNote::model()->deleteByPk(array('Id_multimedia'=>$idMultimedia, 'Id_note'=>$idNote));
	
	}
	
	public function actionAjaxAttachImage()
	{
		$images = (isset($_POST['images']))?$_POST['images']:null;
		$id = $_POST['id'];
		
		$modelNote =$this->loadModel($id);
		$transaction =  $modelNote->dbConnection->beginTransaction();
		
		$criteria=new CDbCriteria;
		
		$criteria->addCondition('Id_multimedia IN(select Id from multimedia where Id_multimedia_type IN ( 1))');
		
		MultimediaNote::model()->deleteAllByAttributes(array('Id_note'=>$id),$criteria);
		
		try {
			if($images)
			{
				foreach($images as $item)
				{
					$model = new MultimediaNote;
					$model->Id_note = $id;
					$model->Id_multimedia = $item;
					$model->save();
				}
			}
			$transaction->commit();	
		} catch (Exception $e) {
			$transaction->rollback();
		}
	
	}
	
	public function actionAjaxAttachDoc()
	{
		$docs = $_POST['docs'];
		$id = $_POST['id'];
		
		$modelNote =$this->loadModel($id);
		$transaction =  $modelNote->dbConnection->beginTransaction();
		
		$criteria=new CDbCriteria;
		
		$criteria->addCondition('Id_multimedia IN(select Id from multimedia where Id_multimedia_type IN ( 3,4))');
		
		MultimediaNote::model()->deleteAllByAttributes(array('Id_note'=>$id),$criteria);
		try {
			if($docs)
			{
				foreach($docs as $item)
				{
					$model = new MultimediaNote;
					$model->Id_note = $id;
					$model->Id_multimedia = $item;
					$model->save();
				}
			}
			$transaction->commit();	
		} catch (Exception $e) {
			$transaction->rollback();
		}
	}
	
	public function actionAjaxPublicNote()
	{
		$id = $_POST['id'];
		$model=$this->loadModel($id);
		
		if(isset($model))
		{
			$model->in_progress = 0;
			$model->save();
		}
	}
	
	public function actionAjaxCancelNote()
	{
		if(isset($_POST['Note_Id_note']))
		{
			$modelNote = Note::model()->findByPk($_POST['Note_Id_note']);
			$transaction = $modelNote->dbConnection->beginTransaction();
			try {
					
				$modelNote->delete();
				$transaction->commit();
			} catch (Exception $e) {
				$transaction->rollback();
			}
		}
	}
	
	public function actionAjaxCreateNote()
	{
	
		if(isset($_POST['idCustomer']))
		{
			$modelNote = new Note;
			
			$modelNote->Id_customer = $_POST['idCustomer'];
			$modelNote->Id_review = $_POST['idReview'];
			$modelNote->username = User::getCurrentUser()->username;
			$modelNote->Id_user_group_owner = User::getCurrentUserGroup()->Id;
			$modelNote->save();
	
			
			echo $modelNote->Id;
				
		}
	}
	public function actionAjaxDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$id=$_POST['id'];
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
		}
		else
		throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Note::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='note-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
