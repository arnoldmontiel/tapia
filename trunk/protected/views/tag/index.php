<?php
$this->breadcrumbs=array(
	'Tags',
);

$this->menu=array(
	array('label'=>'Crear Etiqueta', 'url'=>array('create')),
	array('label'=>'Administrar Etiquetas', 'url'=>array('admin')),
);
?>

<h1>Etiquetas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
