<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'Listar Etapas', 'url'=>array('index')),
	array('label'=>'Crear Etapa', 'url'=>array('create')),
	array('label'=>'Actualizar Etapa', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Administrar Etapas', 'url'=>array('admin')),
);
?>

<h1>Vista Etapa</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'description',
	),
)); ?>

<?php 
	$modelTagReviewType = new TagReviewType();
	$modelTagReviewType->Id_tag = $model->Id;
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'tag-customer-grid',
		'dataProvider'=>$modelTagReviewType->search(),
		'summaryText'=>'',
		'columns'=>array(
				array(
			 		'name'=>'Agrupadores Asociados',
					'htmlOptions'=>array('style'=>'text-align: center'),
					'value'=>'$data->reviewType->description',
				),
				),
			)
		); 
	?>
