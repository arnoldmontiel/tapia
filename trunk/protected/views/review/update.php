<?php
$this->breadcrumbs=array(
	'Reviews'=>array('index'),
	$model->Id=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Review', 'url'=>array('index')),
	array('label'=>'Create Review', 'url'=>array('create')),
	array('label'=>'View Review', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Manage Review', 'url'=>array('admin')),
);
?>

<h1>Update Review <?php echo $model->Id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>