<?php
$this->breadcrumbs=array(
	'User Groups'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Listar Grupo de Usuario', 'url'=>array('index')),
	array('label'=>'Crear Grupo de Usuario', 'url'=>array('create')),
);

?>

<h1>Administrar Grupo de Usuarios</h1>


<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-group-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'description',
			array(
	 			'name'=>"is_administrator",
	 			'type'=>'raw',
	 			'value'=>'CHtml::checkBox("is_administrator",$data->is_administrator,array("disabled"=>"disabled"))',
	 			'filter'=>CHtml::listData(
					array(
						array('id'=>'0','value'=>'No'),
						array('id'=>'1','value'=>'Si')
					)
					,'id','value'
				),
			),
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
