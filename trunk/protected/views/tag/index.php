<?php
$this->breadcrumbs=array(
	'Tags',
);

$this->menu=array(
	array('label'=>'Crear Etapas', 'url'=>array('create')),
	array('label'=>'Administrar Etapas', 'url'=>array('admin')),
);
?>

<h1>Etapas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
