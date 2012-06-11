<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'Listar Etapas', 'url'=>array('index')),
	array('label'=>'Crear Etapa', 'url'=>array('create')),
	array('label'=>'Actualizar Etapa', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Administrar Etapas', 'url'=>array('admin')),
);
?>

<h1>Vista Etapa</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'description',
	),
)); ?>
