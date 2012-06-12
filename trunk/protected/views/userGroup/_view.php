<div class="view">

	<b><?php echo $data->getAttributeLabel('description'); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->description), array('view', 'id'=>$data->Id)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('is_internal')); ?>:</b>
	<?php echo CHtml::checkBox("is_internal",CHtml::encode($data->is_internal),array('disabled'=>'disabled')); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_administrator')); ?>:</b>
	<?php echo CHtml::checkBox("is_administrator",CHtml::encode($data->is_administrator),array('disabled'=>'disabled')); ?>
	<br />


</div>