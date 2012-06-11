<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/detail-view-blue.css" />

<?php

$this->menu=array(
	array('label'=>'Listar Clientes', 'url'=>array('index')),
	array('label'=>'Crear Cliente', 'url'=>array('create')),
	array('label'=>'Administrar Clientes', 'url'=>array('admin')),
);

Yii::app()->clientScript->registerScript('customerAssign', "
$('#Customer_Id').change(function(){
	$.fn.yiiGridView.update('user-customer-grid', {
			data: $(this).serialize()
		});
	if($(this).val()!= '' && $('#UserGroup_Id').val()!= ''){
		$('#display').animate({opacity: 'show'},240);
	}
	else{
		$('#display').animate({opacity: 'hide'},240);	
	}
	return false;
});

$('#UserGroup_Id').change(function(){
	
	$.fn.yiiGridView.update('user-group-grid', {
			data: $(this).serialize()
		});
	if($(this).val()!= '' && $('#Customer_Id').val() != ''){
		$('#display').animate({opacity: 'show'},240);
	}
	else{
		$('#display').animate({opacity: 'hide'},240);	
	}
	return false;
});

function unselectRow(id)
{
	$('#'+id+' > table > tbody > tr').each(function(i)
		{
			if($(this).hasClass('selected'))
			{
				$(this).removeClass('selected');
			}
		}
	);
}

");
?>
<h1>Asignacion de usuarios</h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'customerAssign-form',
		'enableAjaxValidation'=>true,
));
		
		?>
	
	<div id="customer" style="margin-bottom: 5px">
		
		<?php	$customers = CHtml::listData($ddlCustomer, 'Id', 'CustomerDesc');?>

		<?php echo $form->labelEx($model,'Cliente'); ?>

		<?php echo $form->dropDownList($model, 'Id', $customers,		
			array(
				'prompt'=>'Seleccionar un cliente'
			)		
		);
		
		
		
		?>
	</div>
	
	<div id="userGroup" style="margin-bottom: 5px; display: inline-block">
	
	<?php $userGroup = CHtml::listData($ddlUserGroup, 'Id', 'description');?>

	<?php $form->labelEx($modelUserGroup,'Grupo de Usuario');?>

		<?php echo $form->dropDownList($modelUserGroup, 'Id', $userGroup,		
			array(
				'prompt'=>'Seleccionar un grupo'
			)		
		);
		?>
	</div>
	
	<div id="display"
	style="display: none">

	<div class="customer-assign-title">
	Usuarios Disponibles
	</div>
	
	<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'user-group-grid',
		'dataProvider'=>$modelUser->search(),
		'filter'=>$modelUser,
		'summaryText'=>'',
		'selectionChanged'=>'js:function(id){
				$.get(	"'.CustomerController::createUrl('AjaxAddUserCustomer').'",
						{
							IdCustomer:$("#Customer_Id :selected").attr("value"),
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
	
	<p class="messageError"><?php
	echo 'Esa relacion ya existe';
	?></p>
	<br>
	<div class="customer-assign-title">
	Usuarios Asignados
	</div>
	<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'user-customer-grid',
		'dataProvider'=>$modelUserCustomer->search(),
		'filter'=>$modelUserCustomer,
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
	
	</div><!-- display-->
	
<?php $this->endWidget(); ?>

</div><!-- form -->
