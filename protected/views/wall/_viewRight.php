<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#site_view'.$data->Id, "

");

?>
<?php if (isset($data->note)):?>
<div class="view-single-right" id="<?php echo $data->Id?>" <?php if($first) echo 'style="margin-top: 20px"';?>>
	<div class="view-text-date"><?php echo $data->note->creation_date;?></div>
	<div class="view-text-simple-note"><?php echo $data->note->note;?></div>		
	<div class="view-dialog-right" ></div>
</div>
<?php endif?>

<?php if (isset($data->multimedia)):?>
<div class="view-single-right" id="<?php echo $data->Id?>" <?php if($first) echo 'style="margin-top: 20px"';?>>
	<div class="view-text-date"><?php echo $data->multimedia->creation_date;?></div>
	<?php 
		$this->widget('ext.highslide.highslide', array(
								'smallImage'=>"images/".$data->multimedia->file_name_small,
								'image'=>"images/".$data->multimedia->file_name,
								'caption'=>$data->multimedia->description,
								'Id'=>$data->Id,
		)); 
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
				$item->note.
			'</div>';						
		}
		?>
	<?php endif?>
	<div class="view-dialog-right" ></div>
</div>
<?php endif?>

<?php if (isset($data->album)):?>
<div class="view-single-right" id="<?php echo $data->Id?>" <?php if($first) echo 'style="margin-top: 20px"';?>>
	<div class="view-text-date"><?php echo $data->album->creation_date;?></div>
	<?php 
		$images = array();
		foreach($data->album->multimedias as $item)
		{
			$image= array();
			$image['image'] = "images/".$item->file_name;
			$image['small_image'] = "images/".$item->file_name_small;
			$image['caption'] = $item->description;
			$images[]=$image;
		}
		$this->widget('ext.highslide.highslide', array(
								'images'=>$images,
								'Id'=>$data->Id,
		)); 
	?>	
	<?php $notes=$data->album->notes;?>
	<div class="view-text-simple-title"><?php echo $data->album->title;?></div>	
	<?php if (!empty($notes)):?>
		<?php 
		foreach($notes as $item)
		{
			echo '<div class="view-text-note">'.
					'<div class="view-text-date">'.
						$item->creation_date.
					'</div>'.						
				$item->note.
			'</div>';						
		}
		?>
	<?php endif?>
	<div class="view-dialog-right" ></div>
</div>

<?php endif?>
