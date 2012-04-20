<?php
$this->breadcrumbs=array(
	'User Groups'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'List UserGroup', 'url'=>array('index')),
	array('label'=>'Create UserGroup', 'url'=>array('create')),
	array('label'=>'Update UserGroup', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Delete UserGroup', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserGroup', 'url'=>array('admin')),
);
?>

<h1>View UserGroup #<?php echo $model->Id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id',
		'description',
		'can_create',
		'is_administrator',
	),
)); ?>
