<?php
$this->breadcrumbs=array(
	'Document Types'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Listar Tipo de Documentos', 'url'=>array('index')),
	array('label'=>'Crear Tipo de Documentos', 'url'=>array('create')),
);
?>

<h1>Administrar Tipo de Documentos</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'document-type-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(		
		'name',
		'description',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}',
		),
	),
)); ?>
