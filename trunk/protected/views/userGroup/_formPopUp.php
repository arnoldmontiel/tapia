<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-group-form',
	'enableAjaxValidation'=>false,
)); ?>


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
		<?php echo $form->labelEx($model,'is_administrator'); ?>
		<?php echo $form->checkBox($model,'is_administrator'); ?>
		<?php echo $form->error($model,'is_administrator'); ?>
	</div>
	
	<div class="row">	
		<?php echo $form->labelEx($model,'use_technical_docs'); ?>
		<?php echo $form->checkBox($model,'use_technical_docs'); ?>
		<?php echo $form->error($model,'use_technical_docs'); ?>
	</div>
	
	<div class="row">
		<div class="check-title">	
			Tipos de Agrupadores que podr&aacute; crear
		</div>
		<div class="review-types">
		<?php
			$checked = array();
			foreach($model->reviewTypes as $reviewType)
			{
				$checked[] = $reviewType->Id;
			}
		
			$modelReviewType = ReviewType::model()->findAll();
			$checkReviewType = CHtml::listData($modelReviewType, 'Id', 'description');		
			echo CHtml::checkBoxList('chklist-reviewType', $checked, $checkReviewType);
		?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->