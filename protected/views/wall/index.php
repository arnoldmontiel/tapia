<?php

Yii::app()->clientScript->registerScript('indexWall', "
	
$('#btnNote').click(function(){

	$('#divNote').animate({opacity: 'show'},240);
	$('#divImage').animate({opacity: 'hide'},240);
	$('#uploadFile').val('');
});

$('#btnImage').click(function(){
	$('#Note_note').val('');
	$('#divNote').animate({opacity: 'hide'},240);
	$('#divImage').animate({opacity: 'show'},240);
});

$('#submitFile').click(function(){
	if($('#uploadFile').val())
	{
		var ext = $('#uploadFile').val().split('.').pop().toLowerCase(); 
		var allow = new Array('gif','png','jpg','jpeg'); 
		if(jQuery.inArray(ext, allow) == -1) { 
			alert('You are trying to upload a invalid file extencion');
			return false;
		}
	}
	else
	{
		alert('To upload, first select a file');
		return false;
	}

});
");
?>

<h1>Walls</h1>

<?php
	
	echo CHtml::image('images/notes.png','',array(
												'title'=>'note',
												'width'=>'24px',
												'id'=>'btnNote',											
										)
	);
	echo CHtml::image('images/images.png','',array(
													'title'=>'image',
													'width'=>'30px',
													'id'=>'btnImage',											
	));
?>

<div id="divNote">

	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'wall-form',
				'method'=>'post',
				'enableAjaxValidation'=>false,
		));
		
		?>
		<?php  echo $form->textField($modelNote,'note',array('size'=>60,'maxlength'=>100)); ?> 
		<br>
		<div style="display: inline-block;widht:100px;">
					<?php
						echo CHtml::imageButton(
			                                'images/share.png',
			                                array(
			                                'title'=>'Publicar',
			                                'width'=>'100px',
			                                'id'=>'shareNote',
			                                	'ajax'=> array(
													'type'=>'POST',
													'url'=>WallController::createUrl('AjaxShareNote'),
													'beforeSend'=>'function(){
													
																if(! $.trim($("#Note_note").val()).length > 0)
																{
																	alert("You can not post an empty note");
																	return false;
																}
													}',
													'success'=>'js:function(data)
													{	
														$.fn.yiiListView.update("listWall-view");
														
														$("#Note_note").val("");
													}'
			                                	)
			                                )
			                                                         
			                            ); 
					?>
		</div>
		<?php $this->endWidget(); ?>
	</div><!-- form -->		
</div>
<div id="divImage" style="display:none">
	<div class="wide form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>WallController::createUrl('AjaxShareImage'),
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
			'method'=>'post',
		)); ?>
		<label for="file">text file uploader:</label><br>
		<!-- JavaScript is called by OnChange attribute -->
		<input type="file" name="file" id="uploadFile">
		<div class="row">
				<?php echo $form->labelEx($model,'description'); ?>
				<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
				<?php echo $form->error($model,'description'); ?>
			</div>
		<input type="submit" value="upload" name="file" id="submitFile">

		<?php $this->endWidget(); ?>
	</div><!-- image-form -->
</div>
<br>
<div id="wallView">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'id'=>'listWall-view',
	'summaryText' =>"",
)); ?>
</div>		
