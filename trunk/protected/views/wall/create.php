<?php
$this->breadcrumbs=array(
	'Walls'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Wall', 'url'=>array('index')),
	array('label'=>'Manage Wall', 'url'=>array('admin')),
);
?>

<h1>Create Wall</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>