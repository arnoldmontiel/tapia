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
		$modelReviewType=ReviewType::model()->findAll();
		
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
			'modelReviewType'=>$modelReviewType,
		));
	}

	public function actionAjaxGetNextReviewIndex()
	{
		$idCustomer = $_POST['idCustomer'];
		$idReviewType = $_POST['idReviewType'];
		
		$criteria=new CDbCriteria;
		
		$criteria->select='MAX(review) as maxReview';
		$criteria->condition='Id_customer = '.$idCustomer ;
		$criteria->addCondition('Id_review_type = '.$idReviewType);
		
		$modelMax = Review::model()->find($criteria);
		
		echo $modelMax->maxReview + 1;
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id, $order=null)
	{
		$model=$this->loadModel($id);
		$this->modelTag = $model;
		
		$ddlPriority = Priority::model()->findAll();
		$ddlReviewType = ReviewType::model()->findAll();
		
	    $modelReviewUser = ReviewUser::model()->findByPk(array('Id_review'=>$id,'username'=>User::getCurrentUser()->username));
		
		if($modelReviewUser)
		{
			if(!$modelReviewUser->read)
			{
				 $modelReviewUser->read = 1;
				 $modelReviewUser->save();
			}
		}
		
		
		$modelNote = Note::model()->findByAttributes(array('in_progress'=>1, 'Id_review'=>$id, 'username'=>User::getCurrentUser()->username ));
		$idNote = null; 
		if(isset($modelNote))
		{
			$idNote = $modelNote->Id;				
		}
				
		$modelMultimedia = Multimedia::model()->findAllByAttributes(array('Id_review'=>$id, 'Id_user_group'=>User::getCurrentUserGroup()->Id ));
		$modelAlbum = Album::model()->findAllByAttributes(array('Id_review'=>$id, 'Id_user_group_owner'=>User::getCurrentUserGroup()->Id ));
		
		$this->render('update',array(
			'model'=>$model,
			'idNote'=>$idNote,
			'ddlPriority'=>$ddlPriority,
			'ddlReviewType'=>$ddlReviewType,
			'modelMultimedia'=>$modelMultimedia,
			'modelAlbum'=>$modelAlbum,
			'order'=>$order,
		));
	}

	public function actionAjaxAttachImage($id, $idNote)
	{
		$model=$this->loadModel($id);
		
		$criteria=new CDbCriteria;
		
		$criteria->addCondition('t.Id NOT IN(select Id_multimedia from multimedia_note where Id_note = '. $idNote.')');
		$criteria->addCondition('t.Id_user_group = '. User::getCurrentUserGroup()->Id);
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
		$criteria->addCondition('t.Id_user_group = '. User::getCurrentUserGroup()->Id);
		$criteria->addCondition('t.Id_review = '. $id);
		$criteria->addCondition('t.Id_multimedia_type >= 3'); //docs (pdf or autocad)
		
		$modelMultimedia = Multimedia::model()->findAll($criteria);
	
		$criteria=new CDbCriteria;
		
		$criteria->addCondition('t.Id IN(select Id_multimedia from multimedia_note where Id_note = '. $idNote.')');
		$criteria->addCondition('t.Id_review = '. $id);
		$criteria->addCondition('t.Id_multimedia_type >= 3'); //docs (pdf or autocad)
		
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
		$model = $this->loadModel($id);
		$idCustomer = $model->Id_customer; 
		
		$model->delete();
		
		$this->redirect(array('index','Id_customer'=>$idCustomer));
	
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$modelMultimedia = new Multimedia;
		$modelNote = new Note;
		
		if(User::isAdministartor())
		{
			$ddlCustomer = Customer::model()->findAll();
		}
		else
		{
			$criteria=new CDbCriteria;
			if(User::getCustomer())
				$criteria->addCondition('t.Id  = '. User::getCustomer()->Id);
			else 
				$criteria->addCondition('t.Id IN(select Id_customer from user_customer where username = "'. User::getCurrentUser()->username.'")');
			
			$ddlCustomer = Customer::model()->findAll($criteria);
		}
		
		$Id_customer = -1;
		if(isset($_GET['Id_customer']))
		{
			$Id_customer =$_GET['Id_customer'];
		}

		
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
	
	public function actionAjaxCheckUpdate()
	{
		$id = $_POST['id'];
		
		$modelReviewUser = ReviewUser::model()->findByAttributes(array('Id_review'=>$id,'username'=>User::getCurrentUser()->username));
		echo isset($modelReviewUser)?
			$modelReviewUser->read:
			1;	
	}
	
	public function actionAjaxSetPriority()
	{
		$idPriority = $_POST['idPriority'];
		$id = $_POST['id'];
	
		$model=$this->loadModel($id);
		$model->Id_priority = $idPriority;

		$model->save();
	
	}
	
	public function actionAjaxSetReviewType()
	{
		$idReviewType = $_POST['idReviewType'];
		$id = $_POST['id'];
	
		$model=$this->loadModel($id);
		$model->Id_review_type = $idReviewType;
	
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
		
		$dataProvider->pagination->pageSize= 20;
		
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
							 'reviewTypeFilter'=>$_POST['reviewTypeFilter'],
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
			
			$modelUserGroupNote = UserGroupNote::model()->findByAttributes(array('Id_user_group'=>User::getCurrentUserGroup()->Id, 'Id_note'=>$id));
			$modelUserGroupNote->confirmed = 1;
			$modelUserGroupNote->save();

			$this->markAsUnread($model);
			
			$this->renderPartial('_viewData',array('data'=>$model,'dataUserGroupNote'=>$modelUserGroupNote));
				
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	private function markAsUnread($model)
	{
		$reviewUsers = ReviewUser::model()->findAllByAttributes(array('Id_review'=>$model->Id_review));
		foreach($reviewUsers as $item)
		{
			if($item->user->Id_user_group == $model->Id_user_group_owner && $item->username != User::getCurrentUser()->username)
			{
				$item->read = 0;
				$item->save();
			}
		}	
	}
	
	public function actionAjaxDeclineNote()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$id=$_POST['id'];
			// we only allow deletion via POST request
			$model= Note::model()->findByPk($id);
			
			$modelUserGroupNote = UserGroupNote::model()->findByAttributes(array('Id_user_group'=>User::getCurrentUserGroup()->Id, 'Id_note'=>$id));
			$modelUserGroupNote->declined = 1;
			$modelUserGroupNote->save();				
			
			$this->markAsUnread($model);
			
			$this->renderPartial('_viewData',array('data'=>$model,'dataUserGroupNote'=>$modelUserGroupNote));				
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
			
			$modelNote->username = User::getCurrentUser()->username;
			$modelNote->Id_user_group_owner = User::getCurrentUserGroup()->Id;
			$modelNote->note = $value;
			$modelNote->Id_customer = $idCustomer;
			$modelNote->in_progress = 0;
			$modelNote->need_confirmation = $chk;
			$modelNote->save();
				
			$modelNoteNote = new NoteNote;
			$modelNoteNote->Id_parent = $id;
			$modelNoteNote->Id_child = $modelNote->Id;
			$modelNoteNote->save();
	
			$this->markUnreadSubNote($id);
			
			$transaction->commit();
				
			$model = Note::model()->findByPk($id);
			$userGroupNote = UserGroupNote::model()->findByPk(array('Id_note'=>$id,'Id_user_group'=>$modelNote->Id_user_group_owner));				
			$this->renderPartial('_viewData',array('data'=>$model,'dataUserGroupNote'=>$userGroupNote));
			
		} catch (Exception $e) {
			$transaction->rollback();
		}
	}
	
	private function markUnreadSubNote($idParentNote)
	{
		$model = Note::model()->findByPk($idParentNote);
		$reviewUsers = ReviewUser::model()->findAllByAttributes(array('Id_review'=>$model->Id_review));
		$modelUserGroupNotes = UserGroupNote::model()->findAllByAttributes(array('Id_note'=>$model->Id));
		
		foreach($modelUserGroupNotes as $item)
		{
			foreach($reviewUsers as $revItems)
			{
				if($revItems->user->Id_user_group == $item->Id_user_group && $revItems->username != User::getCurrentUser()->username )
				{
					$revItems->read = 0;
					$revItems->save();
				}
			}
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
			
			$userGroupNote = UserGroupNote::model()->findByPk(array('Id_note'=>$model->Id,'Id_user_group'=>$model->Id_user_group_owner));
			$this->renderPartial('_viewData',array('data'=>$model,'dataUserGroupNote'=>$userGroupNote));
			
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
	public function actionUpdateAlbumIE($id)
	{
	
		$model = Album::model()->findByPk($id);
	
		if(isset($model))
			$this->render('updateAlbumIE',array('model'=>$model));
	}
	public function actionAjaxSavePermissions()
	{
		$response = 'error';
		if(
			isset($_POST['idUserGroup'])&&
			isset($_POST['idNote'])&&
			isset($_POST['value'])&&
			isset($_POST['type'])&&
			isset($_POST['idCustomer'])
		)
		{
			$idCustomer = $_POST['idCustomer'];
			$idUserGroup = $_POST['idUserGroup'];
			$idNote = $_POST['idNote'];
			$value = $_POST['value']=='true'?1:0;
			$type= $_POST['type'];
			$modelUserGroupNoteArray = UserGroupNote::model()->findAllByAttributes(array('Id_note'=>$idNote,'Id_user_group'=>$idUserGroup));
			
			if(isset($modelUserGroupNoteArray[0]))
			{
				$modelUserGroupNote = $modelUserGroupNoteArray[0];				
				$modelNoteNote = NoteNote::model()->findAllByAttributes(array('Id_parent'=>$idNote));
				$canEditFeedback = true;
				foreach($modelNoteNote as $itemNoteNote)
				{
					if($itemNoteNote->child->Id_user_group_owner == $idUserGroup)
					{
						$canEditFeedback = false;
						break;
					}
				}
				$canEditNeedConf = !($modelUserGroupNote->confirmed || $modelUserGroupNote->declined);
				
				if($type=='canSee')
				{
					$response = 'forbidden';
					if(!$value&&$canEditNeedConf&&$canEditFeedback)
					{
						$modelUserGroupNote->delete();
						$response = 'ok';
					}				
				}
				elseif($type=='addressed')
				{
					$response = 'forbidden';
					$modelUserGroupNote->addressed = $value;				
					$modelUserGroupNote->save();						
					$response = 'ok';
				}
				elseif($type=='canFeedback')
				{
					$response = 'forbidden';
					if($canEditFeedback)
					{
						$modelUserGroupNote->can_feedback = $value;
						$modelUserGroupNote->save();						
						$response = 'ok';
					}
				}
				elseif($type=='needConfirmation')
				{
					$response = 'forbidden';
					if($canEditNeedConf){
						$modelUserGroupNote->need_confirmation = $value;
						$modelUserGroupNote->save();						
						$response = 'ok';
					}
				}				
			}
			else 
			{
				$modelUserGroupNote = new UserGroupNote;
				$modelUserGroupNote->Id_note=$idNote;
				$modelUserGroupNote->Id_user_group=$idUserGroup;
				$modelUserGroupNote->Id_customer=$idCustomer;
				if($type=='addressed')
				{
					$modelUserGroupNote->addressed = $value;				
				}
				elseif($type=='canFeedback')
				{
					$modelUserGroupNote->can_feedback = $value;				
				}
				elseif($type=='needConfirmation')
				{
					$modelUserGroupNote->need_confirmation = $value;				
				}				
				$response = 'ok';
				$modelUserGroupNote->save();
			}
			//Envio de Mail
			
// 			$userGroup = UserGroup::model()->findByPk($idUserGroup);
// 			$users = $userGroup->users;
// 			foreach($users as $user)
// 			{
// 				$message = new YiiMailMessage;
// 				$message->view = '_noteMail';
// 				$message->setBody(array('model'=>$user), 'text/html');
// 				$message->addTo('arnaldomontiel@gmail.com');
// 				$message->from = 'arnaldomontiel@gmail.com';
// 				Yii::app()->mail->send($message);				
// 			}
		}
		echo $response;

	}
	
	public function actionAjaxPublicNote()
	{
		$userGroup = (isset($_POST['userGroup'])?$_POST['userGroup']:null);
		$canFeedback = (isset($_POST['canFeedback'])?$_POST['canFeedback']:null);
		$addressed = (isset($_POST['addressed'])?$_POST['addressed']:null);
		$needConf = (isset($_POST['needConf'])?$_POST['needConf']:null);
		$idNote = (isset($_POST['idNote'])?$_POST['idNote']:null);
		$idCustomer = (isset($_POST['idCustomer'])?$_POST['idCustomer']:null);
		
		$model = new UserGroupNote;
		$transaction = $model->dbConnection->beginTransaction();
		try {
			
			UserGroupNote::model()->deleteAllByAttributes(array('Id_note'=>$idNote, 'confirmed'=>0, 'declined'=>0));
			
			$arrUserGroupNote = UserGroupNote::model()->findAllByAttributes(array('Id_note'=>$idNote));
			
			foreach ($arrUserGroupNote as $userGroupItem)
			{
				if(isset($canFeedback) && in_array($userGroupItem->Id_user_group,$canFeedback))
					$userGroupItem->can_feedback = 1;
				
				if(isset($addressed) && in_array($userGroupItem->Id_user_group,$addressed))
					$userGroupItem->addressed = 1;
					
				if(isset($needConf) && in_array($userGroupItem->Id_user_group,$needConf))
					$userGroupItem->need_confirmation = 1;
					
				$userGroupItem->save();
				
				$userGroup = array_diff($userGroup, array($userGroupItem->Id_user_group));
			}
			
			$model->Id_note = $idNote;
			$model->Id_customer = $idCustomer;
			$model->Id_user_group = User::getCurrentUserGroup()->Id;
			$model->can_feedback = 1;
			$model->save();
			
			//review-user First insert
			$modelNote = Note::model()->findByPk($idNote);
			$modelReviewUserDb = ReviewUser::model()->findByPk(array('Id_review'=>$modelNote->Id_review,'username'=>User::getCurrentUser()->username));
			if(!$modelReviewUserDb)
			{
				$modelReviewUser = new ReviewUser;
				$modelReviewUser->Id_review = $modelNote->Id_review;
				$modelReviewUser->username = User::getCurrentUser()->username;
				$modelReviewUser->save();
			}
			
			
			if($userGroup)
			{
				if( in_array(User::getAdminUserGroupId(),$userGroup))
				{
					$model = new UserGroupNote;
					$model->Id_note = $idNote;
					$model->Id_customer = $idCustomer;
					$model->Id_user_group = User::getAdminUserGroupId();
					$model->can_feedback = 1;
					if(isset($addressed) && in_array(User::getAdminUserGroupId(),$addressed))
						$model->addressed = 1;
					
					$model->save();
					$userGroup = array_diff($userGroup, array(User::getAdminUserGroupId()));
	
				}
				elseif( ! User::isAdministartor())
				{
					$model = new UserGroupNote;
					$model->Id_note = $idNote;
					$model->Id_customer = $idCustomer;
					$model->Id_user_group = User::getAdminUserGroupId();
					$model->can_feedback = 1;
					$model->save();
				}
				
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
					
					if(isset($needConf) && in_array($item,$needConf))
						$model->need_confirmation = 1;
					
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
