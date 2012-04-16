<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#review-view-data'.$data->Id, "



");
?>

<div class="review-single-view" id="<?php echo $data->Id?>" >
	<div class="view-text-date"><?php echo $data->creation_date;?></div>
	<div id='edit_image<?php echo $data->Id?>' class="review-edit-image div-hidden">
	<?php
		echo CHtml::link('Editar Imagenes',
			ReviewController::createUrl('AjaxAttachImage',array('id'=>$data->review->Id, 'idNote'=>$data->Id)),
			array('class'=>'review-edit-image')
		);
	?>
	</div>
	<?php
	 echo CHtml::image('images/remove.png','',
			array('id'=>'delete_'.$data->Id, 'class'=>'wall-action-remove', 'title'=>'Eliminar'));
	?>
	<div class="review-text-simple-note">
	<textarea id='main_note<?php echo $data->Id?>' class="wall-action-edit-main-note" placeholder='Escriba una nota...'><?php echo $data->note;?></textarea>
	</div>		
	<div id='review_image<?php echo $data->Id?>' class="review-text-images">
			
	<?php
	
	$images = array();
	$height=0;
	foreach($data->multimedias as $item)
	{
		if($item->Id_multimedia_type!=1) continue;
		$image= array();
		$image['image'] = "images/".$item->file_name;
		$image['small_image'] = "images/".$item->file_name_small;
		$image['caption'] = $item->description;
		if($item->height_small>$height);
		{
			$height = $item->height_small;
		}
		$images[]=$image;
	}
	if(sizeof($images)>0)
	{
	
		$this->widget('ext.highslide.highslide', array(
												'images'=>$images,
												'Id'=>$data->Id,
												'height'=>$height,
		));
	}
	else
	{
		echo CHtml::link('Adjuntar Imagenes',
			ReviewController::createUrl('AjaxAttachImage',array('id'=>$data->review->Id, 'idNote'=>$data->Id)));
	}
	?>
	</div>
	<div class="review-text-docs">
		<?php 
			foreach($data->multimedias as $item)
			{
				if($item->Id_multimedia_type!=3
					&&$item->Id_multimedia_type!=4) continue;
				echo CHtml::openTag('div');
				echo CHtml::link(CHtml::encode($item->file_name),Yii::app()->baseUrl.'/docs/'.$item->file_name,array('target'=>'_blank'));
				echo CHtml::closeTag('div');
					
			}
			echo CHtml::link('Adjuntar Documentos',
			ReviewController::createUrl('AjaxAttachDoc',array('id'=>$data->review->Id, 'idNote'=>$data->Id)));
		?>
	</div>
	<div id="singleNoteContainer" class="singles-notes-container">
	<?php $notes=$data->notes;?>
	<?php if (!empty($notes)):?>
		<?php 
		foreach($notes as $item)
		{
			echo CHtml::openTag('div',array('class'=>'view-text-note'));
				echo CHtml::openTag('div',array('class'=>'view-text-date'));
					echo $item->creation_date;
				echo CHtml::closeTag('div');
				echo CHtml::image('images/remove.png','',
					array('id'=>'left_note_'.$item->Id.'_'.$data->Id, 'class'=>'wall-action-remove-small','title'=>'Remove'));
				echo CHtml::openTag('p',array('class'=>'single-formated-text'));
					echo $item->note;
				echo CHtml::closeTag('p');
				echo CHtml::closeTag('div');
		}
		?>
	<?php endif?>
	</div>
	<div class="review-text-note-add">
		<textarea id="note_<?php echo $data->Id?>" class="wall-action-add-note" placeholder='Escriba una nota...'></textarea>
	</div>
</div>


