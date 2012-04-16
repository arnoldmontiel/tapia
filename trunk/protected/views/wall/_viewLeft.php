<?php if (isset($data->note)):?>
	<div id="noteContainer_<?php echo $data->Id?>" >
		<div class="view-single-left" id="<?php echo $data->Id?>" >
			<div class="view-text-date"><?php echo $data->note->creation_date;?></div>
			<?php
			 echo CHtml::image('images/remove.png','',
					array('id'=>'delete_'.$data->Id, 'class'=>'wall-action-remove', 'title'=>'Remove'));
			?>
			<div class="view-text-simple-note"><?php echo $data->note->note;?></div>		

			<?php $notes=$data->note->notes;?>
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
			<div class="view-text-note-add">
				<textarea id="note_<?php echo $data->Id_note?>" class="wall-action-add-note" placeholder='Escriba una nota...'></textarea>
			</div>
			<div class="view-dialog-left"></div>
		</div>
	</div>
<?php endif?>


<?php if (isset($data->multimedia)):?>
<div id="multimediaContainer_<?php echo $data->Id?>" >
	<div class="view-single-left" id="<?php echo $data->Id?>" <?php if($first) echo 'style="margin-top: 20px"';?>>
	<div class="view-text-date"><?php echo $data->multimedia->creation_date;?></div>
	<?php
		 echo CHtml::image('images/remove.png','',
				array('id'=>'delete_'.$data->Id, 'class'=>'wall-action-remove','title'=>'Remove'));
		?>	
		<?php 
		
		if($data->multimedia->Id_multimedia_type == 1)
		{
			$this->widget('ext.highslide.highslide', array(
									'smallImage'=>"images/".$data->multimedia->file_name_small,
									'image'=>"images/".$data->multimedia->file_name,
									'caption'=>$data->multimedia->description,
									'Id'=>$data->Id,
									'small_width'=>$data->multimedia->width_small,
									'small_height'=>$data->multimedia->height_small,
				
			));
		}
		else
		{
			echo CHtml::link(
			CHtml::image(Yii::app()->baseUrl.'/images/'.$data->multimedia->file_name_small,'',array('class'=>'wall-action-pdf')),
			Yii::app()->baseUrl.'/docs/'.$data->multimedia->file_name,array('target'=>'_blank'));
			echo CHtml::link(
			CHtml::image(Yii::app()->baseUrl.'/images/pdfwatermark.png','',array('class'=>'wall-action-pdf-watermark')),
			Yii::app()->baseUrl.'/docs/'.$data->multimedia->file_name, array('target'=>'_blank'));
				
		}
		
	?>	
	<?php $notes=$data->multimedia->notes;?>
	<?php if (empty($notes)):?>
		<div class="view-text-simple-note"><?php echo $data->multimedia->description;?></div>		
	<?php endif?>
	<?php if (!empty($notes)):?>
		<?php 
		foreach($notes as $item)
		{
			echo '<div class="view-text-note">'.
					'<div class="view-text-date">'.
						$item->creation_date.
					'</div>'.	
			CHtml::image('images/remove.png','',
			array('id'=>'left_multimedia_'.$item->Id.'_'.$data->Id, 'class'=>'wall-action-remove-small','title'=>'Remove')).
				$item->note.
			'</div>';							
		}
		?>
	<?php endif?>
	<div class="view-text-note-add">
		<textarea id="multimedia_<?php echo $data->Id_multimedia?>" class="wall-action-add-note" placeholder='Escriba una nota...'></textarea>
	</div>
	<div class="view-dialog-left" ></div>
	</div>
</div>
<?php endif?>

<?php if (isset($data->album)):?>
<div id="albumContainer_<?php echo $data->Id?>" >
<div class="view-single-left" id="<?php echo $data->Id?>">
	<div class="view-text-date"><?php echo $data->album->creation_date;?></div>
	<?php
		 echo CHtml::image('images/remove.png','',
				array('id'=>'delete_'.$data->Id, 'class'=>'wall-action-remove', 'title'=>'Remove'));
	?>	
	<?php
		echo CHtml::link(
			CHtml::image('images/edit.png','',
				array('id'=>'delete_'.$data->Id, 'class'=>'wall-action-album-update', 'title'=>'Update'))
		,AlbumController::createUrl('album/update',array('id'=>$data->album->Id)));
	?>	
	<?php 
		$images = array();
		$height=0;
		foreach($data->album->multimedias as $item)
		{
			$image= array();
			$image['image'] = "images/".$item->file_name;
			$image['small_image'] = "images/".$item->file_name_small;
			$image['caption'] = $item->description;
			if($item->height_small>$height)
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
	?>	
	<?php $notes=$data->album->notes;?>
	<div class="view-text-simple-title"><?php echo $data->album->title;?></div>
	<?php if (empty($notes)):?>
		<div class="view-text-simple-note"><?php echo $data->album->description;?></div>		
	<?php endif?>
	<?php if (!empty($notes)):?>
		<?php 
		foreach($notes as $item)
		{
			echo '<div class="view-text-note">'.
					'<div class="view-text-date">'.
						$item->creation_date.
					'</div>'.			
			CHtml::image('images/remove.png','',
			array('id'=>'left_album_'.$item->Id.'_'.$data->Id, 'class'=>'wall-action-remove-small','title'=>'Remove')).
				$item->note.
			'</div>';						
		}
		?>
	<?php endif?>
	<div class="view-text-note-add">
		<textarea id="album_<?php echo $data->Id_album?>" class="wall-action-add-note" placeholder='Escriba una nota...'></textarea>
	</div>
	<div class="view-dialog-left" ></div>
</div>
</div>
<?php endif?>

