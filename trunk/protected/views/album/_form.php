<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'album-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<?php
	  $this->widget('CMultiFileUpload', array(
	     'model'=>$model,
	     'attribute'=>'files',
	     'accept'=>'jpg|gif',
	     'options'=>array(
	        'onFileSelect'=>'function(e, v, m){ alert("onFileSelect - "+v) }',
	        'afterFileSelect'=>'function(e, v, m){ alert("afterFileSelect - "+v) }',
	        'onFileAppend'=>'function(e, v, m){ alert("onFileAppend - "+v) }',
	        'afterFileAppend'=>'function(e, v, m){ alert("afterFileAppend - "+v) }',
	        'onFileRemove'=>'function(e, v, m){ alert("onFileRemove - "+v) }',
	        'afterFileRemove'=>'function(e, v, m){ alert("afterFileRemove - "+v) }',
	     ),
	  ));
	?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->