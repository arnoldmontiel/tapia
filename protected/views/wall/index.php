<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-with-gallery.js',CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-exe.js',CClientScript::POS_HEAD);
$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide.css');
Yii::app()->clientScript->registerScript('indexWall', "


function bindEvents(data)
{
	$(data).children().children().each(
			function(index, item){
			
				$(item).children().children('img').each(
								function(i, imgItem){
									if(typeof $(imgItem).attr('id') != 'undefined')
									{
										if ($(imgItem).attr('id').indexOf('left') >= 0 || $(imgItem).attr('id').indexOf('right') >= 0)
										{
											$('#'+$(imgItem).attr('id')).click(function(){
												var id = $(imgItem).attr('id');
												
												var idNote = id.split('_')[2];
												var idParent = id.split('_')[3];
												
												var side = '_viewRight';
												if (id.indexOf('left') >= 0)
													side = '_viewLeft';
													
												var type = 'note';
												if (id.indexOf('multimedia') >= 0)
													type = 'multimedia';
												else{
													if (id.indexOf('album') >= 0)
														type = 'album';
												}
												var getParam = '&id='+idNote+'&type='+type+'&side='+side+'&idParent='+idParent;
												
												$.ajax({
														type : 'GET',
														url : '" . WallController::createUrl('AjaxRemoveSingleNote') ."' + getParam,
														beforeSend : function(){
																	if(!confirm('Seguro que quiere borrar esta nota?')) 
																		return false;
																		},
														success : function(data)
														{
															$('#'+type+'Container_'+idParent).html(data);
															bindEvents($('#'+type+'Container_'+idParent));
														}
												});
											});
										}
									} //end typeof undefined
								}
				); // end sub note click event
							
				$('#'+$(item).children().children('textarea').attr('id')).change(function(){
							var idParent = $(item).attr('id');
							var id = $(this).attr('id').split('_')[1];
							var value = $(this).val();
							
							var type = 'note';
							if($(item).children().children('textarea').attr('id').indexOf('multimedia') >= 0){
								type = 'multimedia';
							}else{
								if($(item).children().children('textarea').attr('id').indexOf('album') >= 0){
									type = 'album';
								}
							}
							
							var side = '_viewRight';
							if ($(item).attr('class').indexOf('left') >= 0)
								side = '_viewLeft';
								
							$.post(
								'".WallController::createUrl('AjaxAddNoteTo')."',
								{
								 	id: id,
									value: $(this).val(),
									side: side,
									type: type,
									idCustomer: $('#Id_customer').val(),
								 }).success(
										function(data) 
										{ 
											$('#'+type+'Container_'+idParent).html(data);
											bindEvents($('#'+type+'Container_'+idParent));
											
										}
									);
				}); //end change event				
				
				$('#delete_' + $(item).attr('id')).click(function(){
							var id = $(item).attr('id');
							$.ajax({
												type : 'GET',
												url : '" . WallController::createUrl('AjaxRemoveBubble') ."' + '&id='+id,
												beforeSend : function(){
															if(!confirm('Are you sure you want to delete this bubble?')) 
																return false;
																},
												success : function(data)
												{
													$('#'+$(item).attr('id')).attr('style','display:none');
													$('#Id_customer').change();
												}
										});
				}); //end remove click event
		}); //end each
}

$(document).keypress(function(e) {
    if(e.keyCode == 13) 
    {
    	if($('*:focus').attr('id').indexOf('multimedia') >= 0 && $('*:focus').val() != '')
    	{
    		$('#'+$('*:focus').attr('id')).blur();
    		return false;
    	}
    	
		if($('*:focus').attr('id').indexOf('album') >= 0 && $('*:focus').val() != '')
    	{
    		$('#'+$('*:focus').attr('id')).blur();
    		return false;
    	}
    	
    	if($('*:focus').attr('id').indexOf('note') >= 0 && $('*:focus').val() != '')
    	{
    		$('#'+$('*:focus').attr('id')).blur();
    		return false;
    	}
		return false; 
    }
  });
  
$(window).scroll(function(){

	if  ($(window).scrollTop() == $(document).height() - $(window).height()){
		var lastId;
		var lastLeft = 0;
		$('#big-loading').addClass('big-loading');
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
			$('#big-loading').removeClass('big-loading');
			if(lastLeft){
				$('.view-single-left:last').after(data);
			}else{
				$('.view-single-right:last').after(data);
			}
			
			bindEvents(data);
						
		});
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
		$('#btn-box').removeClass('div-hidden');
		$.post('".WallController::createUrl('AjaxFillWall')."', 
			$('#Id_customer').serialize()
		).success(
			function(data){
				$('#wallView').html(data);
				bindEvents(data);		
		});
		
		$('#wallView').animate({opacity: 'show'},240);
	}
}



