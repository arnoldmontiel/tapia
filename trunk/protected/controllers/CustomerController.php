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
		$modelUserCustomer = new UserCustomer('Search');
		$modelUserCustomer->Id_customer = $id;
		if(isset($_GET['UserCustomer']))
		{
			$modelUserCustomer->attributes =$_GET['UserCustomer'];
			if(isset($_GET['UserCustomer']['Id_user_group']))
				$modelUserCustomer->Id_user_group =$_GET['UserCustomer']['Id_user_group']; 			

			if(isset($_GET['UserCustomer']['name']))
				$modelUserCustomer->name =$_GET['UserCustomer']['name']; 			

			if(isset($_GET['UserCustomer']['last_name']))
				$modelUserCustomer->last_name =$_GET['UserCustomer']['last_name']; 			

			if(isset($_GET['UserCustomer']['email']))
				$modelUserCustomer->email =$_GET['UserCustomer']['email']; 			

			if(isset($_GET['UserCustomer']['phone_house']))
				$modelUserCustomer->phone_house =$_GET['UserCustomer']['phone_house']; 			

			if(isset($_GET['UserCustomer']['phone_mobile']))
				$modelUserCustomer->phone_mobile =$_GET['UserCustomer']['phone_mobile']; 			
		}
		
		$modelUserGroupCustomer = new UserGroupCustomer('Search');
		$modelUserGroupCustomer->Id_customer = $id;
		if(isset($_GET['UserGroupCustomer']))
		{
			$modelUserGroupCustomer->attributes = $_GET['UserGroupCustomer'];
		}
		$modelUser = new User('Search');
		if(isset($_GET['User']))
		{
			$modelUser->attributes = $_GET['User'];
		}
		
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'modelUser'=>$modelUser,
			'modelUserCustomer'=>$modelUserCustomer,
			'modelUserGroupCustomer'=>$modelUserGroupCustomer,
		));
	}

	public function actionAjaxUpdatePermission()
	{
		$idUserGroup = $_POST['idUserGroup'];
		$idInterestPower = $_POST['idInterestPower'];
		$idCustomer = $_POST['idCustomer'];
		
		$modelUserGroupCustomer = UserGroupCustomer::model()->findByPk(array('Id_user_group'=>$idUserGroup, 'Id_customer'=>$idCustomer));
		$modelUserGroupCustomer->Id_interest_power = $idInterestPower;
		$modelUserGroupCustomer->save();
	}
	
	public function actionAjaxUpdatePermissionGrid()
	{
		$idCustomer = $_POST['idCustomer'];
		
		$userGroups = UserGroup::model()->findAll();
		
		foreach($userGroups as $userGroup)
		{
			$uGroupCustomerDb = UserGroupCustomer::model()->findAllByAttributes(array('Id_customer'=>$idCustomer, 'Id_user_group'=>$userGroup->Id));
			if(empty($uGroupCustomerDb))
			{
				$this->savePermission($idCustomer, $userGroup);
			}
		}
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$model->Id_user_group = 3; // cliente
		
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->building_address = $_POST['User']['building_address'];
			if($model->save())
			{
				$modelCustomer = Customer::model()->findByAttributes(array('username'=>$model->username));
				$this->createDefaultPermissions($modelCustomer->Id);
				$this->redirect(array('view','id'=>$modelCustomer->Id));
			}
		}
		
		$this->render('create',array(
					'model'=>$model,
		));
	}

	private function createDefaultPermissions($idCustomer)
	{
		$userGroups = UserGroup::model()->findAll();
		foreach($userGroups as $item)
		{
			$this->savePermission($idCustomer, $item);
		}
	}
	
	private function savePermission($idCustomer, $modelUserGroup)
	{
		$modelUserGroupCustomer = new UserGroupCustomer;
		$modelUserGroupCustomer->Id_customer = $idCustomer;
		$modelUserGroupCustomer->Id_user_group = $modelUserGroup->Id;
		if($modelUserGroup->is_administrator)
			$modelUserGroupCustomer->Id_interest_power = 2;
		else
			$modelUserGroupCustomer->Id_interest_power = 1;
			
		$modelUserGroupCustomer->save();
	}
	
	public function actionAssign()
	{
		$model = new Customer;
		$modelUserGroup = new UserGroup;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
	
		$ddlCustomer = Customer::model()->findAll();
		
	
		$criteria=new CDbCriteria;
			
		$criteria->addCondition('t.is_internal <> 1');
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
		
		$this->render('assign',array(
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
		$modelCustomer = $this->loadModel($id);
		
		$model = User::model()->findByAttributes(array('username'=>$modelCustomer->username));
		$model->building_address = $modelCustomer->building_address; 
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->building_address = $_POST['User']['building_address'];
			if($model->save())
				$this->redirect(array('view','id'=>$modelCustomer->Id));
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
