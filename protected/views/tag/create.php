<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Listar Etapas', 'url'=>array('index')),
	array('label'=>'Administrar Etapas', 'url'=>array('admin')),
);
?>

<h1>Crear Etapas</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'modelTagReviewType'=>$modelTagReviewType)); ?>