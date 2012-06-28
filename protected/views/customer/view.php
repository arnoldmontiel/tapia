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

<h1>Cliente</h1>

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
				array('label'=>$model->getAttributeLabel('description'),
						'type'=>'raw',
						'value'=>$model->user->description
				),
		),
)); ?>
<br>
<div id="customer-assign-area" style="display: none">

	<div class="customer-assign-title">
		Usuarios Disponibles
		<div class="customer-button-box">
			<?php echo CHtml::button('Terminar',array(
					'onclick'=>
					'
					jQuery("#customer-assign-area").animate({opacity: "hide"},100);
					jQuery("#btn-assign-user").val("Asignar Usuarios");
					return false;',
			));
			echo CHtml::button('Nuevo Usuario', array('class'=>'customer-new-user',
					'onclick'=>'jQuery("#CreateUser").dialog("open"); return false;',
			));

			?>
		</div>

	</div>

	<?php 
	$creteria = new CDbCriteria();
	$creteria->addCondition('is_internal = 0');
	$creteria->addNotInCondition('Id', array('1','3'));
	$userGroup = UserGroup::model()->findAll($creteria);
	$userGroupList = CHtml::listData($userGroup,'Id','description');
	$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'user-group-grid',
			'dataProvider'=>$modelUser->searchUnassigned($model->Id),
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
				$.fn.yiiGridView.update("user-group-grid", {
					data: $(this).serialize()
				});
			
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
				array(
		 			'name'=>"Id_user_group",
		 			'type'=>'raw',
		 			'value'=>'$data->userGroup->description',
		 			'filter'=>$userGroupList,
				),
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
							'Cancelar'=>'js:function(){jQuery("#CreateUser").dialog( "close" );}',
							'Grabar'=>'js:function()
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
	$modelUserCreate = new User;
	$criteria=new CDbCriteria;
	$criteria->condition='Id <> 3'; // clients
	$ddlUserGroup = UserGroup::model()->findAll($criteria);
	echo $this->renderPartial('_formUser', array('model'=>$modelUserCreate ,'ddlUserGroup'=>$ddlUserGroup));

	$this->endWidget('zii.widgets.jui.CJuiDialog');
	?>
</div>
<div class="customer-assign-title">
	Usuarios Asignados
	<div class="customer-button-box">
		<?php echo CHtml::button('Asignar Usuarios',array('id'=>'btn-assign-user',
				'onclick'=>
				'
				if(jQuery(this).val()=="Terminar")
				{
				jQuery("#customer-assign-area").animate({opacity: "hide"},100);
		 	jQuery(this).val("Asignar Usuarios");
}
				else
				{
				jQuery("#customer-assign-area").animate({opacity: "show"},2000);
		 	jQuery(this).val("Terminar");
}
				return false;',
	)); ?>
	</div>
</div>
<?php 

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'user-customer-grid',
		'dataProvider'=>$modelUserCustomer->search(),
		'filter'=>$modelUserCustomer,
		'afterAjaxUpdate'=>'js:function(){$.fn.yiiGridView.update("user-group-grid");}',
		'summaryText'=>'',
		'columns'=>array(
				array(
		 			'name'=>"Id_user_group",
		 			'type'=>'raw',
		 			'value'=>'$data->user->userGroup->description',
		 			'filter'=>$userGroupList,
				),
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
				array(
						'class'=>'CButtonColumn',
						'template'=>'{delete}',
						'buttons'=>array(
								'delete' => array(
										'url'=>'Yii::app()->createUrl("customer/AjaxRemoveUserCustomer", array("IdCustomer"=>$data->Id_customer,"username"=>$data->username))',
								),
						),
				),

		),
)
);
?>

<div class="customer-assign-title">
	Permisos por grupo de usuario
	<div class="customer-button-box">
		<?php echo CHtml::submitButton('Actualizar',array('id'=>'btnUpdateGrid')); ?>
	</div>
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
