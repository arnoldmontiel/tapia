<?php
Yii::app()->clientScript->registerScript('AttachDoc', "

$('#btnCancel').click(function(){
		window.location = '".ReviewController::createUrl('update',array('id'=>$model->Id, 'idNote'=>$idNote))."';
		return false;
});

$('#btnPublic').click(function(){
	$('#loading').addClass('loading');
	var data = { 'docs[]' : []};
	$(':checked').each(function() {
 	 data['docs[]'].push($(this).val());
	});
	$.post('".NoteController::createUrl('note/AjaxAttachDoc')."', 
		{
			id: ".$idNote.",
			docs: data['docs[]']
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
	
	
	foreach ($modelMultimedia as $item)
	{
		if($item->notes->Id_note != $idNote)
		{
			echo CHtml::openTag('div',array('id'=>'picture_'.$item->Id,'class'=>'album-action-area-image'));
			echo CHtml::encode($item->description);
			echo CHtml::checkBox('chkDoc','',array('id'=>$item->Id, 'value'=>$item->Id));
			
		
			echo CHtml::closeTag('div');
		}
	}
	?>
</div>
<div class="row" style="text-align: center;">
	<?php echo CHtml::button('Publicar',array('class'=>'wall-action-submit-btn','id'=>'btnPublic',));?>
	<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancel',));?>
</div>