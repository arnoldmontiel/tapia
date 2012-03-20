<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'Id'); ?>
		<?php echo $form->textField($model,'Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Id_note'); ?>
		<?php echo $form->textField($model,'Id_note'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Id_multimedia'); ?>
		<?php echo $form->textField($model,'Id_multimedia'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'index_order'); ?>
		<?php echo $form->textField($model,'index_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'album_Id'); ?>
		<?php echo $form->textField($model,'album_Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Id_customer'); ?>
		<?php echo $form->textField($model,'Id_customer'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->