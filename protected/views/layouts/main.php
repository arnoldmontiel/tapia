<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="es" />

	<!-- blueprint CSS framework -->
	<script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script>
	
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl."/js/autoresize.js");?>
	

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div class="header-menu" >
			<div class="logo">
				<?php $params = User::getCustomer()?array('Id_customer'=>User::getCustomer()->Id):array(); ?>
				<?php echo CHtml::link(CHtml::encode(Yii::app()->name),Yii::app()->createUrl('review/index',$params),array('class'=>'logo')); ?>
			</div>
			<div class="header-menu-item" >
				<?php echo CHtml::link('Salir '.' ('.Yii::app()->user->name.')',Yii::app()->createUrl('site/logout'),array('class'=>'header-menu-item')); ?>
			</div>
		
		</div>
		<div id="header-container" style='display: none;'>
			<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
			<ul class="nav">
				<?php if(Yii::app()->user->checkAccess('SiteIndex')):?>
					<li class="nav">
						<?php echo CHtml::link('Inicio ',Yii::app()->createUrl('site/index')); ?>
					</li>
				<?php endif?>
				<?php if(Yii::app()->user->checkAccess('WallIndex')):?>
					<li class="nav">
					<?php echo CHtml::link('Administrar ',Yii::app()->createUrl('wall/index')); ?>
					</li>
				<?php endif?>
				<li class="nav">
					<?php echo CHtml::link('Salir '.' ('.Yii::app()->user->name.')',Yii::app()->createUrl('site/logout')); ?>
				</li>
			</ul>
			<div class="search">
				<div class="search-icon"></div>
					<input id="q" type="text" x-webkit-speech="" placeholder="Buscar" name="q">
			</div>			
		</div><!-- header-container -->
	</div><!-- header -->


	<?php echo $content; ?>

	<div class="line"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by SmartLiving.<br/>
		All Rights Reserved.<br/>
		Powered by Oneken
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
