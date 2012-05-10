<?php
$this->breadcrumbs=array(
	'User Groups',
);

$this->menu=array(
	array('label'=>'Crear Grupo de Usuario', 'url'=>array('create')),
	array('label'=>'Administrar Grupo de Usuario', 'url'=>array('admin')),
);
?>

<h1>Grupo de usuarios</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
