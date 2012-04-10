<?php
Yii::app()->clientScript->registerScript('UpdateReview', "

if('".$idNote."'!='')
	$('#wall-action-note').animate({opacity: 'show'},240);


$('#btnAlbum').click(function(){
		$('#loading').addClass('loading');
		$.post('".AlbumController::createUrl('album/AjaxCreateAlbum')."', 
			{
				idCustomer: ".$model->Id_customer.",
				idReview: ".$model->Id."
			}
		).success(
		function(data){
			$('#loading').removeClass('loading');
			//debugger;
			var param = '&idAlbum='+data+'&idReview='+".$model->Id.";
			$('#_form').attr('action','".AlbumController::createUrl('album/AjaxUpload')."'+param);
			$('#Album_Id_album').val(data);
		
			$('#wall-action-note').animate({opacity: 'hide'},240,function()
			{
				$('#wall-action-album').animate({opacity: 'show'},240);
				$('#files').html('');
				$('#Album_description').val('');
				$('#Album_title').val('');
			});
		
		}
		);
});

$('#btnNote').click(function(){
	
	$('#loading').addClass('loading');
	$.post('".NoteController::createUrl('note/AjaxCreateNote')."', 
		{
			idCustomer: ".$model->Id_customer.",
			idReview: ".$model->Id."
		}
	).success(
	function(data){
		$('#loading').removeClass('loading');
		$('#Note_Id_note').val(data);
		$('#wall-action-album').animate({opacity: 'hide'},240,function()
		{
					
			$('#note-images').animate({opacity: 'hide'},240);
			$('#wall-action-note').animate({opacity: 'show'},240);
			$('#Note_note').val('');
		});
	
	}
	);
	
});

$('#btnCancelNote').click(function(){
	$('#loading').addClass('loading');
	$.post('".NoteController::createUrl('note/AjaxCancelNote')."', 
		$('#Note_Id_note').serialize()
	).success(
	function(data){
		$('#loading').removeClass('loading');
		$('#wall-action-note').animate({opacity: 'hide'},240,
			function(){		
			$('#Note_note').val('');
		});
	});
});

$('#btnCancelAlbum').click(function(){
	$('#loading').addClass('loading');
	$.post('".AlbumController::createUrl('album/AjaxCancelAlbum')."', 
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

$('#Review_review').change(function(){
	$.post(
		'".ReviewController::createUrl('AjaxUpdateReview')."',
		{
			id: ".$model->Id.",
			review:$(this).val(),
		}).success(
			function() 
			{ 

			});
});


$('#Review_description').change(function(){
	$.post(
		'".ReviewController::createUrl('AjaxUpdateDescription')."',
		{
			id: ".$model->Id.",
			description:$(this).val(),
		}).success(
			function() 
			{ 

			});
});
	
$('#btnAttachToNote').click(function(){
	
	var url = '".ReviewController::createUrl('AjaxAttachImage',array('id'=>$model->Id))."';
	window.location = url + '&idNote='+$('#Note_Id_note').val();
	return false;
});

");
?>


	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($model); ?>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'review'); ?>
		<?php echo CHtml::activeTextField($model,'review'); ?>
		<?php echo CHtml::error($model,'review'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'description'); ?>
		<?php echo CHtml::activeTextArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo CHtml::error($model,'description'); ?>
	</div>



<div class="wall-action-area" id="wall-action-area">
<div id="loading" class="loading-place-holder" >
</div>
<?php
	echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'btn-box'));
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
	<?php 
		
		$modelNote = (isset($idNote))? Note::model()->findByPk($idNote):new Note;
		$this->renderPartial('_formNote',array('model'=>$modelNote));
	?>		
	<div class="row" style="text-align: center;">
		<?php echo CHtml::button('Publicar',array('class'=>'wall-action-submit-btn','id'=>'btnPublicNote',));?>
		<?php echo CHtml::button('Adjuntar',array('class'=>'wall-action-submit-btn','id'=>'btnAttachToNote',));?>
		<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancelNote',));?>
	</div>
</div>

<!-- *************** ALBUM ******************************* -->

<div id="wall-action-album"  class='wall-action-area-note' style="display:none">
	<div class="wall-action-area-album-dialog">
	</div>
	<?php 
		$modelAlbum = new Album;
		$this->renderPartial('_formAlbum',array('model'=>$modelAlbum));
	?>
	<div class="row" style="text-align: center;">
		<?php echo CHtml::button('Publicar',array('class'=>'wall-action-submit-btn','id'=>'btnPublicAlbum',));?>
		<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancelAlbum',));?>
	</div>
		
</div>