<script type="text/javascript">
hs.graphicsDir = '<?php echo '../..'. $this->graphics ?>/'
</script>
<?php
	if(isset($height))
	{
		$height+=10;		
	} 
?>
<div class="highslide-gallery" style="display:inline-block;position: relative; <?php echo (isset($height))? 'height:'.$height.'px;':'';?> ">
	<?php if (empty($images)):?>		
		<?php 		
			echo CHtml::openTag('a',
				array(
					'id'=>'thumb'.$Id,
					'href'=>$image,
					'class'=>'highslide',
					'onclick'=>'return hs.expand(this, { thumbnailId: "thumb'.$Id.'", slideshowGroup: '.$Id.' })',
				)
			);
			echo CHtml::image($smallImage,'Highslide JS',array('title'=>'Click para ampliar'));
			echo CHtml::closeTag('a');
		?>

		<div class="highslide-caption">
			<?php echo $caption;?>
		</div>
		<?php endif?>		
	<?php if (!empty($images)):?>
		<?php 		
			echo CHtml::openTag('a',
				array(
					'id'=>'thumb'.$Id,
					'href'=>$images[0]['image'],
					'class'=>'highslide',
					'style'=>'position:relative;float:left;z-index:10;',
					'onclick'=>'return hs.expand(this, { thumbnailId: "thumb'.$Id.'", slideshowGroup: '.$Id.' })',
				)
			);
			echo CHtml::image($images[0]['small_image'],'Highslide JS',array('title'=>'Click para ampliar'));
			echo CHtml::closeTag('a');

			if(isset($images[1]))
			{
				echo CHtml::openTag('a',
				array(
									'id'=>'thumb'.$Id,
									'href'=>$images[1]['image'],
									'class'=>'highslide',
									'style'=>'height:200px;-webkit-transform: rotate(-5deg);
									-moz-transform: rotate(-5deg); position:absolute;float:left;left:0px;',
									'onclick'=>'return hs.expand(this, { thumbnailId: "thumb'.$Id.'", slideshowGroup: '.$Id.' })',
				)
				);
				echo CHtml::image($images[1]['small_image'],'Highslide JS',array('title'=>'Click para ampliar'));
				echo CHtml::closeTag('a');				
			}
			if(isset($images[2]))
			{
				echo CHtml::openTag('a',
				array(
									'id'=>'thumb'.$Id,
									'href'=>$images[2]['image'],
									'class'=>'highslide',
									'style'=>'height:200px;-webkit-transform: rotate(4deg);
									-moz-transform: rotate(4deg); position:absolute;float:left;;left:0px;',
									'onclick'=>'return hs.expand(this, { thumbnailId: "thumb'.$Id.'", slideshowGroup: '.$Id.' })',
				)
				);
				echo CHtml::image($images[2]['small_image'],'Highslide JS',array('title'=>'Click para ampliar'));
				echo CHtml::closeTag('a');				
			}
			?>
		<?php 
		echo CHtml::openTag('div',array('class'=>'highslide-caption'));
			echo $images[0]['caption'];
		echo CHtml::closeTag('div');
		?>
		<div class="hidden-container">
		<?php foreach ($images as $indice => $item)
		{
			if($indice>2)
			{
				echo CHtml::openTag('a',
					array(
						'class'=>'highslide',
						'onclick'=>'return hs.expand(this, { thumbnailId: "thumb'.$Id.'", slideshowGroup: '.$Id.' })',
						'href'=>$item['image'],
					)
				);
				echo CHtml::closeTag('a');
				echo CHtml::openTag('div',array('class'=>'highslide-caption'));
					echo $item['caption'];
				echo CHtml::closeTag('div');
			}				
		}
		?>
			
		</div>		
	<?php endif?>		
</div>
	