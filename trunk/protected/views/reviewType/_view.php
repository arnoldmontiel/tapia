<div class="view">

	<b><?php echo $data->getAttributeLabel('description'); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->description), array('view', 'id'=>$data->Id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_internal')); ?>:</b>
	<?php echo CHtml::checkBox("is_internal",CHtml::encode($data->is_internal),array('disabled'=>'disabled')); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('is_for_client')); ?>:</b>
	<?php echo CHtml::checkBox("is_for_client",CHtml::encode($data->is_for_client),array('disabled'=>'disabled')); ?>
	<br />
	
</div>