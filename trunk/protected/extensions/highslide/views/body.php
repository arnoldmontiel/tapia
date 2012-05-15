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
			$options = array('title'=>'Click para ampliar');
			$options['style']='';				
			if(isset($small_width))
			{
				$options['style']='width:'.$small_width.'px;';				
			}
			if(isset($small_height))
			{
				$options['style']=$options['style'].' height:'.$small_height.'px;';				
			}
				
			echo CHtml::openTag('a',
			array(
					'id'=>'thumb'.$Id,
					'href'=>$image,
					'class'=>'highslide',
					'onclick'=>'return hs.expand(this, { thumbnailId: "thumb'.$Id.'", slideshowGroup: '.$Id.' })',
				)
			);
			//echo CHtml::image($smallImage,'Highslide JS',$options);
			$options['style']=$options['style'].' background-image: url('.$smallImage.'); background-repeat: no-repeat;display: block;background-position: center 25%;';				
			echo CHtml::openTag('i',$options
			);echo ".";
			echo CHtml::closeTag('i');
				
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

			echo CHtml::openTag('div',array('class'=>'highslide-caption'));
			echo $images[0]['caption'];
			echo CHtml::closeTag('div');
			
			if(isset($images[1]))
			{
				echo CHtml::openTag('a',
				array(
									'id'=>'thumb'.$Id,
									'href'=>$images[1]['image'],
									'class'=>'highslide',
									'style'=>'height:200px;-webkit-transform: rotate(-5deg);
									-moz-transform: rotate(-5deg); position:absolute;float:left;left:0px;
									filter: progid:DXImageTransform.Microsoft.Matrix(sizingMethod="auto expand",
        							M11=0.9961947202682495, M12=0.08715574443340301,
        							M21=-0.08715574443340301, M22=0.9961947202682495);
									',
									'onclick'=>'return hs.expand(this, { thumbnailId: "thumb'.$Id.'", slideshowGroup: '.$Id.' })',
				)
				);
				echo CHtml::image($images[1]['small_image'],'Highslide JS',array('title'=>'Click para ampliar'));
				echo CHtml::closeTag('a');	
				echo CHtml::openTag('div',array('class'=>'highslide-caption'));
				echo $images[1]['caption'];
				echo CHtml::closeTag('div');
			}
			if(isset($images[2]))
			{
				echo CHtml::openTag('a',
				array(
									'id'=>'thumb'.$Id,
									'href'=>$images[2]['image'],
									'class'=>'highslide',
									'style'=>'height:200px;-webkit-transform: rotate(4deg);
									-moz-transform: rotate(4deg); position:absolute;float:left;left:0px;
									filter: progid:DXImageTransform.Microsoft.Matrix(sizingMethod="auto expand",
        							M11=0.9975640773773193, M12=-0.06975647062063217,
        							M21=0.06975647062063217, M22=0.9975640773773193);',
									'onclick'=>'return hs.expand(this, { thumbnailId: "thumb'.$Id.'", slideshowGroup: '.$Id.' })',
				)
				);
				echo CHtml::image($images[2]['small_image'],'Highslide JS',array('title'=>'Click para ampliar'));
				echo CHtml::closeTag('a');		
				echo CHtml::openTag('div',array('class'=>'highslide-caption'));
				echo $images[2]['caption'];
				echo CHtml::closeTag('div');
			}
			?>
		<?php 
// 		echo CHtml::openTag('div',array('class'=>'highslide-caption'));
// 			echo $images[0]['caption'];
// 		echo CHtml::closeTag('div');
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
	