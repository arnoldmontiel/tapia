<?php
Yii::app()->clientScript->registerScript('AttachImage', "

$('#btnCancel').click(function(){
		window.location = '".ReviewController::createUrl('update',array('id'=>$model->Id))."';
		return false;
});

$('#btnPublic').click(function(){
	$('#loading').addClass('loading');
	var data = { 'images[]' : []};
	$('input:checked').each(function() {
		if($(this).val() != '' && $(this).attr('name') == 'chkImage')
 	 		data['images[]'].push($(this).val());
	});
	$.post('".NoteController::createUrl('note/AjaxAttachImage')."', 
		{
			id: ".$idNote.",
			images: data['images[]']
		}
	).success(
	function(data){
		window.location = '".ReviewController::createUrl('update',array('id'=>$model->Id))."';
		return false;
	}
	);
});

$('#chkAll').change(function(){
		if($(this).is(':checked'))
			$('#images').find('input:checkbox').attr('checked',true);
		else
			$('#images').find('input:checkbox').attr('checked',false);
		
});

");
?>

<?php 
echo CHtml::label('Seleccionar Todo', 'chkAll');
echo CHtml::checkBox('chkAll','',array('id'=>'chkAll'));?>
		
<div id="images">		
	<div class="album-action-area-images" id="images_selected_container">
	<?php
	foreach ($modelMultimediaSelected as $item)
	{
		echo CHtml::openTag('div',array('id'=>'picture_'.$item->Id,'class'=>'review-attach-image'));
			$this->widget('ext.highslide.highslide', array(
										'smallImage'=>"images/".$item->file_name_small,
										'image'=>"images/".$item->file_name,
										'caption'=>$item->description,
										'Id'=>$item->Id,
										'small_width'=>240,
										'small_height'=>180,
			));
			echo CHtml::checkBox('chkImage',true,array('id'=>$item->Id, 'value'=>$item->Id, 'class'=>'review-attach-image'));
			
			echo CHtml::openTag('div',array('class'=>'review-attach-image-description'));
				echo CHtml::openTag('p',array('class'=>'review-attach-image-description'));
				echo CHtml::encode($item->description).'&nbsp;';
				echo CHtml::closeTag('p');				
			echo CHtml::closeTag('div');				
		echo CHtml::closeTag('div');
	}
	?>
	</div>
	<hr style="clear:none">
	<div class="album-action-area-images" id="images_container">
		<?php
		foreach ($modelMultimedia as $item)
		{
			echo CHtml::openTag('div',array('id'=>'picture_'.$item->Id,'class'=>'review-attach-image'));
			$this->widget('ext.highslide.highslide', array(
										'smallImage'=>"images/".$item->file_name_small,
										'image'=>"images/".$item->file_name,
										'caption'=>$item->description,
										'Id'=>$item->Id,
										'small_width'=>240,
										'small_height'=>180,
			));
			echo CHtml::checkBox('chkImage','',array('id'=>$item->Id, 'value'=>$item->Id, 'class'=>'review-attach-image'));
			
				echo CHtml::openTag('div',array('class'=>'review-attach-image-description'));
				echo CHtml::openTag('p',array('class'=>'review-attach-image-description'));
				echo CHtml::encode($item->description).'&nbsp;';
				echo CHtml::closeTag('p');				
				echo CHtml::closeTag('div');				
			echo CHtml::closeTag('div');
		}
		?>
	</div>
</div>
<div class="row" style="text-align: center;">
	<?php echo CHtml::button('Adjuntar',array('class'=>'wall-action-submit-btn','id'=>'btnPublic',));?>
	<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancel',));?>
</div>