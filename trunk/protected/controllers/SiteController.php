<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
				
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$modelWall = new Wall;
		$customer = User::getCustomer();
		if(isset($customer))
		{
			Yii::app()->controller->redirect(array('review/index','Id_customer'=>$customer->Id));
				
		}
		else
		{
			Yii::app()->controller->redirect(array('review/index'));				
		}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout='//layouts/login';
		
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
				AuditLogin::audit();
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	
	public function actionAjaxFillNextWall()
	{
		if(isset($_POST['lastId'])&&isset($_POST['lastLeft']))
		{
			$wall = new Wall;
			$customer = User::getCustomer();
				
			$wall->Id_customer = $customer->Id;
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
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}