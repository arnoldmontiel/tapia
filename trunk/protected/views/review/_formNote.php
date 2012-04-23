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

$('#Note_need_confirmation').change(function(){
	$.post(
		'".ReviewController::createUrl('AjaxUpdateNoteNeedConf')."',
		{
			id: $('#Note_Id_note').val(),
			chk:$(this).val()
		}).success(
			function() 
			{ 
				//$('#saveok2').animate({opacity: 'show'},2000);
				//$('#saveok2').animate({opacity: 'hide'},2000);
			});
		}
	);

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
		$imgBtn= CHtml::imageButton(
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
		
		if($item->Id_multimedia_type == 1)
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
			echo $imgBtn;
			echo CHtml::openTag('div',array('class'=>'review-attach-image-description'));
			echo CHtml::openTag('p',array('class'=>'review-attach-image-description'));
			echo CHtml::encode($item->description).'&nbsp;';
			echo CHtml::closeTag('p');				
			echo CHtml::closeTag('div');				
			echo CHtml::closeTag('div');			
		}
		else 
		{
			echo CHtml::openTag('div',array('id'=>'picture_'.$item->Id,'class'=>'review-area-single-files'));
			echo CHtml::openTag('div',array('class'=>'review-area-single-files-name'));
			echo CHtml::link(CHtml::encode($item->file_name),Yii::app()->baseUrl.'/docs/'.$item->file_name,array('target'=>'_blank'));
			echo CHtml::encode(' '.round(($item->size / 1024), 2));
			echo CHtml::encode(' (Kb) ');
			echo CHtml::closeTag('div');
			echo CHtml::openTag('div',array('class'=>'review-area-single-files-description'));
			echo CHtml::encode($item->description);
			echo CHtml::closeTag('div');
			echo $imgBtn;
			echo CHtml::closeTag('div');
		}
				
	}
	?>
		</div>
		
<?php $this->endWidget(); ?>

</div><!-- form -->