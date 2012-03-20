<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wall-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Id_note'); ?>
		<?php echo $form->textField($model,'Id_note'); ?>
		<?php echo $form->error($model,'Id_note'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Id_multimedia'); ?>
		<?php echo $form->textField($model,'Id_multimedia'); ?>
		<?php echo $form->error($model,'Id_multimedia'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'index_order'); ?>
		<?php echo $form->textField($model,'index_order'); ?>
		<?php echo $form->error($model,'index_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'album_Id'); ?>
		<?php echo $form->textField($model,'album_Id'); ?>
		<?php echo $form->error($model,'album_Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Id_customer'); ?>
		<?php echo $form->textField($model,'Id_customer'); ?>
		<?php echo $form->error($model,'Id_customer'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->