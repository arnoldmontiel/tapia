<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->Id)); ?>	
	<br />

	<b><?php echo $data->getAttributeLabel('description'); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />


</div>