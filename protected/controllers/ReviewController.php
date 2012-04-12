<?php

class ReviewController extends Controller
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
		$model=new Review;
		$modelCustomer=new Customer;
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
		
		if(isset($_GET['Id_customer']))
		{
			$model->Id_customer=$_GET['Id_customer'];
			$modelCustomer = Customer::model()->findByPk($model->Id_customer);
		}
		if(isset($_POST['Review']))
		{
			$model->attributes=$_POST['Review'];
			if($model->save())
				$this->redirect(array('update','id'=>$model->Id));
		}

		$this->render('create',array(
			'model'=>$model,
			'modelCustomer'=>$modelCustomer
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id, $idNote=null)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

// 		if(isset($_POST['Review']))
// 		{
// 			$model->attributes=$_POST['Review'];
// 			if($model->save())
// 				$this->redirect(array('view','id'=>$model->Id));
// 		}
		
		$this->render('update',array(
			'model'=>$model,
			'idNote'=>$idNote,
		));
	}

	public function actionAjaxAttachImage($id, $idNote)
	{
		$model=$this->loadModel($id);
		
		$criteria=new CDbCriteria;
		
		$criteria->addCondition('t.Id NOT IN(select Id_multimedia from multimedia_note where Id_note = '. $idNote.')');
		$criteria->addCondition('t.Id_review = '. $id);
		$criteria->addCondition('t.Id_multimedia_type = 1'); //image
		
		$modelMultimedia = Multimedia::model()->findAll($criteria);
		
		$this->render('attachImages',array(
					'model'=>$model,
					'idNote'=>$idNote,
					'modelMultimedia'=>$modelMultimedia,
		));
	}
	
	public function actionAjaxAttachDoc($id, $idNote)
	{
		$model=$this->loadModel($id);
	
		$criteria=new CDbCriteria;
	
		$criteria->addCondition('t.Id NOT IN(select Id_multimedia from multimedia_note where Id_note = '. $idNote.')');
		$criteria->addCondition('t.Id_review = '. $id);
		$criteria->addCondition('t.Id_multimedia_type = 3 or t.Id_multimedia_type = 4'); //docs (pdf or autocad)
		
		$modelMultimedia = Multimedia::model()->findAll($criteria);
	
		$this->render('attachDocs',array(
						'model'=>$model,
						'idNote'=>$idNote,
						'modelMultimedia'=>$modelMultimedia,
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
		$modelMultimedia = new Multimedia;
		$modelNote = new Note;
		$ddlCustomer = Customer::model()->findAll();
		$Id_customer = -1;
		if(isset($_GET['Id_customer']))
		{
			$Id_customer =$_GET['Id_customer'];
		}
		$criteria=new CDbCriteria;
		$criteria->order = 't.Id desc';
		$this->render('index',
			array('modelMultimedia'=>$modelMultimedia,
					'modelNote'=>$modelNote,
					'ddlCustomer'=>$ddlCustomer,
					'Id_customer'=>$Id_customer,
			)
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Review('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Review']))
			$model->attributes=$_GET['Review'];

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
		$model=Review::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='review-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionAjaxUpdateReview()
	{
		$review = $_POST['review'];
		$id = $_POST['id'];
		$model=$this->loadModel($id);
		if(isset($model))
		{
			$model->review = $review;
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
	
	private function fillIndex($Id_customer, $tagFilter=null)
	{
		$criteria=new CDbCriteria;
		
		if($tagFilter)
			$criteria->addCondition('t.Id IN(select Id_review from tag_review where Id_tag IN ('. $tagFilter.'))');
		
		$criteria->addCondition('t.Id_customer = '. $Id_customer);
		
		
		$review = new Review;
		$dataProvider = new CActiveDataProvider($review, array(
					'criteria'=>$criteria,
		));
		$dataProvider->pagination->pageSize= 10;
		$data = $dataProvider->getData();
			
		foreach ($data as $item){
			$this->renderPartial('_view',array('data'=>$item));
		}
	}
	
	public function actionAjaxFillInbox()
	{
		if(isset($_POST['Id_customer']))
		{
			$this->fillIndex($_POST['Id_customer'], $_POST['tagFilter']);
		}		
	}
	
	public function actionAjaxShareDocument()
	{
		//$_FILES
		$file = $_FILES["file"];
	
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else
		{
	
			$multi = $_POST['Multimedia'];
			$model = new Multimedia;
	
			$transaction = $model->dbConnection->beginTransaction();
	
			try {
					
				$model->attributes = $multi;
				$model->uploadedFile = $file;
				$model->Id_multimedia_type = 3;
				$model->Id_customer = $_POST['Id_customer'];
				$model->Id_review = $_POST['Id_review'];
					
				$model->save();
					
				
				$transaction->commit();
				$this->redirect(array('review/index','Id_customer'=>$model->Id_customer));
					
			} catch (Exception $e) {
				$transaction->rollback();
			}
		}
	
	
	}
}
