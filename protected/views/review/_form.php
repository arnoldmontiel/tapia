
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'review-form',
	'enableAjaxValidation'=>true,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo CHtml::label('N&uacute;mero de Revisi&oacute;n', 'Review[review]'); ?>
		<?php echo $form->textField($model,'review'); ?>
		<?php echo $form->error($model,'review'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Prioridad', 'Review_Id_priority'); ?>
		<?php 
		$prioritys = CHtml::listData($modelPriority, 'Id', 'description');
		echo $form->dropDownList($model, 'Id_priority', $prioritys);
		?>
		<?php echo $form->error($model,'priority'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Tipo', 'Review_Id_review_type'); ?>
		<?php 
		$reviewTypes = CHtml::listData($modelReviewType, 'Id', 'description');
		echo $form->dropDownList($model, 'Id_review_type', $reviewTypes);
		?>
		<?php echo $form->error($model,'Id_review_type'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Descripci&oacute;n', 'Review[description]'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50,'style'=>'resize:none;')); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->