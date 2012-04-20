<?php
$this->breadcrumbs=array(
	'User Groups'=>array('index'),
	$model->Id=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserGroup', 'url'=>array('index')),
	array('label'=>'Create UserGroup', 'url'=>array('create')),
	array('label'=>'View UserGroup', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Manage UserGroup', 'url'=>array('admin')),
);
?>

<h1>Update UserGroup <?php echo $model->Id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>