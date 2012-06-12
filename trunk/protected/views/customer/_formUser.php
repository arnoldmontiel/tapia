<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row" style="float: left; width: 100%;">
		<div class="row-half">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'username'); ?>
		</div>

		<div class="row-half">
			<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>20,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'password'); ?>
		</div>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Id_user_group'); ?>
		<?php 
			$userGroups = CHtml::listData($ddlUserGroup, 'Id', 'description');
			echo $form->dropDownList($model,'Id_user_group',$userGroups); ?>
		<?php echo $form->error($model,'Id_user_group'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
			
	<div class="row">
		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array('size'=>45,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'last_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>45,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>
	
	<div class="row">
		<div class="row-half">
		<?php echo $form->labelEx($model,'phone_house'); ?>
		<?php echo $form->textField($model,'phone_house',array('size'=>20,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'phone_house'); ?>
		</div>
		<div class="rowl-half">
		<?php echo $form->labelEx($model,'phone_mobile'); ?>
		<?php echo $form->textField($model,'phone_mobile',array('size'=>20,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'phone_mobile'); ?>
		</div>
	</div>
	<?php $this->endWidget(); ?>

</div><!-- form -->