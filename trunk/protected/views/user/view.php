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
		'username',
		'password',
		'email',
		'name',
		'last_name',
		'address',
	),
)); ?>
