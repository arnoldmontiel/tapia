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
		$modelPriority=Priority::model()->findAll();
		
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

		$criteria=new CDbCriteria;

		$criteria->select='MAX(review) as maxReview';
		$criteria->condition='Id_customer = '.$model->Id_customer ;
		
		$modelMax = Review::model()->find($criteria);

		$model->review = $modelMax->maxReview + 1;
		
		$this->render('create',array(
			'model'=>$model,
			'modelCustomer'=>$modelCustomer,
			'modelPriority'=>$modelPriority,
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
		$this->modelTag = $model;
		
		$ddlPriority = Priority::model()->findAll();
		
		$customer = User::getCustomer();
		if(isset($customer))
		{
			if(!$model->read)
			{
				 $model->read = 1;
				 $model->save();
			}
		}
		
		
		$modelNote = Note::model()->findByAttributes(array('in_progress'=>1, 'Id_review'=>$id));
		
		$this->render('update',array(
			'model'=>$model,
			'idNote'=>$modelNote->Id,
			'ddlPriority'=>$ddlPriority,
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
		
		$criteria=new CDbCriteria;
		
		$criteria->addCondition('t.Id IN(select Id_multimedia from multimedia_note where Id_note = '. $idNote.')');
		$criteria->addCondition('t.Id_review = '. $id);
		$criteria->addCondition('t.Id_multimedia_type = 1'); //image
		
		$modelMultimediaSelected = Multimedia::model()->findAll($criteria);
		
		$this->render('attachImages',array(
					'model'=>$model,
					'idNote'=>$idNote,
					'modelMultimediaSelected'=>$modelMultimediaSelected,
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
	
		$criteria=new CDbCriteria;
		
		$criteria->addCondition('t.Id IN(select Id_multimedia from multimedia_note where Id_note = '. $idNote.')');
		$criteria->addCondition('t.Id_review = '. $id);
		$criteria->addCondition('t.Id_multimedia_type = 3 or t.Id_multimedia_type = 4'); //docs (pdf or autocad)
		
		$modelMultimediaSelected = Multimedia::model()->findAll($criteria);
		
		$this->render('attachDocs',array(
						'model'=>$model,
						'idNote'=>$idNote,
						'modelMultimedia'=>$modelMultimedia,
						'modelMultimediaSelected'=>$modelMultimediaSelected,
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
		
		$this->showFilter = true;
		
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
	
	public function actionAjaxSetPriority()
	{
		$idPriority = $_POST['idPriority'];
		$id = $_POST['id'];
	
		$model=$this->loadModel($id);
		$model->Id_priority = $idPriority;

		$model->save();
	
	}
	
	public function actionAjaxAddTag()
	{
		$idTag = $_POST['idTag'];
		$id = $_POST['id'];
		
		$model = new TagReview();
		$model->Id_review = $id;
		$model->Id_tag = $idTag;
		$model->save();
	
	}
	
	public function actionAjaxRemoveTag()
	{
		$idTag = $_POST['idTag'];
		$id = $_POST['id'];
		
		$model = TagReview::model()->deleteByPk(array('Id_review'=>$id,'Id_tag'=>$idTag));
	
	}
	
	private function fillIndex($Id_customer, $arrFilters)
	{
		
		$review = new Review;
		$review->Id_customer = $Id_customer;

		$dataProvider = $review->searchSummary($arrFilters);
		
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
			$arrFilters = array('tagFilter'=>$_POST['tagFilter'], 
							 'typeFilter'=>$_POST['typeFilter'],
							 'dateFromFilter'=>$_POST['dateFromFilter'],
							 'dateToFilter'=>$_POST['dateToFilter']);
			
			$this->fillIndex($_POST['Id_customer'], $arrFilters);
		}		
	}
	
	public function actionAjaxUpdateNoteNeedConf()
	{
 		$chk = $_POST['chk'];
 		$id = $_POST['id'];
		$model= Note::model()->findByPk($id);
		
		if(isset($model))
		{
			$model->need_confirmation = $chk;
			$model->save();
		}
		$this->renderPartial('_viewData',array('data'=>$model));
	}
	
	public function actionAjaxConfirmNote()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$id=$_POST['id'];
			// we only allow deletion via POST request
			$model= Note::model()->findByPk($id);
				
			$model->confirmed = 1;
			$model->save();

			$this->renderPartial('_viewData',array('data'=>$model));
		}
		else
		throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function actionAjaxAddNote()
	{
		$id = $_POST['id'];
		$value = $_POST['value'];
		$idCustomer = $_POST['idCustomer'];
		$chk = $_POST['chk'];
		
		$modelNote = new Note;
	
		$transaction = $modelNote->dbConnection->beginTransaction();
		try {
			$modelNote->note = $value;
			$modelNote->Id_customer = $idCustomer;
			$modelNote->in_progress = 0;
			$modelNote->need_confirmation = $chk;
			$modelNote->save();
				
			$modelNoteNote = new NoteNote;
			$modelNoteNote->Id_parent = $id;
			$modelNoteNote->Id_child = $modelNote->Id;
			$modelNoteNote->save();
	
			$transaction->commit();
				
			$model = Note::model()->findByPk($id);
	
			$this->renderPartial('_viewData',array('data'=>$model));
		} catch (Exception $e) {
			$transaction->rollback();
		}
	}
	
	public function actionAjaxRemoveSingleNote($id, $idParent)
	{
	
		$model = Note::model()->findByPk($idParent);
		
		$transaction = $model->dbConnection->beginTransaction();
		try {
			NoteNote::model()->deleteAllByAttributes(array('Id_child'=>$id));
			Note::model()->deleteByPk($id);
			$transaction->commit();
			
			$this->renderPartial('_viewData',array('data'=>$model));
		} catch (Exception $e) {
			$transaction->rollback();
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
				$model->Id_customer = $_POST['Id_customer'];
				$model->Id_review = $_POST['Id_review'];
					
				$model->save();
					
				
				$transaction->commit();
				$this->redirect(array('update','id'=>$_POST['Id_review']));
					
			} catch (Exception $e) {
				$transaction->rollback();
			}
		}
	
	
	}
	
	public function actionUpdateDocuments($id)
	{
		
		$model=$this->loadModel($id);
		
		if(isset($model))	
			$this->render('updateDocument',array('model'=>$model));
	}
	
	public function actionUpdateAlbum($id)
	{
	
		$model = Album::model()->findByPk($id);
	
		if(isset($model))
			$this->render('updateAlbum',array('model'=>$model));
	}
	
	
	public function actionAjaxPublicNote()
	{
		$userGroup = $_POST['userGroup'];
		$canFeedback = $_POST['canFeedback'];
		$addressed = $_POST['addressed'];
		$idNote = $_POST['idNote'];
		$idCustomer = $_POST['idCustomer'];
		
		$model = new UserGroupNote;
		$transaction = $model->dbConnection->beginTransaction();
		try {
			
			$model->Id_note = $idNote;
			$model->Id_customer = $idCustomer;
			$model->Id_user_group = User::getCurrentUserGroup()->Id;
			$model->can_feedback = 1;
			$model->addressed = 1;
			$model->save();
			
			if($userGroup)
			{
				foreach($userGroup as $item)
				{
					$model = new UserGroupNote;
						
					$model->Id_note = $idNote;
					$model->Id_customer = $idCustomer;
					$model->Id_user_group = $item;
						
					if(isset($canFeedback) && in_array($item,$canFeedback))
						$model->can_feedback = 1;
			
					if(isset($addressed) && in_array($item,$addressed))
						$model->addressed = 1;
						
					$model->save();
				}
			}
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
		}
		
	}
	
	public function actionAjaxSaveNote()
	{
		$id = $_POST['id'];
		$model= Note::model()->findByPk($id);
		
		if(isset($model))
		{
			$model->in_progress = 0;
			$model->save();
			echo CHtml::openTag('div', array('class'=>'review-container-single-view','id'=>'noteContainer_'.$id));
			$this->renderPartial('_viewPendingData',array('data'=>$model));
			echo CHtml::closeTag('div');
		}
	}
}
