<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'language'=>'es', // Este es el lenguaje en el que quer�s que muestre las cosas
    'sourceLanguage'=>'en', //  este es el lenguaje por default de los archivos
    
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    
	'name'=>'Tapia',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.controllers.*',
		'application.models.*',
		'application.components.*',
		'application.modules.srbac.controllers.SBaseController',
		'application.extensions.yii-mail.YiiMailMessage',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Tapia',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'srbac' => array(
							'userclass'=>'User', //default: User
							'userid'=>'username', //default: userid
							'username'=>'username', //default:username
							'delimeter'=>'@', //default:-
							'debug'=>false, //default :false
							'pageSize'=>10, // default : 15
							'superUser' =>'Authority', //default: Authorizer
							'css'=>'srbac.css', //default: srbac.css
							'layout'=>'application.views.layouts.main', //default: application.views.layouts.main,
		//must be an existing alias
							'notAuthorizedView'=> 'srbac.views.authitem.unauthorized', // default:
		//srbac.views.authitem.unauthorized, must be an existing alias
							'alwaysAllowed'=>array( //default: array()
							'SiteLogin','SiteLogout','SiteError'),
							'prefixAlwaysAllowed'=>array( //default: array()
										'Ajax'),
							'userActions'=>array('Show','View','List'), //default: array()
							'listBoxNumberOfLines' => 15, //default : 10 'imagesPath' => 'srbac.images', // default: srbac.images 'imagesPack'=>'noia', //default: noia 'iconText'=>true, // default : false 'header'=>'srbac.views.authitem.header', //default : srbac.views.authitem.header,
		//must be an existing alias 'footer'=>'srbac.views.authitem.footer', //default: srbac.views.authitem.footer,
		//must be an existing alias 'showHeader'=>true, // default: false 'showFooter'=>true, // default: false
							'alwaysAllowedPath'=>'srbac.components', // default: srbac.components
		// must be an existing alias
		)
	),

	// application components
	'components'=>array(
		'mail' => array(
		 			'class' => 'ext.yii-mail.YiiMail',
		 			'transportType' => 'php',
		 			'viewPath' => 'application.views.mail',
		 			'logging' => true,
		 			'dryRun' => false
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		'db2'=>array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=tapia',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		'authManager'=>array(
		// Path to SDbAuthManager in srbac module if you want to use case insensitive
		//access checking (or CDbAuthManager for case sensitive access checking)
									'class'=>'application.modules.srbac.components.SDbAuthManager',
		// The database component used
									'connectionID'=>'db2',
		// The itemTable name (default:authitem)
									'itemTable'=>'items',
		// The assignmentTable name (default:authassignment)
									'assignmentTable'=>'assignments',
		// The itemChildTable name (default:authitemchild)
									'itemChildTable'=>'itemchildren',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);