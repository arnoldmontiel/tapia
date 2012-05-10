<?php
$this->breadcrumbs=array(
	'User Groups'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'Listar Grupo de Usuario', 'url'=>array('index')),
	array('label'=>'Crear Grupo de Usuario', 'url'=>array('create')),
	array('label'=>'Actualizar Grupo de Usuario', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Administrar Grupo de Usuario', 'url'=>array('admin')),
);
?>

<h1>Vista Individual</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id',
		'description',
		array('label'=>$model->getAttributeLabel('can_create'),
				'type'=>'raw',
				'value'=>CHtml::checkBox("can_create",$model->can_create,array("disabled"=>"disabled"))
		),
		array('label'=>$model->getAttributeLabel('is_administrator'),
				'type'=>'raw',
				'value'=>CHtml::checkBox("is_administrator",$model->is_administrator,array("disabled"=>"disabled"))
		),
	),
)); ?>
