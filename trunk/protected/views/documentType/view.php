<?php
$this->breadcrumbs=array(
	'Document Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Listar Tipo de Documentos', 'url'=>array('index')),
	array('label'=>'Crear Tipo de Documentos', 'url'=>array('create')),
	array('label'=>'Actualizar Tipo de Documentos', 'url'=>array('update', 'id'=>$model->Id)),	
	array('label'=>'Administrar Tipo de Documentos', 'url'=>array('admin')),
);
?>

<h1>Vista Tipo de Documento</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'description',
	),
)); ?>
