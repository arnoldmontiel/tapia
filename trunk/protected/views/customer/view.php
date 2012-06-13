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
Yii::app()->clientScript->registerScript('viewTapiaCustomer', "

jQuery.fn.yiiGridView.update('userGroup-customer-grid');

jQuery('#btnUpdateGrid').click(function(){
	jQuery.post(
		'".CustomerController::createUrl('AjaxUpdatePermissionGrid')."',
		{
			idCustomer: '".$model->Id."'
		}).success(
			function() 
			{ 
				jQuery.fn.yiiGridView.update('userGroup-customer-grid');
			});
	return false;
});

",CClientScript:: POS_LOAD);
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
	Usuarios Disponibles
	</div>
	
	<?php 
	$modelUser = new User;
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'user-group-grid',
		'dataProvider'=>$modelUser->search(),
		'filter'=>$modelUser,
		'summaryText'=>'',
		'selectionChanged'=>'js:function(id){
				$.get(	"'.CustomerController::createUrl('AjaxAddUserCustomer').'",
						{
							IdCustomer:'.$model->Id.',
							username:$.fn.yiiGridView.getSelection("user-group-grid")
						}).success(
							function() 
							{
								
								$.fn.yiiGridView.update("user-customer-grid", {
									data: $(this).serialize()
								});
								unselectRow("user-group-grid");	
								
							})
						.error(
							function()
							{
								$(".messageError").animate({opacity: "show"},2000);
								$(".messageError").animate({opacity: "hide"},2000);
								unselectRow("user-group-grid");
							});
		}',
		'columns'=>array(
	 			'username',
	 			'name',
	 			'last_name',
				'email',
				'phone_house',
				'phone_mobile',
				),
			)
		); 
	?>


<?php 
	$this->widget('ext.processingDialog.processingDialog', array(
			'buttons'=>array('save'),
			'idDialog'=>'wating',
	));
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'CreateUser',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'Crear Usuario',
        'autoOpen'=>false,
        'modal'=>true,
		'width'=> '500',
    		'buttons'=>	array(
				'Create'=>'js:function()
				{
				jQuery("#wating").dialog("open");
				jQuery.post("'.Yii::app()->createUrl("user/create").'", $("#user-form").serialize(),
				function(data) {
					$.fn.yiiGridView.update("user-group-grid", {
						data: $(this).serialize()
					});

					jQuery("#wating").dialog("close");
					jQuery("#CreateUser").dialog( "close" );
				 }
			);
				
		}'),
    ),
));
	$modelUser = new User;
	$ddlUserGroup = UserGroup::model()->findAll();
    echo $this->renderPartial('_formUser', array('model'=>$modelUser ,'ddlUserGroup'=>$ddlUserGroup));

$this->endWidget('zii.widgets.jui.CJuiDialog');

// the link that may open the dialog
echo CHtml::link('Nuevo Usuario', '#', array(
	'onclick'=>'jQuery("#CreateUser").dialog("open"); return false;',
	));

?>
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

<div class="customer-assign-title">
	Permisos por grupo de usuario <?php echo CHtml::submitButton('Actualizar',array('id'=>'btnUpdateGrid')); ?>
</div>
	
<?php 

	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'userGroup-customer-grid',
		'dataProvider'=>$modelUserGroupCustomer->search(),
		'summaryText'=>'',
		'afterAjaxUpdate'=>'function(id, data){
						$("#userGroup-customer-grid").find("select[name = ddlInterestPower]").each(
							function(index, item){
								$(item).change(function(){
									
									$.post(
										"'.CustomerController::createUrl('AjaxUpdatePermission').'",
										{
											idUserGroup: $(this).attr("id"),
											idInterestPower: $(this).val(),
											idCustomer: "'.$model->Id.'"
										}).success(
											function() 
											{ 
								
											});
								});	
							});
					}',
		'columns'=>array(
					array(
				 		'name'=>'Grupo de Usuario',
						'value'=>'$data->userGroup->description',
					),
					array(
				 		'name'=>'Interes/Poder',
						'value'=>'CHtml::dropDownList("ddlInterestPower",
														$data->Id_interest_power,
														CHtml::listData(InterestPower::model()->findAll(), "Id", "description"),
														array(
														"id"=>$data->Id_user_group)
													)',
						'type'=>'raw',
						'htmlOptions'=>array('style'=>'text-align: center'),
					),
				),
			)
		); 
	?>
