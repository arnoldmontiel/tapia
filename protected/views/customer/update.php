<?php
$this->breadcrumbs=array(
	'Customers'=>array('index'),
	'Update',
);

$this->menu=array(
	array('label'=>'Listar Clientes', 'url'=>array('index')),
	array('label'=>'Crear Cliente', 'url'=>array('create')),
	array('label'=>'Asignacion Clientes', 'url'=>array('assign')),
	array('label'=>'Administrar Clientes', 'url'=>array('admin')),
);
?>

<h1>Actualizar Cliente</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>