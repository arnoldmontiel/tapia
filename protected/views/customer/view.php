<?php
$this->breadcrumbs=array(
	'Customers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Listar Clientes', 'url'=>array('index')),
	array('label'=>'Crear Cliente', 'url'=>array('create')),
	array('label'=>'Asignacion Clientes', 'url'=>array('assign')),
	array('label'=>'Actualizar Cliente', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Administrar Clientes', 'url'=>array('admin')),
);
?>

<h1>Vista Cliente</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'last_name',
		array('label'=>$model->getAttributeLabel('username'),
				'type'=>'raw',
				'value'=>$model->username . ' ( ' . CHtml::link('Asignar permisos', Yii::app()->createUrl('srbac/authitem/assign'),array('id'=>'linkPermission')) . ' )'
		),
		array('label'=>$model->getAttributeLabel('password'),
				'type'=>'raw',
				'value'=>$model->user->password
		),
		array('label'=>$model->getAttributeLabel('email'),
				'type'=>'raw',
				'value'=>$model->user->email
		),
		array('label'=>$model->getAttributeLabel('address'),
				'type'=>'raw',
				'value'=>$model->user->address
		),
		'building_address',
		array('label'=>$model->getAttributeLabel('phone_house'),
				'type'=>'raw',
				'value'=>$model->user->phone_house
		),
		array('label'=>$model->getAttributeLabel('phone_mobile'),
				'type'=>'raw',
				'value'=>$model->user->phone_mobile
		),
	),
)); ?>
<br>
<div class="customer-assign-title">
	Usuarios Asignados
	</div>
<?php 
	
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'user-customer-grid',
		'dataProvider'=>$modelUserCustomer->search(),
		'summaryText'=>'',
		'columns'=>array(
				array(
			 		'name'=>'user_group_desc',
					'value'=>'$data->user->userGroup->description',
				),
				'username',
				array(
			 		'name'=>'name',
					'value'=>'$data->user->name',
				),
				array(
			 		'name'=>'last_name',
					'value'=>'$data->user->last_name',
				),
				array(
			 		'name'=>'email',
					'value'=>'$data->user->email',
				),
				array(
			 		'name'=>'phone_house',
					'value'=>'$data->user->phone_house',
				),
				array(
			 		'name'=>'phone_mobile',
					'value'=>'$data->user->phone_mobile',
				),
				),
			)
		); 
	?>


