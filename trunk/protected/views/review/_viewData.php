<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#review-view-data'.$data->Id, "
$('#edit_image".$data->Id."').hover(
	function(){
		$(this).removeClass('div-hidden');
	}
);

$('#review_image".$data->Id."').hover(
	function(){
		$('#edit_image".$data->Id."').removeClass('div-hidden');
	},
	function(){
		$('#edit_image".$data->Id."').addClass('div-hidden');
	}
);


");
?>

<div class="review-single-view" id="<?php echo $data->Id?>" >
	<div class="view-text-date"><?php echo $data->creation_date;?></div>
	<div id='edit_image<?php echo $data->Id?>' class="review-edit-image div-hidden">
	<?php
		echo CHtml::link('Editar Imagenes',
			ReviewController::createUrl('index',array('id'=>$data->Id)),
			array('class'=>'review-edit-image')
		);
	?>
	</div>
	<?php
	 echo CHtml::image('images/remove.png','',
			array('id'=>'delete_'.$data->Id, 'class'=>'wall-action-remove', 'title'=>'Eliminar'));
	?>
	<div class="review-text-simple-note"><?php echo $data->note;?></div>		
	<div id='review_image<?php echo $data->Id?>' class="review-text-images">
			
	<?php
	
	if($data->multimedias[0]->Id_multimedia_type == 1)
	{
		$images = array();
		$height=0;
		foreach($data->multimedias as $item)
		{
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
		$this->widget('ext.highslide.highslide', array(
												'images'=>$images,
												'Id'=>$data->Id,
												'height'=>$height,
		));
	}
	else
	{
	
	}
	
	?>
	</div>
	<div id="singleNoteContainer">
	<?php $notes=$data->notes;?>
	<?php if (!empty($notes)):?>
		<?php 
		foreach($notes as $item)
		{
			echo '<div class="view-text-note">'.
					'<div class="view-text-date">'.
						$item->creation_date.
					'</div>'.	
			CHtml::image('images/remove.png','',
			array('id'=>'left_note_'.$item->Id.'_'.$data->Id, 'class'=>'wall-action-remove-small','title'=>'Remove')).
				$item->note.
			'</div>';							
		}
		?>
	<?php endif?>
	</div>
	<div class="review-text-note-add">
		<textarea id="note_<?php echo $data->Id?>" class="wall-action-add-note" placeholder='Escriba una nota...'></textarea>
	</div>
</div>


