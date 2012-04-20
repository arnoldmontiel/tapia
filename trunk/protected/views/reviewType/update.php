<?php
$this->breadcrumbs=array(
	'Review Types'=>array('index'),
	$model->Id=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ReviewType', 'url'=>array('index')),
	array('label'=>'Create ReviewType', 'url'=>array('create')),
	array('label'=>'View ReviewType', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Manage ReviewType', 'url'=>array('admin')),
);
?>

<h1>Update ReviewType <?php echo $model->Id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>