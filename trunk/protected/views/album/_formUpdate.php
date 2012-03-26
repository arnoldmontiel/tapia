<?php

Yii::app()->clientScript->registerScript('updateAlbum', "


");
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'album-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

<?php 

	foreach ($model->multimedias as $item)
	{
		echo "<div id=picture_".$item->Id.">";
		$this->widget('ext.highslide.highslide', array(
								'smallImage'=>"images/".$item->file_name_small,
								'image'=>"images/".$item->file_name,
								'caption'=>$item->description,
								'Id'=>$item->Id,
		)); 
		echo CHtml::imageButton(
		                                'images/add_all_blue.png',
								array(
		                                'title'=>'Delete Image',
		                                'style'=>'width:30px;',
		                                'id'=>'delete_'.$item->Id,
		                                	'ajax'=> array(
												'type'=>'GET',
												'url'=>AlbumController::createUrl('AjaxRemoveImage',array('IdMultimedia'=>$item->Id)),
												'beforeSend'=>'function(){
															if(!confirm("Are you sure you want to delete this image?")) 
																return false;
																}',
												'success'=>'js:function(data)
												{
													$("#picture_'.$item->Id.'").attr("style","display:none");
												}'
									)
								)
		 
			);
		echo "</div>";
	}
	?>	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

<?php
$this->widget('ext.xupload.XUploadWidget', array(
                    'url' => AlbumController::createUrl('AjaxUpload',array('id'=>$model->Id)),
					'multiple'=>true,
					'name'=>'file',
					'options' => array(
						'acceptFileTypes' => '/(\.|\/)(gif|jpeg|png)$/i',
						'onComplete' => 'js:function (event, files, index, xhr, handler, callBack) {

							id = jQuery.parseJSON(xhr.response).id;
							$tr = $(document).find("#"+id);
							$tr.find(".file_upload_cancel button").click(function(){
								var target = $(this);
											
								$.get("'.AlbumController::createUrl('AjaxRemoveImage').'",
 									{
										IdMultimedia:$(target).parent().parent().attr("id")
 								}).success(
 									function(data) 
 									{
 										
 										$(target).parent().parent().attr("style","display:none");	
 									}
 								);
                         		
 							});
                        		

                         }'
					),
));
?>
</div><!-- form -->