<?php
$this->breadcrumbs=array(
	'Walls'=>array('index'),
	$model->Id=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Wall', 'url'=>array('index')),
	array('label'=>'Create Wall', 'url'=>array('create')),
	array('label'=>'View Wall', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Manage Wall', 'url'=>array('admin')),
);
?>

<h1>Update Wall <?php echo $model->Id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>