$('#Id_customer').change(function(){
	$('#Multimedia_Id_customer').val($(this).val());
	$('#Note_Id_customer').val($(this).val());
	
	if($(this).val()!= ''){
		$('#btn-box').removeClass('div-hidden');
		$('#loading').addClass('loading');
		$.post('".WallController::createUrl('AjaxFillWall')."', 
			$(this).serialize()
		).success(
		function(data){
			$('#loading').removeClass('loading');
			$('#wallView').html(data);
			bindEvents(data);
		});
		
		$('#wallView').animate({opacity: 'show'},240);
	}
	else
	{
		$('#btn-box').addClass('div-hidden');
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

	$('#wall-action-image').animate({opacity: 'hide'},240,function()
	{
		$('#wall-action-album').animate({opacity: 'hide'},240,function()
		{
			$('#wall-action-note').animate({opacity: 'show'},240);
			$('#Multimedia_description').val('');
		});
	});
});

$('#btnDoc').click(function(){
	$('#Note_note').val('');
	$('#wall-action-note').animate({opacity: 'hide'},240,function()
	{
		$('#wall-action-album').animate({opacity: 'hide'},240,function()
		{
			$('#wall-action-image').animate({opacity: 'show'},240);
			$('#docType').val('3'); // PDF
			$('#arrow').removeClass('wall-action-area-images-dialog');
			$('#arrow').addClass('wall-action-area-docs-dialog');
		});
	});
});

$('#btnImage').click(function(){
	$('#Note_note').val('');
	$('#wall-action-note').animate({opacity: 'hide'},240,function()
	{
		$('#wall-action-album').animate({opacity: 'hide'},240,function()
		{
			$('#wall-action-image').animate({opacity: 'show'},240);
			$('#docType').val('1'); // IMAGE
			$('#arrow').removeClass('wall-action-area-docs-dialog');
			$('#arrow').addClass('wall-action-area-images-dialog');
		});
	});
});

$('#btnAlbum').click(function(){
		$('#loading').addClass('loading');
		$.post('".WallController::createUrl('AjaxCreateAlbum')."', 
			$('#Id_customer').serialize()
		).success(
		function(data){
			$('#loading').removeClass('loading');
			$('#_form').attr('action','".AlbumController::createUrl('album/AjaxUpload')."'+'&id='+data);
			$('#Album_Id_album').val(data);
			$('#wall-action-note').animate({opacity: 'hide'},240,function()
			{
				$('#wall-action-image').animate({opacity: 'hide'},240,function()
				{
					$('#wall-action-album').animate({opacity: 'show'},240);
				});
			});
		}
		);
});

$('#submitFile').click(function(){
	if($('#uploadFile').val())
	{
		var ext = $('#uploadFile').val().split('.').pop().toLowerCase(); 
		
		var allow;
		if($('#docType').val() == 1) 
			allow = new Array('gif','png','jpg','jpeg');
		else
			allow = new Array('pdf');
			
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

$('#btnCancelAlbum').click(function(){
	$('#loading').addClass('loading');
	$.post('".WallController::createUrl('AjaxCancelAlbum')."', 
		$('#Album_Id_album').serialize()
	).success(
	function(data){
		$('#loading').removeClass('loading');
		$('#wall-action-album').animate({opacity: 'hide'},240,
			function(){		
				$('#files').html('');
			$('#Album_description').val('');
			$('#Album_title').val('');
		});
	});
});

$('#btnPublicAlbum').click(function(){
	$('#loading').addClass('loading');
	$.post('".WallController::createUrl('AjaxFillWall')."', 
		$('#Id_customer').serialize()
	).success(
	function(data){
		$('#loading').removeClass('loading');
		$('#wallView').html(data);
		bindEvents(data);
		$('#wall-action-album').animate({opacity: 'hide'},240,
		function()
		{
			$('#files').html('');
			$('#Album_description').val('');
			$('#Album_title').val('');
		});		
	}
	);

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
	echo CHtml::openTag('div',array('class'=>'wall-action-box-btn div-hidden','id'=>'btn-box'));
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnImage'));
			echo 'Imagenes';
		echo CHtml::closeTag('div');
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnAlbum'));
			echo 'Album';
		echo CHtml::closeTag('div');	
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnNote'));
			echo 'Notas';
		echo CHtml::closeTag('div');	
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnDoc'));
			echo 'Documentos';
		echo CHtml::closeTag('div');
	echo CHtml::closeTag('div');	
?>

</div>

<!-- *************** NOTE ******************************* -->

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
															bindEvents(data);
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

<!-- *************** IMAGE ******************************* -->

<div id="wall-action-image"  class='wall-action-area-note' style="display:none">

	<div id="arrow" class="wall-action-area-images-dialog">
	</div>
	<div class="wide form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>WallController::createUrl('AjaxShareImage'),
			'htmlOptions'=>array('enctype'=>'multipart/form-data'),
			'method'=>'post',
			)); 
			echo $form->hiddenField($modelMultimedia,'Id_customer');	
			echo CHtml::hiddenField('docType','',array('Id'=>'docType'));
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

<!-- *************** ALBUM ******************************* -->

<div id="wall-action-album"  class='wall-action-area-note' style="display:none">
	<div class="wall-action-area-album-dialog">
	</div>
	<?php 
		$model = new Album;
		$this->renderPartial('_formAlbum',array('model'=>$model));
	?>
	<div class="row" style="text-align: center;">
		<?php echo CHtml::button('Publicar',array('class'=>'wall-action-submit-btn','id'=>'btnPublicAlbum',));?>
		<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancelAlbum',));?>
	</div>
		
</div>
<br>
<div id="wallView">
<!-- data container -->
</div>		
<div id="big-loading" class="big-loading-place-holder" >
</div>
