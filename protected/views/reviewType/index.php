<?php
$this->breadcrumbs=array(
	'Review Types',
);

$this->menu=array(
	array('label'=>'Create ReviewType', 'url'=>array('create')),
	array('label'=>'Manage ReviewType', 'url'=>array('admin')),
);
?>

<h1>Review Types</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
