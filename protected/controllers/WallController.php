<?php

class WallController extends Controller
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
		$this->render('index',array('modelMultimedia'=>$modelMultimedia,
			'modelNote'=>$modelNote,
			'ddlCustomer'=>$ddlCustomer,
			'Id_customer'=>$Id_customer,
		)
		);		
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

	public function actionAjaxRemoveBubble($id)
	{
		$model = $this->loadModel($id);
		$transaction = $model->dbConnection->beginTransaction();
		try {
		    
			$model->delete();
			
			if(isset($model->Id_multimedia)){
				$this->removeMultimediaRelation(MultimediaNote::model()->findAllByAttributes(array('Id_multimedia'=>$model->Id_multimedia)));
				$this->unlinkFile(Multimedia::model()->findByPk($model->Id_multimedia));
		    	Multimedia::model()->deleteByPk($model->Id_multimedia);
			}
		    if(isset($model->Id_note)){
		    	$this->removeNoteRelation(NoteNote::model()->findAllByAttributes(array('Id_parent'=>$model->Id_note)));
		    	Note::model()->deleteByPk($model->Id_note);
		    }
		    if(isset($model->Id_album))
		    {
		    	$this->unlinkAlbumFiles(Multimedia::model()->findAllByAttributes(array('Id_album'=>$model->Id_album)));
		    	$this->removeAlbumRelation(AlbumNote::model()->findAllByAttributes(array('Id_album'=>$model->Id_album)));
		    	Album::model()->deleteByPk($model->Id_album);
		    }
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
		}
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
	
	private function unlinkAlbumFiles($arrModel)
	{
		foreach ($arrModel as $model)
		{
			$this->unlinkFile($model);
			$model->delete();
		}
	}
	
	private function removeMultimediaRelation($arrModel)
	{
		foreach ($arrModel as $model)
		{
			MultimediaNote::model()->deleteAllByAttributes(array('Id_note'=>$model->Id_note, 'Id_multimedia'=>$model->Id_multimedia));
			Note::model()->deleteByPk($model->Id_note);
		}	
	}
	
	private function removeNoteRelation($arrModel)
	{
		foreach ($arrModel as $model)
		{
			NoteNote::model()->deleteAllByAttributes(array('Id_parent'=>$model->Id_parent, 'Id_child'=>$model->Id_child));
			Note::model()->deleteByPk($model->Id_child);
		}
	}
	
	private function removeAlbumRelation($arrModel)
	{
		foreach ($arrModel as $model)
		{
			AlbumNote::model()->deleteAllByAttributes(array('Id_note'=>$model->Id_note, 'Id_album'=>$model->Id_album));
			Note::model()->deleteByPk($model->Id_note);
		}
	}
	
	public function actionAjaxAddNoteTo()
	{
		$id = $_POST['id'];
		$value = $_POST['value'];
		$side = $_POST['side'];
		$type = $_POST['type'];
		$idCustomer = $_POST['idCustomer'];
		$modelNote = new Note;
		
		$transaction = $modelNote->dbConnection->beginTransaction();
		try {
			$modelNote->note = $value;
			$modelNote->Id_customer = $idCustomer;
			$modelNote->save();
			
			switch ($type) {
				case "multimedia":
					
					$modelMultimediaNote = new MultimediaNote;
					$modelMultimediaNote->Id_multimedia = $id;
					$modelMultimediaNote->Id_note = $modelNote->Id;
					$modelMultimediaNote->save();
					$model = Wall::model()->findByAttributes(array('Id_multimedia'=>$id));
					break;
				case "album":
					
					$modelAlbumNote = new AlbumNote;
					$modelAlbumNote->Id_album = $id;
					$modelAlbumNote->Id_note = $modelNote->Id;
					$modelAlbumNote->save();
					$model = Wall::model()->findByAttributes(array('Id_album'=>$id));
					break;
				default :
					$modelNoteNote = new NoteNote;
					$modelNoteNote->Id_parent = $id;
					$modelNoteNote->Id_child = $modelNote->Id;
					$modelNoteNote->save();
					$model = Wall::model()->findByAttributes(array('Id_note'=>$id));
					break;
			}
			
			
			$transaction->commit();
			
			$this->renderPartial($side,array('data'=>$model));
		} catch (Exception $e) {
			$transaction->rollback();
		}
	}
	
	public function actionAjaxRemoveSingleNote($id, $type, $side, $idParent)
	{
		
		switch ($type) {
			case "multimedia":
				MultimediaNote::model()->deleteAllByAttributes(array('Id_note'=>$id));
				Note::model()->deleteByPk($id);
				break;
			case "album":
				AlbumNote::model()->deleteAllByAttributes(array('Id_note'=>$id));
				Note::model()->deleteByPk($id);
				break;
			default :
				NoteNote::model()->deleteAllByAttributes(array('Id_child'=>$id));
				Note::model()->deleteByPk($id);
				break;
		}
		$model = Wall::model()->findByPk($idParent);
		$this->renderPartial($side,array('data'=>$model));
	}
	
	public function actionAjaxShareImage()
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
				$model->Id_multimedia_type = $_POST['docType'];
			
				$model->save();
					
				$modelWall = new Wall;
				$modelWall->attributes = array('Id_customer'=>$model->Id_customer,
																'Id_multimedia'=>$model->Id, 
																'index_order'=>$this->getNextIndexOrder());
				$modelWall->save();
				$transaction->commit();
				$this->redirect(array('wall/index','Id_customer'=>$model->Id_customer));
				$this->fillWall($_POST['Note']['Id_customer']);
					
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

		if(isset($_POST['Note']))
		{
			$str = trim($_POST['Note']['note']);
			if(!empty($str))
			{
				$modelNote = new Note;
				$modelNote->attributes = $_POST['Note'];
				$transaction = $modelNote->dbConnection->beginTransaction();
				try {
		
					$modelNote->save();
						
					$modelWall = new Wall;
					$modelWall->attributes = array('Id_customer'=>$modelNote->Id_customer,
																	'Id_note'=>$modelNote->Id, 
																	'index_order'=>$this->getNextIndexOrder());
					$modelWall->save();
					$transaction->commit();
				} catch (Exception $e) {
					$transaction->rollback();
				}
			}
			$this->fillWall($_POST['Note']['Id_customer']);
		}
	}
	private function fillWall($Id_customer)
	{
		$wall = new Wall;
		$wall->Id_customer = $Id_customer;
		$dataProvider = $wall->searchOrderedByIndex();
		$dataProvider->pagination->pageSize= 4;		
		$data = $dataProvider->getData();
			
		echo CHtml::openTag('div',array('class'=>'view-index'));
		$left=true;
		$first = true;
		foreach ($data as $item){
			if($left)
			{
				$left=false;
				$this->renderPartial('_viewLeft',array('data'=>$item));
			}else
			{
				$left=true;
				$this->renderPartial('_viewRight',array('data'=>$item,'first'=>$first));
				$first=false;
			}
		}
		echo CHtml::closeTag('div');
	}
	public function actionAjaxFillNextWall()
	{
		if(isset($_POST['Id_customer'])&&isset($_POST['lastId'])&&isset($_POST['lastLeft']))
		{
			$wall = new Wall;
			$wall->Id_customer = $_POST['Id_customer'];
			$dataProvider = $wall->searchOrderedByIndexSince($_POST['lastId']);
			$dataProvider->pagination->pageSize= 4;				
			$data = $dataProvider->getData();
				
			echo CHtml::openTag('div',array('class'=>'view-index'));
			$left=$_POST['lastLeft']==1?false:trueS;
			$first = false;
			foreach ($data as $item){
				if($left)
				{
					$left=false;
					$this->renderPartial('_viewLeft',array('data'=>$item));
				}else
				{
					$left=true;
					$this->renderPartial('_viewRight',array('data'=>$item,'first'=>$first));
				}
			}
			echo CHtml::closeTag('div');				
		}
	}
	
	public function actionAjaxFillWall()
	{

		if(isset($_POST['Id_customer']))
		{
			$this->fillWall($_POST['Id_customer']);
		}
	}
	
	public function actionSeach()
	{
		
	}	
	public function actionAjaxCancelAlbum()
	{	
		if(isset($_POST['Album_Id_album']))
		{
			$modelAlbum = Album::model()->findByPk($_POST['Album_Id_album']);
			$transaction = $modelAlbum->dbConnection->beginTransaction();
			try {
					
				Multimedia::model()->deleteAllByAttributes(array('Id_album'=>$modelAlbum->Id));
				Wall::model()->deleteAllByAttributes(array('Id_album'=>$modelAlbum->Id));
				$modelAlbum->delete();
				$transaction->commit();
			} catch (Exception $e) {
				$transaction->rollback();
			}	
		}
	}
	public function actionAjaxCreateAlbum()
	{	
		if(isset($_POST['Id_customer']))
		{
			$modelAlbum = new Album;
			$modelAlbum->Id_customer = $_POST['Id_customer'];
			$transaction = $modelAlbum->dbConnection->beginTransaction();
			try {
		
				$modelAlbum->save();
					
				$modelWall = new Wall;
				$modelWall->attributes = 
					array('Id_customer'=>$modelAlbum->Id_customer,
						'Id_album'=>$modelAlbum->Id, 
						'index_order'=>$this->getNextIndexOrder()
				);
				$modelWall->save();
				$transaction->commit();
			} catch (Exception $e) {
				$transaction->rollback();
			}
			echo $modelAlbum->Id;
			//echo CHtml::openTag('div',array('class'=>'wall-action-area-album-dialog'));
			//echo CHtml::closeTag('div');
			//$this->renderPartial('_formAlbum',array('model'=>$modelAlbum));
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
