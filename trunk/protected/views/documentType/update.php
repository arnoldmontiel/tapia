<?php
$this->breadcrumbs=array(
	'Document Types'=>array('index'),
	$model->name=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'Listar Tipo de Documentos', 'url'=>array('index')),
	array('label'=>'Crear Tipo de Documentos', 'url'=>array('create')),
	array('label'=>'Vista Tipo de Documentos', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Administrar Tipo de Documentos', 'url'=>array('admin')),
);
?>

<h1>Actualizar Tipo de Documento</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>