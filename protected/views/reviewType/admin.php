<?php
$this->breadcrumbs=array(
	'Review Types'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Listar Agrupadores', 'url'=>array('index')),
	array('label'=>'Crear Agrupador', 'url'=>array('create')),
);

?>

<h1>Administrar Agrupadores</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'review-type-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'description',
		array(
 			'name'=>"is_internal",
 			'type'=>'raw',
 			'value'=>'CHtml::checkBox("is_internal",$data->is_internal,array("disabled"=>"disabled"))',
	 			'filter'=>CHtml::listData(
					array(
						array('id'=>'0','value'=>'No'),
						array('id'=>'1','value'=>'Si')
					)
			,'id','value'
			),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}',
		),
	),
)); ?>
