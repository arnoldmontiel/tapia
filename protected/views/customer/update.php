<?php
$this->breadcrumbs=array(
	'Customers'=>array('index'),
	'Update',
);

$this->menu=array(
	array('label'=>'List Customer', 'url'=>array('index')),
	array('label'=>'Create Customer', 'url'=>array('create')),
	array('label'=>'Manage Customer', 'url'=>array('admin')),
);
?>

<h1>Update Customer</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>