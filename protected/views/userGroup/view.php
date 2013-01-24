<?php
$this->breadcrumbs=array(
	'User Groups'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'Listar Grupo de Usuario', 'url'=>array('index')),
	array('label'=>'Crear Grupo de Usuario', 'url'=>array('create')),
	array('label'=>'Actualizar Grupo de Usuario', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Administrar Grupo de Usuario', 'url'=>array('admin')),
);
?>

<h1>Vista Individual</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'description',
		array('label'=>$model->getAttributeLabel('is_internal'),
				'type'=>'raw',
				'value'=>CHtml::checkBox("is_internal",$model->is_internal,array("disabled"=>"disabled"))
		),
		array('label'=>$model->getAttributeLabel('is_administrator'),
				'type'=>'raw',
				'value'=>CHtml::checkBox("is_administrator",$model->is_administrator,array("disabled"=>"disabled"))
		),
		array('label'=>$model->getAttributeLabel('use_technical_docs'),
				'type'=>'raw',
				'value'=>CHtml::checkBox("use_technical_docs",$model->use_technical_docs,array("disabled"=>"disabled"))
		),
	),
)); ?>
<br>
<?php 
	
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'user-customer-grid',
		'dataProvider'=>$modelReviewTypeUserGroup->search(),
		'summaryText'=>'',
		'columns'=>array(
				array(
			 		'name'=>'review_type_desc',
					'htmlOptions'=>array('style'=>'text-align: center'),
					'value'=>'$data->reviewType->description',
				),
				),
			)
		); 
	?>
