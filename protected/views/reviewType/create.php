<?php
$this->breadcrumbs=array(
	'Review Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Listar Agrupadores', 'url'=>array('index')),
	array('label'=>'Administrar Agrupadores', 'url'=>array('admin')),
);
?>

<h1>Crear Agrupador</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>