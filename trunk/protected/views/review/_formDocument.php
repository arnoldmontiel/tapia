<?php
Yii::app()->clientScript->registerScript('form-album', "

$('#uploadFile').change(
function()
{
	$('#fake-uploadFile').val($(this).val());
}
)
$('#uploadFile').hover(
function()
{
	$('#upload-file-btn').addClass('wall-action-upload-file-btn-hover');
},
function()
{
	$('#upload-file-btn').removeClass('wall-action-upload-file-btn-hover');
});

$('#btnCancelDoc').click(function(){
	$('#wall-action-doc').animate({opacity: 'hide'},240);
	$('#uploadFile').val('');
	$('#Multimedia_description').val('');
});

$('#btnPublicDoc').click(function(){
	if($('#uploadFile').val())
	{
		var ext = $('#uploadFile').val().split('.').pop().toLowerCase(); 
		
		var allow = new Array('pdf','dwg','dxf');
			
		if(jQuery.inArray(ext, allow) == -1) { 
			alert('La extención no es válida');
			return false;
		}
	}
	else
	{
		alert('Debe seleccinar un documento primero');
		return false;
	}
	$('#wall-action-doc').animate({opacity: 'hide'},240);
	$('#loading').addClass('loading');
});

");
?>

<div class="wide form">
<?php $formDocument=$this->beginWidget('CActiveForm', array(
	'id'=>'document-form',
	'action'=>ReviewController::createUrl('AjaxShareDocument'),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'method'=>'post',
));

echo CHtml::hiddenField('Id_review',$Id_review,array('id'=>'Id_review'));
echo CHtml::hiddenField('Id_customer',$Id_customer,array('id'=>'Id_customer'));
?>

	<div class="wall-action-upload-file-container">
		<div class="wall-action-upload-file-btn-container">
			<input type="file" name="file" id="uploadFile" class='wall-action-upload-file-hidden'/>
			<div id= 'upload-file-btn' class="wall-action-upload-file-btn">
				Seleccionar
			</div>
		</div>
		<div class="wall-action-fake-uploadFile">
			<input id='fake-uploadFile' class="wall-action-fake-uploadFile"/>
		</div>
	</div>
	
	<div class="row">
		<?php echo $formDocument->textArea($model,'description',array('rows'=>2, 'cols'=>40,'class'=>'wall-action-upload-file-description', 'placeholder'=>'Escriba un comentario...')); ?>
		<?php echo $formDocument->error($model,'description'); ?>
	</div>

	<div class="row" style="text-align: center;">
		<?php echo CHtml::button('Publicar',array('type'=>'submit', 'class'=>'wall-action-submit-btn','id'=>'btnPublicDoc',));?>
		<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancelDoc',));?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->