<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<div style="display: inline-block;">
			<?php echo $form->labelEx($model,'Id_user_group'); ?>
			<?php 
				$userGroups = CHtml::listData($ddlUserGroup, 'Id', 'description');
				echo $form->dropDownList($model,'Id_user_group',$userGroups); ?>
			<?php echo $form->error($model,'Id_user_group'); ?>
		</div>
		<div style="display: inline-block;">
			<?php echo CHtml::link( 'Agregar Nuevo Grupo de Usuarios','#',array('onclick'=>'jQuery("#CreateUserGroup").dialog("open"); return false;'));?>
		</div>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
			
	<div class="row">
		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'last_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'phone_house'); ?>
		<?php echo $form->textField($model,'phone_house',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'phone_house'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'phone_mobile'); ?>
		<?php echo $form->textField($model,'phone_mobile',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'phone_mobile'); ?>
	</div>
			
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('size'=>255,'maxlength'=>255,'cols'=>80)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div class="row">	
		<?php echo $form->labelEx($model,'send_mail'); ?>
		<?php echo $form->checkBox($model,'send_mail', array('checked','checked')); ?>
		<?php echo $form->error($model,'send_mail'); ?>
	</div>
			
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
//Nomenclator
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
			'id'=>'CreateUserGroup',
			// additional javascript options for the dialog plugin
			'options'=>array(
					'title'=>'Crear Grupo de Usuario',
					'autoOpen'=>false,
					'modal'=>true,
					'width'=> '500',
					'buttons'=>	array(
							'Cancelar'=>'js:function(){jQuery("#CreateUserGroup").dialog( "close" );}',
							'Grabar'=>'js:function()
							{
							jQuery("#wating").dialog("open");
							jQuery.post("'.Yii::app()->createUrl("userGroup/AjaxCreate").'", $("#user-group-form").serialize(),
							function(data) {
								if(data!=null)
								{
									$("#User_Id_user_group").append(
										$("<option></option>").val(data.Id).html(data.description)
									);
									jQuery("#CreateUserGroup").dialog( "close" );
								}
							jQuery("#wating").dialog("close");
						},"json"
					);
	
	}'),
			),
	));
	$modelUserGroup = new UserGroup();
	echo $this->renderPartial('../userGroup/_formPopUp', array('model'=>$modelUserGroup));
	
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>