<?php
$this->breadcrumbs=array(
	'Review Types'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'List ReviewType', 'url'=>array('index')),
	array('label'=>'Create ReviewType', 'url'=>array('create')),
	array('label'=>'Update ReviewType', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Delete ReviewType', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ReviewType', 'url'=>array('admin')),
);
?>

<h1>View ReviewType #<?php echo $model->Id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id',
		'description',
	),
)); ?>