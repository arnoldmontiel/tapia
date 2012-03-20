<div class="view">

	<?php 
	if(isset($data->Id_note)){ 
		$this->widget('ext.richtext.jwysiwyg', array(
		 	'id'=>'noteContainer',	// default is class="ui-sortable" id="yw0"	
		 	'notes'=> $data->note->note,
		 	'mode'=>'show'
		));
		
	}
	?>
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('Id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Id), array('view', 'id'=>$data->Id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_note')); ?>:</b>
	<?php echo CHtml::encode($data->Id_note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_multimedia')); ?>:</b>
	<?php echo CHtml::encode($data->Id_multimedia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('index_order')); ?>:</b>
	<?php echo CHtml::encode($data->index_order); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('album_Id')); ?>:</b>
	<?php echo CHtml::encode($data->album_Id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_customer')); ?>:</b>
	<?php echo CHtml::encode($data->Id_customer); ?>
	<br />


</div>