<?php
Yii::app()->clientScript->registerScript('AttachDoc', "

$('#btnCancel').click(function(){
		window.location = '".ReviewController::createUrl('update',array('id'=>$model->Id))."';
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
		window.location = '".ReviewController::createUrl('update',array('id'=>$model->Id))."';
		return false;
	}
	);
});


$('#chkAll').change(function(){
		if($(this).is(':checked'))
			$('#documents').find('input:checkbox').attr('checked',true);
		else
			$('#documents').find('input:checkbox').attr('checked',false);
		
});

");
?>

<?php 
echo CHtml::label('Seleccionar Todo', 'chkAll');
echo CHtml::checkBox('chkAll','',array('id'=>'chkAll'));?>
		
<div id="documents">
	<div class="review-area-files" id="files_selected_container">
		<div class="review-action-area-files" >
		<?php
		foreach ($modelMultimediaSelected as $item)
		{
			if($item->notes->Id_note != $idNote)
			{
				echo CHtml::openTag('div',array('id'=>'picture_'.$item->Id,'class'=>'review-area-single-files'));
					echo CHtml::openTag('div',array('class'=>'review-area-single-files-name'));
						echo CHtml::checkBox('chkDoc',true,array('id'=>$item->Id, 'value'=>$item->Id));
						echo CHtml::link(CHtml::encode($item->file_name),Yii::app()->baseUrl.'/docs/'.$item->file_name,array('target'=>'_blank'));
						echo CHtml::encode(' '.round(($item->size / 1024), 2));
						echo CHtml::encode(' (Kb) ');
					echo CHtml::closeTag('div');
					echo CHtml::openTag('div',array('class'=>'review-area-single-files-description'));
						echo CHtml::encode($item->description);
					echo CHtml::closeTag('div');
				echo CHtml::closeTag('div');
			}
		}
		?>
		</div>
	</div>
	<hr style="clear:none">
	<div class="review-area-files" id="files_container">
		<div class="review-action-area-files" >
		<?php
		foreach ($modelMultimedia as $item)
		{
			if($item->notes->Id_note != $idNote)
			{
				echo CHtml::openTag('div',array('id'=>'picture_'.$item->Id,'class'=>'review-area-single-files'));
					echo CHtml::openTag('div',array('class'=>'review-area-single-files-name'));
						echo CHtml::checkBox('chkDoc','',array('id'=>$item->Id, 'value'=>$item->Id));
						echo CHtml::link(CHtml::encode($item->file_name),Yii::app()->baseUrl.'/docs/'.$item->file_name,array('target'=>'_blank'));
						echo CHtml::encode(' '.round(($item->size / 1024), 2));
						echo CHtml::encode(' (Kb) ');
					echo CHtml::closeTag('div');
					echo CHtml::openTag('div',array('class'=>'review-area-single-files-description'));
						echo CHtml::encode($item->description);
					echo CHtml::closeTag('div');
				echo CHtml::closeTag('div');
			}
		}
		?>
		</div>
	</div>
</div>
<div class="row" style="text-align: center;">
	<?php echo CHtml::button('Adjuntar',array('class'=>'wall-action-submit-btn','id'=>'btnPublic',));?>
	<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancel',));?>
</div>