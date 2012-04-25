<?php

class CustomerController extends Controller
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
		$model=new Customer;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$ddlUsername = User::model()->findAll();
		
		if(isset($_POST['Customer']))
		{
			$model->attributes=$_POST['Customer'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Id));
		}

		$this->render('create',array(
			'model'=>$model,
			'ddlUsername'=>$ddlUsername,
		));
	}

	public function actionAssignment()
	{
		$model = new Customer;
		$modelUserGroup = new UserGroup;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
	
		$ddlCustomer = Customer::model()->findAll();
		
	
		$criteria=new CDbCriteria;
			
		$criteria->addCondition('t.Id <> '. User::getAdminUserGroupId());
		$criteria->addCondition('t.Id <> 3');
		
		$ddlUserGroup = UserGroup::model()->findAll($criteria);
		
		$modelUser = new User;
		if(isset($_GET['UserGroup']))
			$modelUser->Id_user_group = $_GET['UserGroup']['Id'];
			 
		
		$modelUserCustomer = new UserCustomer;
		if(isset($_GET['Customer']))
			$modelUserCustomer->Id_customer = $_GET['Customer']['Id'];
		
		if(isset($_GET['User']))
			$modelUser->attributes = $_GET['User'];
		
		if(isset($_GET['UserCustomer']))
			$modelUserCustomer->attributes = $_GET['UserCustomer'];
		
		$this->render('customerAssign',array(
				'model'=>$model,
				'ddlCustomer'=>$ddlCustomer,
				'ddlUserGroup'=>$ddlUserGroup,
				'modelUserGroup'=>$modelUserGroup,
				'modelUser'=>$modelUser,
				'modelUserCustomer'=>$modelUserCustomer,
		));
	}
	
	public function actionAjaxAddUserCustomer()
	{
		
		$idCustomer = isset($_GET['IdCustomer'])?$_GET['IdCustomer']:'';
		$idUser = isset($_GET['username'])?$_GET['username'][0]:'';
	
		if(!empty($idCustomer)&&!empty($idUser))
		{
			$userCustomerDb = UserCustomer::model()->findByAttributes(array('Id_customer'=>(int) $idCustomer,'username'=>$idUser));
			if($userCustomerDb==null)
			{
				$userCustomer = new UserCustomer;
				$userCustomer->attributes =  array('Id_customer'=>$idCustomer,
													'username'=>$idUser,
												);
				$userCustomer->save();
			}
			else
			{
				throw new CDbException('El usuario ya esta asignado');
			}
		}
	}
	
	public function actionAjaxRemoveUserCustomer()
	{
	
		$idCustomer = isset($_GET['IdCustomer'])?$_GET['IdCustomer']:'';
		$idUser = isset($_GET['username'])?$_GET['username']:'';
	
		if(!empty($idCustomer)&&!empty($idUser))
		{
			$userCustomerDb = UserCustomer::model()->findByAttributes(array('Id_customer'=>(int) $idCustomer,'username'=>$idUser));
			if($userCustomerDb)
			{
				$userCustomerDb->delete();
			}
		}
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

		if(isset($_POST['Customer']))
		{
			$model->attributes=$_POST['Customer'];
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
		$dataProvider=new CActiveDataProvider('Customer');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Customer('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Customer']))
			$model->attributes=$_GET['Customer'];

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
		$model=Customer::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='customer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
