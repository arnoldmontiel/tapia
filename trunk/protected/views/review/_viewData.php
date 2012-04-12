<div id="noteContainer_<?php echo $data->Id?>" >
	<div class="review-single-view" id="<?php echo $data->Id?>" >
		<div class="view-text-date"><?php echo $data->creation_date;?></div>
		<?php
		 echo CHtml::image('images/remove.png','',
				array('id'=>'delete_'.$data->Id, 'class'=>'wall-action-remove', 'title'=>'Remove'));
		?>
		<div class="review-text-simple-note"><?php echo $data->note;?></div>		
		<div class="review-text-images">
		<?php
		?>
				
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
		<div class="review-text-note-add">
			<textarea id="note_<?php echo $data->Id?>" class="wall-action-add-note" placeholder='Escriba una nota...'></textarea>
		</div>
	</div>
</div>

