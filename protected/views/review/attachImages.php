<?php
Yii::app()->clientScript->registerScript('AttachImage', "

$('#btnCancel').click(function(){
		window.location = '".ReviewController::createUrl('update',array('id'=>$model->Id, 'idNote'=>$idNote))."';
		return false;
});

$('#btnPublic').click(function(){
	$('#loading').addClass('loading');
	var data = { 'images[]' : []};
	$(':checked').each(function() {
 	 data['images[]'].push($(this).val());
	});
	$.post('".NoteController::createUrl('note/AjaxAttachImage')."', 
		{
			id: ".$idNote.",
			images: data['images[]']
		}
	).success(
	function(data){
		window.location = '".ReviewController::createUrl('update',array('id'=>$model->Id, 'idNote'=>$idNote))."';
		return false;
	}
	);
});

");
?>


<div class="album-action-area-images" id="images_container">
	<?php
	
	
	foreach ($model->multimedias as $item)
	{
		if($item->notes->Id_note != $idNote)
		{
			echo CHtml::openTag('div',array('id'=>'picture_'.$item->Id,'class'=>'album-action-area-image'));
			$this->widget('ext.highslide.highslide', array(
										'smallImage'=>"images/".$item->file_name_small,
										'image'=>"images/".$item->file_name,
										'caption'=>'',
										'Id'=>$item->Id,
			));
			echo CHtml::checkBox('chkImage','',array('id'=>$item->Id, 'value'=>$item->Id));
			
		
			echo CHtml::closeTag('div');
		}
	}
	?>
</div>
<div class="row" style="text-align: center;">
	<?php echo CHtml::button('Publicar',array('class'=>'wall-action-submit-btn','id'=>'btnPublic',));?>
	<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancel',));?>
</div>