<?php
$this->breadcrumbs=array(
	'Review Types'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'Listar Agrupadores', 'url'=>array('index')),
	array('label'=>'Crear Agrupador', 'url'=>array('create')),
	array('label'=>'Actualizar Agrupador', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Administrar Agrupadores', 'url'=>array('admin')),
);
?>

<h1>Vista Agrupador</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'description',
		array('label'=>$model->getAttributeLabel('is_internal'),
				'type'=>'raw',
				'value'=>CHtml::checkBox("is_internal",$model->is_internal,array("disabled"=>"disabled"))
		),
		array('label'=>$model->getAttributeLabel('is_for_client'),
				'type'=>'raw',
				'value'=>CHtml::checkBox("is_for_client",$model->is_for_client,array("disabled"=>"disabled"))
		),
	),
)); ?>
