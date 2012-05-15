<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-with-gallery.js',CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-exe.js',CClientScript::POS_HEAD);
$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide.css');

Yii::app()->clientScript->registerScript('updateAlbum-review', "
$('#Album_description').autoResize();
$('#Album_title').change(function(){
	
		$.post(
			'".AlbumController::createUrl('album/AjaxUpdateTitle')."',
			{
			 	id: ".$model->Id.",
				title:$(this).val()
			 }).success(
					function() 
					{ 
						$('#saveok').animate({opacity: 'show'},2000);
						$('#saveok').animate({opacity: 'hide'},2000);
						
					});
		});
		
$('#Album_description').change(function(){
	
		$.post(
			'".AlbumController::createUrl('album/AjaxUpdateDescription')."',
			{
			 	id: ".$model->Id.",
				description:$(this).val()
			 }).success(
					function() 
					{ 
						$('#saveok2').animate({opacity: 'show'},2000);
						$('#saveok2').animate({opacity: 'hide'},2000);
						
					});
		});


$('#images_container').find('textarea').each(
									function(index, item){
												$(item).change(function(){
													var target = $(this);
													var it = $(item);
													$.get('".AlbumController::createUrl('album/AjaxAddImageDescription')."',
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

		
$(document).keypress(function(e) {
    if(e.keyCode == 13) 
    {
    	if($('*:focus').attr('id') == 'Album_title' && $('*:focus').val() != '')
    	{
    		$('#Album_title').blur();
    		return false;
    	}
    }
  });
");
?>
<div class="album-action-area">

<div class="wide form" >

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'album-form-update',
	'enableAjaxValidation'=>false,
)); ?>
<?php
echo CHtml::imageButton(
		                                'images/remove.png',
								array(
		                                'class'=>'album-action-remove',
		                                'title'=>'Borrar Album',
										'id'=>'delete_'.$model->Id,
		                                	'ajax'=> array(
												'type'=>'GET',
												'url'=>AlbumController::createUrl('album/AjaxRemoveAlbum',array('id'=>$model->Id)),
												'beforeSend'=>'function(){
															if(!confirm("\u00BFEst\u00e1 seguro de eliminar este album?")) 
																return false;
																}',
												'success'=>'js:function(data)
												{
													window.location = "'.ReviewController::createUrl('update',array('id'=>$model->Id_review)).'";
												}'
									)
								)
		 
			);
?>
	<div class="row">
		<?php echo CHtml::label('T&iacute;tulo', 'Album_Title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo CHtml::image("images/save_ok.png","",array("id"=>"saveok", "style"=>"display:none", "width"=>"20px", "height"=>"20px")); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Descripci&oacute;n', 'Album_Title'); ?>
		<?php echo $form->textArea($model,'description',array('style'=>'width:500px;resize:none;')); ?>
		<?php echo CHtml::image("images/save_ok.png","",array("id"=>"saveok2", "style"=>"display:none", "width"=>"20px", "height"=>"20px")); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="album-action-area-images" style="padding-left:70px;margin-top:40px;" id="images_container">
	<?php 

	foreach ($model->multimedias as $item)
	{
		echo CHtml::openTag('div',array('id'=>'picture_'.$item->Id,'class'=>'review-attach-image'));
		echo CHtml::imageButton(
		                                'images/remove.png',
								array(
		                                'class'=>'album-action-remove',
		                                'style'=>'right:10px;top:5px;z-index:10;',
										'title'=>'Borrar imagen',
										'id'=>'delete_'.$item->Id,
		                                	'ajax'=> array(
												'type'=>'GET',
												'url'=>AlbumController::createUrl('album/AjaxRemoveImage',array('IdMultimedia'=>$item->Id)),
												'beforeSend'=>'function(){
															if(!confirm("\u00BFEst\u00e1 seguro de eliminar esta imagen?")) 
																return false;
																}',
												'success'=>'js:function(data)
												{
													$("#picture_'.$item->Id.'").attr("style","display:none");
												}'
									)
								)
		 
			);
		echo CHtml::openTag('div',array('class'=>'review-update-image'));
		$this->widget('ext.highslide.highslide', array(
								'smallImage'=>"images/".$item->file_name_small,
								'image'=>"images/".$item->file_name,
								'caption'=>'',
								'Id'=>$item->Id,
								'small_width'=>240,
								'small_height'=>180,
		
		)); 
		echo CHtml::closeTag('div');
		echo CHtml::openTag('div',array());
		echo CHtml::textArea('photo_description',$item->description,
							array(
								'id'=>$item->Id,
								'placeholder'=>'Escriba una description...',
								'class'=>'review-update-album-descr',
							)
						
			);
		echo CHtml::closeTag('div');		
		echo CHtml::closeTag('div');
	}
	?>	
	</div>

<?php $this->endWidget(); ?>

<?php
$this->widget('ext.xupload.XUploadWidget', array(
                    'url' => AlbumController::createUrl('album/AjaxUpload',array('idAlbum'=>$model->Id,'idReview'=>$model->Id_review)),
					'multiple'=>true,
					'name'=>'file',
					'options' => array(
						'acceptFileTypes' => '/(\.|\/)(gif|jpeg|png)$/i',
						'onComplete' => 'js:function (event, files, index, xhr, handler, callBack) {

							id = jQuery.parseJSON(xhr.response).id;
							$tr = $(document).find("#"+id);
							$tr.find(".file_upload_cancel button").click(function(){
								var target = $(this);
											
								$.get("'.AlbumController::createUrl('album/AjaxRemoveImage').'",
 									{
										IdMultimedia:$(target).parent().parent().attr("id")
 								}).success(
 									function(data) 
 									{
 										
 										$(target).parent().parent().attr("style","display:none");	
 									}
 								);
                         		
 							});
 							
 							$tr.find("#photo_description").change(function(){
								var target = $(this);
								
								$.get("'.AlbumController::createUrl('album/AjaxAddImageDescription').'",
 									{
										IdMultimedia:$(target).parent().parent().attr("id"),
										description:$(this).val()
 								}).success(
 									function(data) 
 									{
 										
 									}
 								);
                         		
 							});
                         }'
					),
));
?>
	<div class="row" style="text-align: center;">
			<?php 
				echo CHtml::link('Volver',
					ReviewController::createUrl('update',array('id'=>$model->Id_review)),
					array('id'=>'finish-btn','class'=>'wall-action-submit-btn')
				);
			?>
	</div>

</div><!-- form -->
</div>