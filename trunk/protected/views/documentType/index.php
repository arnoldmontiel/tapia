<?php
$this->breadcrumbs=array(
	'Document Types',
);

$this->menu=array(
	array('label'=>'Crear Tipo de Documentos', 'url'=>array('create')),
	array('label'=>'Administrar Tipo de Documentos', 'url'=>array('admin')),
);
?>

<h1>Tipo de Documentos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
