<?php
$this->breadcrumbs=array(
	'Walls',
);

Yii::app()->clientScript->registerScript('indexWall', "
	
$('#btnNote').click(function(){

	$('#divNote').animate({opacity: 'show'},240);
	$('#divImage').animate({opacity: 'hide'},240);
});

$('#btnImage').click(function(){

	$('#divNote').animate({opacity: 'hide'},240);
	$('#divImage').animate({opacity: 'show'},240);
	return false;
});


");
?>

<h1>Walls</h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'wall-form',
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
));

?>
<?php
	
	echo CHtml::image('images/notes.png','',array(
												'title'=>'note',
												'width'=>'24px',
												'id'=>'btnNote',											
										)
	);
	echo CHtml::imageButton('images/images.png',array(
													'title'=>'image',
													'width'=>'30px',
													'id'=>'btnImage',											
	));
?>

<div id="divNote">
<?php

 $this->widget('ext.richtext.jwysiwyg', array(
 	'id'=>'noteContainer',	// default is class="ui-sortable" id="yw0"	
 	'notes'=> null
 			));

?>
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
																if(! $.trim($("#wysiwyg-wysiwyg-iframe").contents().find("body").text()).length > 0)
																{
																	alert("You can not post an empty note");
																	return false;
																}
													}',
													'success'=>'js:function(data)
													{
														$.fn.yiiListView.update("listWall-view");
														$("#wysiwyg-wysiwyg-iframe").contents().find("body").text("");
													}'
			                                	)
			                                )
			                                                         
			                            ); 
					?>
		</div>
</div>		
<div id="divImage" style="display:none">
	<?php $model = new Multimedia; ?>
	<div class="row">
		<?php echo $form->labelEx($model,'uploadedFile'); ?>
		<?php echo $form->fileField($model,'uploadedFile');?>
		<?php echo $form->error($model,'uploadedFile'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
<input type="text" id="pis">
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	<div style="display: inline-block;widht:100px;">
					<?php
						echo CHtml::imageButton(
			                                'images/share.png',
			                                array(
			                                'title'=>'Publicar',
			                                'width'=>'100px',
			                                'id'=>'shareImage',
			                                	'ajax'=> array(
													'type'=>'POST',
													'url'=>WallController::createUrl('AjaxShareImage'),
													'beforeSend'=>'function(){
// 																if(! $.trim($("#wysiwyg-wysiwyg-iframe").contents().find("body").text()).length > 0)
// 																{
// 																	alert("You can not post an empty note");
// 																	return false;
// 																}
													}',
													'success'=>'js:function(data)
													{
														$.fn.yiiListView.update("listWall-view");
														$("#wysiwyg-wysiwyg-iframe").contents().find("body").text("");
													}'
			                                	)
			                                )
			                                                         
			                            ); 
					?>
		</div>
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
<?php $this->endWidget(); ?>

</div><!-- form -->