<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'Listar Etiquetas', 'url'=>array('index')),
	array('label'=>'Crear Etiqueta', 'url'=>array('create')),
	array('label'=>'Actualizar Etiqueta', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Administrar Etiquetas', 'url'=>array('admin')),
);
?>

<h1>Vista Etiqueta</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'description',
	),
)); ?>
