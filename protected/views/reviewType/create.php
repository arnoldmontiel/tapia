<?php
$this->breadcrumbs=array(
	'Review Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ReviewType', 'url'=>array('index')),
	array('label'=>'Manage ReviewType', 'url'=>array('admin')),
);
?>

<h1>Create ReviewType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>