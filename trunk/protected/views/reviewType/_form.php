<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#review-type-form', "
	$('#ReviewType_is_for_client').change(function(){
		$('#ReviewType_is_internal').attr('checked',false);
	});
	$('#ReviewType_is_internal').change(function(){
		$('#ReviewType_is_for_client').attr('checked',false);
	});
");

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'review-type-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Campos con <span class="required">*</span> son requeridos.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div class="row">	
		<?php echo $form->labelEx($model,'is_internal'); ?>
		<?php echo $form->checkBox($model,'is_internal'); ?>
		<?php echo $form->error($model,'is_internal'); ?>
	</div>
	
	<div class="row">	
		<?php echo $form->labelEx($model,'is_for_client'); ?>
		<?php echo $form->checkBox($model,'is_for_client'); ?>
		<?php echo $form->error($model,'is_for_client'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->