<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-with-gallery.js',CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-exe.js',CClientScript::POS_HEAD);
$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide.css');

Yii::app()->clientScript->registerScript('indexWall', "
$(window).scroll(function(){

	if  ($(window).scrollTop() == $(document).height() - $(window).height()){
		var lastId;
		var lastLeft = 0;
		if($('.view-single-right:last').attr('id')<$('.view-single-left:last').attr('id'))
		{
			lastId = $('.view-single-right:last').attr('id');
		}
		else
		{
			lastId= $('.view-single-left:last').attr('id');
			lastLeft = 1;
		}
		$.post('".WallController::createUrl('AjaxFillNextWall')."', 
			$('#Id_customer').serialize()+'&lastId='+lastId+'&lastLeft='+lastLeft
		).success(
		function(data){
			if(lastLeft){
				$('.view-single-left:last').after(data);
			}else{
				$('.view-single-right:last').after(data);
			}				
		}
		);
}
});
LoadPage();

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
}
)

function LoadPage()
{
	var id_customer =".$Id_customer."; 
	if(id_customer!=-1)
	{
		$('#Multimedia_Id_customer').val(id_customer);
		$('#Note_Id_customer').val(id_customer);
		$.post('".WallController::createUrl('AjaxFillWall')."', 
			$('#Id_customer').serialize()
		).success(
		function(data){
			$('#wallView').html(data)
		}
		);
		$('#wallView').animate({opacity: 'show'},240);
	}
}


$('#Id_customer').change(function(){
	$('#Multimedia_Id_customer').val($(this).val());
	$('#Note_Id_customer').val($(this).val());
	
	if($(this).val()!= ''){
		$('#loading').addClass('loading');
		$.post('".WallController::createUrl('AjaxFillWall')."', 
			$(this).serialize()
		).success(
		function(data){
			$('#loading').removeClass('loading');
			$('#wallView').html(data)
		}
		);
		$('#wallView').animate({opacity: 'show'},240);
	}
	else
	{
		$('#wallView').animate({opacity: 'hide'},240);	
		$('#wall-action-image').animate({opacity: 'hide'},240);
		$('#wall-action-note').animate({opacity: 'hide'},240);
		$('#uploadFile').val('');
		$('#Multimedia_description').val('');
		$('#Note_note').val('');
	}
	return false;
});

$('#cancelNote').click(function(){
	$('#wall-action-note').animate({opacity: 'hide'},240);
	$('#Note_note').val('');
});
$('#cancelImage').click(function(){
	$('#wall-action-image').animate({opacity: 'hide'},240);
	$('#uploadFile').val('');
	$('#Multimedia_description').val('');
});
$('#btnNote').click(function(){

	$('#wall-action-image').animate({opacity: 'hide'},240,function(){
	$('#wall-action-note').animate({opacity: 'show'},240);
	$('#uploadFile').val('');
	$('#Multimedia_description').val('');
});
});

$('#btnImage').click(function(){
	$('#Note_note').val('');
	$('#wall-action-note').animate({opacity: 'hide'},240,function()
	{
		$('#wall-action-image').animate({opacity: 'show'},240);
	});
});

$('#submitFile').click(function(){
	if($('#uploadFile').val())
	{
		var ext = $('#uploadFile').val().split('.').pop().toLowerCase(); 
		var allow = new Array('gif','png','jpg','jpeg'); 
		if(jQuery.inArray(ext, allow) == -1) { 
			alert('La extención no es válida');
			return false;
		}
	}
	else
	{
		alert('Debe seleccinar una imagen primero');
		return false;
	}
	$('#wall-action-image').animate({opacity: 'hide'},240);
	$('#loading').addClass('loading');
});
");
?>
<div class="wall-action-area" id="wall-action-area">
<div id="loading" class="loading-place-holder" >
</div>
<div id="customer" class="wall-action-ddl" >
	<?php	$customers = CHtml::listData($ddlCustomer, 'Id', 'CustomerDesc');?>
	<?php echo CHtml::label('Customer','Id_customer'); ?>
	<?php echo CHtml::dropDownList('Id_customer',$Id_customer, $customers,		
		array(
			'prompt'=>'Select a Customer',
			)		
		);
		?>
</div>

<?php
	echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnImage'));
		echo 'Imagenes';
	echo CHtml::closeTag('div');
	echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnNote'));
		echo 'Notas';
	echo CHtml::closeTag('div');	
?>

</div>

<div id="wall-action-note"  class='wall-action-area-note' style="display:none">
	<div class="wall-action-area-note-dialog">
	</div>
		<div class="form"  style="text-align: center;">
			<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'wall-form',
					'method'=>'post',
					'enableAjaxValidation'=>false,
			));
			echo $form->hiddenField($modelNote,'Id_customer');			
			?>
			<?php echo $form->textArea($modelNote,'note',array('rows'=>2, 'cols'=>110,'class'=>'wall-action-upload-file-description', 'placeholder'=>'Escriba una nota...')); ?>
			<div class="row" style="text-align: center;">
						<?php
							echo CHtml::button(
				                                'Publicar',
				                                array(
				                                'class'=>'wall-action-submit-btn',
				                                'id'=>'shareNote',
				                                	'ajax'=> array(
														'type'=>'POST',
														'url'=>WallController::createUrl('AjaxShareNote'),
														'beforeSend'=>'function(){
																$("#loading").addClass("loading");
																	if(! $.trim($("#Note_note").val()).length > 0)
																	{
																		alert("Debe completar la nota antes de publicarla");
																		return false;
																	}
														}',
														'success'=>'js:function(data)
														{																
															$("#loading").removeClass("loading");
															$("#Note_note").val("");
															$("#wall-action-note").animate({opacity: "hide"},240);
															$("#wallView").html(data);							
														}'
				                                	)
				                                )
				                                                         
				                            ); 
						?>
			<?php
				echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'cancelNote',)); 
			?>
						
			</div>
			<?php $this->endWidget(); ?>
		</div><!-- form -->		

</div>
<div id="wall-action-image"  class='wall-action-area-note' style="display:none">

	<div class="wall-action-area-images-dialog">
	</div>
	<div class="wide form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>WallController::createUrl('AjaxShareImage'),
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
			'method'=>'post',
			)); 
			echo $form->hiddenField($modelMultimedia,'Id_customer');			
		?>
		<!-- JavaScript is called by OnChange attribute -->
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
				<?php echo $form->textArea($modelMultimedia,'description',array('rows'=>2, 'cols'=>40,'class'=>'wall-action-upload-file-description', 'placeholder'=>'Escriba un comentario...')); ?>
				<?php echo $form->error($modelMultimedia,'description'); ?>
			</div>
		<div class="row" style="text-align: center;">
			<input type="submit" value="Publicar" name="file" id="submitFile" class="wall-action-submit-btn">
			<?php
				echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'cancelImage',)); 
			?>
		</div>
		
		<?php $this->endWidget(); ?>
	</div><!-- image-form -->
</div>
<br>
<div id="wallView">
<!-- data container -->
</div>		
