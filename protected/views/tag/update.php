<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	$model->Id=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'Listar Etapas', 'url'=>array('index')),
	array('label'=>'Crear Etapas', 'url'=>array('create')),
	array('label'=>'Ver Etapa', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Administrar Etapas', 'url'=>array('admin')),
);
?>

<h1>Actualizar Etapa</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'modelTagReviewType'=>$modelTagReviewType,)); ?>