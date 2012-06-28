<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->username,
);

$this->menu=array(
	array('label'=>'Listar Usuario', 'url'=>array('index')),
	array('label'=>'Crear Usuario', 'url'=>array('create')),
	array('label'=>'Actualizar Usuario', 'url'=>array('update', 'id'=>$model->username)),
	array('label'=>'Administrar Usuario', 'url'=>array('admin')),
);
?>

<h1>Vista Usuario</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array('label'=>$model->getAttributeLabel('username'),
			'type'=>'raw',
			'value'=>$model->username . ' ( ' . CHtml::link('Asignar permisos', Yii::app()->createUrl('srbac/authitem/assign'),array('id'=>'linkPermission')) . ' )'
		),
		'password',
		array('label'=>$model->getAttributeLabel('Grupo de Usuario'),
			'type'=>'raw',
			'value'=>$model->userGroup->description
		),
		'email',
		'name',
		'last_name',
		'address',
		'phone_house',
		'phone_mobile',
		'description',
		array('label'=>$model->getAttributeLabel('send_mail'),
				'type'=>'raw',
				'value'=>CHtml::checkBox("send_mail",$model->send_mail,array("disabled"=>"disabled"))
		),
	),
)); ?>
