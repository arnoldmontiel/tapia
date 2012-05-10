<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	$model->Id=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'Listar Etiquetas', 'url'=>array('index')),
	array('label'=>'Crear Etiqueta', 'url'=>array('create')),
	array('label'=>'Ver Etiqueta', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Administrar Etiquetas', 'url'=>array('admin')),
);
?>

<h1>Actualizar Etiqueta</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>