<?php
Yii::app()->clientScript->registerScript('form-note', "

		
$('#Note_note').change(function(){
	$.post(
		'".NoteController::createUrl('note/AjaxUpdateNoteDesc')."',
		{
			id: $('#Note_Id_note').val(),
			note:$(this).val()
		}).success(
			function() 
			{ 
				//$('#saveok2').animate({opacity: 'show'},2000);
				//$('#saveok2').animate({opacity: 'hide'},2000);
			});
		}
	);
		
$(document).keypress(function(e) {
    if(e.keyCode == 13) 
    {
    	if($('*:focus').attr('id') == 'Note_note' && $('*:focus').val() != '')
    	{
    		$('#Note_note').blur();
    		return false;
    	}
		return false; 
    }
});
");
?>

<div class="wide form">
<?php $formNote=$this->beginWidget('CActiveForm', array(
	'id'=>'note-form',
	'method'=>'post',
));
?>

	<?php echo CHtml::hiddenField('Note_Id_note',$model->Id,array('id'=>'Note_Id_note')); ?>
	<div class="row">
		<?php echo $formNote->textArea($model,'note',array('rows'=>2, 'cols'=>110,'class'=>'wall-action-upload-file-description', 'placeholder'=>'Escriba una nota...')); ?>
		<?php echo $formNote->error($model,'note'); ?>
	</div>

	<div class="album-action-area-images" id="note-images">
	<?php
	
	foreach ($model->multimedias as $item)
	{
		echo CHtml::openTag('div',array('id'=>'picture_'.$item->Id,'class'=>'album-action-area-image'));
		
		if($item->Id_multimedia_type == 1)
		{
			$this->widget('ext.highslide.highslide', array(
										'smallImage'=>"images/".$item->file_name_small,
										'image'=>"images/".$item->file_name,
										'caption'=>$item->description,
										'Id'=>$item->Id,
										'small_width'=>240,
										'small_height'=>180,
			
			));
		}
		else 
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
		
		echo CHtml::imageButton(
			                                'images/remove.png',
		array(
			                                'class'=>'album-action-remove',
			                                'title'=>'Delete Image',
											'id'=>'delete_'.$item->Id,
			                                	'ajax'=> array(
													'type'=>'GET',
													'url'=>NoteController::createUrl('note/AjaxRemoveResourceFromNote',array('IdMultimedia'=>$item->Id, 'IdNote'=>$model->Id)),
													'beforeSend'=>'function(){
																if(!confirm("\u00BFEst\u00e1 seguro de eliminar?")) 
																	return false;
																	}',
													'success'=>'js:function(data)
													{
														$("#picture_'.$item->Id.'").attr("style","display:none");
													}'
		)
		)
			
		);
	
		echo CHtml::closeTag('div');
	}
	?>
		</div>
		
<?php $this->endWidget(); ?>

</div><!-- form -->