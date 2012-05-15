<?php
Yii::app()->clientScript->registerScript('form-album-create', "

$('#Album_title').change(function(){	
	$.post(
		'".AlbumController::createUrl('album/AjaxUpdateTitle')."',
		{
			id: $('#Album_Id_album').val(),
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
			id: $('#Album_Id_album').val(),
			description:$(this).val()
		}).success(
			function() 
			{ 
				$('#saveok2').animate({opacity: 'show'},2000);
				$('#saveok2').animate({opacity: 'hide'},2000);
			});
		}
	);
		

");
?>

<div class="wide form">
<?php $formAlbum=$this->beginWidget('CActiveForm', array(
	'id'=>'album-form',
	'action'=>ReviewController::createUrl('AjaxShareAlbum'),
	'method'=>'post',
));
echo $formAlbum->hiddenField($model,'Id_customer');
?>

	<?php echo CHtml::hiddenField('Album_Id_album',$model->Id,array('id'=>'Album_Id_album')); ?>
	<div class="row">
		<?php echo CHtml::label('T&iacute;tulo', 'Album_Title'); ?>
		<?php echo $formAlbum->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $formAlbum->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Descripci&oacute;n', 'Album_Title'); ?>
		<?php echo $formAlbum->textArea($model,'description',array('style'=>'width:500px;resize:none;')); ?>
		<?php echo $formAlbum->error($model,'description'); ?>
	</div>
		
<?php $this->endWidget(); ?>

<?php
$this->widget('ext.xupload.XUploadWidget', array(
                    'url' => AlbumController::createUrl('album/AjaxUpload'),
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
</div><!-- form -->