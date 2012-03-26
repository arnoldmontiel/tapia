<?php
$this->breadcrumbs=array(
	'Albums'=>array('index'),
	$model->title=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Album', 'url'=>array('index')),
	array('label'=>'Create Album', 'url'=>array('create')),
	array('label'=>'View Album', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Manage Album', 'url'=>array('admin')),
);
?>

<h1>Update Album <?php echo $model->Id; ?></h1>

<?php echo $this->renderPartial('_formUpdate', array('model'=>$model)); ?>