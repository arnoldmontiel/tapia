<?php
$this->breadcrumbs=array(
	'User Groups'=>array('index'),
	$model->Id=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'Listar Grupo de Usuario', 'url'=>array('index')),
	array('label'=>'Crear Grupo de Usuario', 'url'=>array('create')),
	array('label'=>'Ver Grupo de Usuario', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Administrar Grupo de Usuario', 'url'=>array('admin')),
);
?>

<h1>Actualizar Grupo de Usuario</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>