<?php
$this->breadcrumbs=array(
	'User Groups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Listar Grupo de Usuario', 'url'=>array('index')),
	array('label'=>'Administrar Grupo de Usuario', 'url'=>array('admin')),
);
?>

<h1>Crear Grupo de Usuario</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>