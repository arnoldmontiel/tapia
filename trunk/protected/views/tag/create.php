<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Listar Etiquetas', 'url'=>array('index')),
	array('label'=>'Administrar Etiquetas', 'url'=>array('admin')),
);
?>

<h1>Crear Etiquetas</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>