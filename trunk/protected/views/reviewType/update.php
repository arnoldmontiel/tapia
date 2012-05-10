<?php
$this->breadcrumbs=array(
	'Review Types'=>array('index'),
	$model->Id=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'Listar Agrupadores', 'url'=>array('index')),
	array('label'=>'Crear Agrupador', 'url'=>array('create')),
	array('label'=>'Ver Agrupador', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Administrar Agrupadores', 'url'=>array('admin')),
);
?>

<h1>Actualizar Agrupador</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>