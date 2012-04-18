<?php
Yii::app()->clientScript->registerScript('updateDocument', "

$('#btnBack').click(function(){
		window.location = '".ReviewController::createUrl('update',array('id'=>$model->Id))."';
		return false;
});


$('#files_container').find('textarea').each(
				function(index, item){
							$(item).change(function(){
								var target = $(this);
								var it = $(item);
								$.get('".MultimediaController::createUrl('multimedia/AjaxAddResourceDescription')."',
 									{
										IdMultimedia:$(target).attr('id'),
										description:$(this).val()
 								}).success(
 									function(data) 
 									{
 										
 									}
 								);
							});

});	

");
?>
<div class="review-area-files" id="files_container">
	<div class="review-action-area-files" >
		<?php
		foreach ($model->multimedias as $item)
		{
			if($item->Id_multimedia_type != 1)
			{
				echo CHtml::openTag('div',array('id'=>'file_'.$item->Id,'class'=>'review-update-single-files'));
					echo CHtml::openTag('div',array('class'=>'review-update-files-name'));
						echo CHtml::link(CHtml::encode($item->file_name),Yii::app()->baseUrl.'/docs/'.$item->file_name,array('target'=>'_blank'));
						echo CHtml::encode(' '.round(($item->size / 1024), 2));
						echo CHtml::encode(' (Kb) ');
					echo CHtml::closeTag('div');
					echo CHtml::openTag('div',array('class'=>'review-update-files-descr'));
						
						echo CHtml::textArea('photo_description',$item->description,
						array(
							'id'=>$item->Id,
							'placeholder'=>'Escriba una description...',
							'class'=>'review-update-files-descr'
						)
						);
						echo CHtml::imageButton(
                                'images/remove.png',
						array(
                               
                                'title'=>'Borrar documento',
								'id'=>'delete_'.$item->Id,
								'class'=>'album-action-remove  album-action-remove-update-file',
                                	'ajax'=> array(
										'type'=>'GET',
										'url'=>MultimediaController::createUrl('multimedia/AjaxRemoveResource',array('IdMultimedia'=>$item->Id)),
										'beforeSend'=>'function(){
													if(!confirm("\u00BFEst\u00e1 seguro de eliminar este documento?")) 
														return false;
														}',
										'success'=>'js:function(data)
										{
											$("#file_'.$item->Id.'").attr("style","display:none");
										}'
								)
							)
						);
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