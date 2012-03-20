<?php
$this->breadcrumbs=array(
	'Walls'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'List Wall', 'url'=>array('index')),
	array('label'=>'Create Wall', 'url'=>array('create')),
	array('label'=>'Update Wall', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Delete Wall', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Wall', 'url'=>array('admin')),
);
?>

<h1>View Wall #<?php echo $model->Id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id',
		'Id_note',
		'Id_multimedia',
		'index_order',
		'album_Id',
		'Id_customer',
	),
)); ?>
