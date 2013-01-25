<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-with-gallery.js',CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-exe.js',CClientScript::POS_HEAD);
$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide.css');


Yii::app()->clientScript->registerScript('viewImageResource', "
$('#btnBack').click(function(){
		window.location = '".ReviewController::createUrl('index',array('Id_customer'=>$Id_customer))."';		
		return false;
});

");
?>
<div id="resources-view" class="review-single-view">
	<div class="review-resources-title">
		Recursos Multimedias - Imagenes
	</div>
	<?php
	echo CHtml::openTag('div',array('class'=>'review-container-album'));
		foreach($modelAlbum as $item)
		{
				
			echo CHtml::openTag('div',array('class'=>'review-container-single-album'));	
			echo CHtml::openTag('div',array('id'=>'edit_image'.$item->Id,'class'=>"review-edit-image review-edit-image-album"));
			$urlUpdateAlbum = 'updateAlbum';
			if($browser['browser']=='IE')
			{
				$urlUpdateAlbum .= 'IE';				
			}
						
			echo CHtml::link('Editar Album',
			ReviewController::createUrl($urlUpdateAlbum,array('id'=>$item->Id)),
			array('class'=>'review-edit-image')
			);
			echo CHtml::closeTag('div');
			$images = array();
			$height=0;
			foreach($item->multimedias as $multi_item)
			{
				$image= array();
				$image['image'] = "images/".$multi_item->file_name;
				$image['small_image'] = "images/".$multi_item->file_name_small;
				$image['caption'] = $multi_item->description;
				if($multi_item->height_small>$height)
				{
					$height = $multi_item->height_small;
				}
				$images[]=$image;
			}
			$this->widget('ext.highslide.highslide', array(
											'images'=>$images,
											'Id'=>$item->Id,
											'height'=>$height,
			));
						
			echo CHtml::openTag('div',array('style'=>'margin-top:10px;font-weight:bold;'));
			echo $item->title;	
			echo CHtml::closeTag('div');
			echo CHtml::openTag('p',array('class'=>'single-formated-text'));
			echo $item->description;	
			echo CHtml::closeTag('p');
			echo CHtml::closeTag('div');
		}
		echo CHtml::closeTag('div');
		?>
</div>

<div class="row" style="text-align: center;">	
	<?php echo CHtml::button('Volver',array('class'=>'wall-action-submit-btn','id'=>'btnBack',));?>
</div>

