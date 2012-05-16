<?php
$this->breadcrumbs=array(
	'Customers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Listar Clientes', 'url'=>array('index')),
	array('label'=>'Crear Cliente', 'url'=>array('create')),
	array('label'=>'Asignacion Clientes', 'url'=>array('assign')),
	array('label'=>'Actualizar Cliente', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Administrar Clientes', 'url'=>array('admin')),
);
?>

<h1>Vista Cliente</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'last_name',
		'username',
		array('label'=>$model->getAttributeLabel('email'),
				'type'=>'raw',
				'value'=>$model->user->email
		),
		array('label'=>$model->getAttributeLabel('address'),
				'type'=>'raw',
				'value'=>$model->user->address
		),
		
	),
)); ?>
