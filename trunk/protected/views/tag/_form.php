<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tag-form',
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
	<div class="check-title">
		Tipos de Agrupadores
	</div>
	<div class="review-types">
	
		<?php 
			$checkReviewTypes = CHtml::listData(ReviewType::model()->findAll(), 'Id', 'description');
			$checked = array();
			foreach($modelTagReviewType as $item)
			{
				$checked[]= $item->Id_review_type;
			}
		?>
		<?php echo CHtml::checkBoxList('ReviewType', $checked, $checkReviewTypes,array()); ?>
		</div>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Crear' : 'Guardar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->