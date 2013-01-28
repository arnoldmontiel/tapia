<?php
Yii::app()->clientScript->registerScript('viewDocResource', "
$('#btnBack').click(function(){
		window.location = '".ReviewController::createUrl('index',array('Id_customer'=>$Id_customer))."';
		return false;
});


");
?>
	
<div class="review-area-files" id="files_container">
	<div class="review-resources-title">
		Recursos Multimedias - Documentos T&eacute;cnicos
	</div>
	<div class="review-action-area-files" >
		<?php
		$currentDocTypeDesc = '';
		$isFirst = true;
		$isCurrent = true; 
		foreach ($modelMultimedia as $item)
		{
			if($item->Id_multimedia_type != 1)
			{
				if($isFirst)
				{
					$currentDocTypeDesc = $item->documentType->name;
					$isFirst = false;
					$tab = "";
					
					echo CHtml::openTag('div',array('class'=>'review-update-single-files'));
					echo $currentDocTypeDesc;
					echo CHtml::closeTag('div');
				}
								
				if($currentDocTypeDesc != $item->documentType->name)
				{
					$currentDocTypeDesc = $item->documentType->name;
					echo CHtml::openTag('div',array('class'=>'review-update-single-files'));
					echo $currentDocTypeDesc;
					echo CHtml::closeTag('div');
					$isCurrent = true;
				}
				
				echo CHtml::openTag('div',array('id'=>'file_'.$item->Id,'class'=>'review-update-single-files'));
					if($isCurrent)
					{
						echo CHtml::openTag('div',array('class'=>'review-tech-files-name'));
						$isCurrent = false;
					}
					else
						echo CHtml::openTag('div',array('class'=>'review-tech-child-files-name'));
					
						echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
							switch ( $item->Id_multimedia_type) {
								case 4:
									echo CHtml::image('images/autocad_resource.png','',array('style'=>'width:25px;'));
									break;
								case 5:
									echo CHtml::image('images/word_resource.png','',array('style'=>'width:25px;'));
									break;
								case 6:
									echo CHtml::image('images/excel_resource.png','',array('style'=>'width:25px;'));
									break;
								case 3:
									echo CHtml::image('images/pdf_resource.png','',array('style'=>'width:25px;'));
									break;
							}
						echo CHtml::closeTag('div');
						echo CHtml::link(CHtml::encode($item->file_name),Yii::app()->baseUrl.'/docs/'.$item->file_name,array('target'=>'_blank'));
						echo CHtml::encode(' '.round(($item->size / 1024), 2));
						echo CHtml::encode(' (Kb) ');						
					echo CHtml::closeTag('div');
					echo CHtml::openTag('div',array('class'=>'review-tech-files-descr'));						
						echo CHtml::encode($item->description);
						echo "<p>Subido por : ". $item->username ." el ". $item->creation_date . "</p>";
					echo CHtml::closeTag('div');
				echo CHtml::closeTag('div');
			}
		}
		?>
	</div>
</div>
<div class="row" style="text-align: center;">	
	<?php echo CHtml::button('Volver',array('class'=>'wall-action-submit-btn','id'=>'btnBack',));?>
</div>

