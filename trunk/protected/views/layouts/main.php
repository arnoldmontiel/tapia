<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="header-container">
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
		Powered by WestIdeas
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
