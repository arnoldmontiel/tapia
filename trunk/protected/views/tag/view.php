<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'List Tag', 'url'=>array('index')),
	array('label'=>'Create Tag', 'url'=>array('create')),
	array('label'=>'Update Tag', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Manage Tag', 'url'=>array('admin')),
);
?>

<h1>View Tag #<?php echo $model->Id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id',
		'description',
	),
)); ?>
