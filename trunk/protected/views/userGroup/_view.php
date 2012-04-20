<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Id), array('view', 'id'=>$data->Id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('can_create')); ?>:</b>
	<?php echo CHtml::encode($data->can_create); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_administrator')); ?>:</b>
	<?php echo CHtml::encode($data->is_administrator); ?>
	<br />


</div>