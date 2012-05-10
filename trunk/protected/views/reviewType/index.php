<?php
$this->breadcrumbs=array(
	'Review Types',
);

$this->menu=array(
	array('label'=>'Crear Agrupador', 'url'=>array('create')),
	array('label'=>'Administrar Agrupadores', 'url'=>array('admin')),
);
?>

<h1>Tipos de Agrupador</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
