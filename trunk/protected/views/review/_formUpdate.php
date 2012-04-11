<?php
Yii::app()->clientScript->registerScript('UpdateReview', "

function RestoreButtons()
{
	$('#btn-box').children().removeClass('wall-action-btn-disable');
	$('#btn-box').children().removeClass('wall-action-btn-selected');
}

function SelectAButton(btnSelected)
{
	$('#btn-box').children().addClass('wall-action-btn-disable');
	$(btnSelected).removeClass('wall-action-btn-disable');
	$(btnSelected).addClass('wall-action-btn-selected');
}
function EnableButton(btnClicked)
{
	if($(btnClicked).hasClass('wall-action-btn-disable')||$(btnClicked).hasClass('wall-action-btn-selected'))
	{
		return false;
	}
	return true;
}


if('".$idNote."'!='')
	$('#wall-action-note').animate({opacity: 'show'},240);

$('#btnAlbum').hover(function(){
	if(!EnableButton($(this)))
	{
		return false;
	}
	$(this).addClass('wall-action-btn-hover');
},function(){
	$(this).removeClass('wall-action-btn-hover');
}
);

$('#btnNote').hover(function(){
	if(!EnableButton($(this)))
	{
		return false;
	}
	$(this).addClass('wall-action-btn-hover');
},function(){
	$(this).removeClass('wall-action-btn-hover');
}
);

$('#btnAlbum').click(function(){
		if(!EnableButton($(this)))
		{
			return false;
		}
		SelectAButton($(this));

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
	if(!EnableButton($(this)))
	{
		return false;
	}
	SelectAButton($(this));

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

$('#btnPublicNote').click(function(){
	$('#wall-action-note').animate({opacity: 'hide'},240,
		function(){		
			RestoreButtons();
			$('#Note_note').val('');
		}
	);
});

$('#btnPublicAlbum').click(function(){
	$('#wall-action-album').animate({opacity: 'hide'},240,
		function(){		
			RestoreButtons();
			$('#files').html('');
			$('#Album_description').val('');
			$('#Album_title').val('');
	});
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
			RestoreButtons();
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
				RestoreButtons();
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
<div class="wall-action-area" id="wall-action-area">
<div id="customer" class="review-action-back" >
	<?php echo CHtml::link($model->customer->name.' '.$model->customer->last_name,
		ReviewController::createUrl('index',array('Id_customer'=>$model->Id_customer)),
		array('class'=>'index-review-single-link')
		);
	 ?>
</div>

<div id="loading" class="loading-place-holder" >
</div>
<?php
	echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'btn-box'));
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnAlbum'));
			echo 'Album';
		echo CHtml::closeTag('div');	
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnNote'));
			echo 'Notas';
		echo CHtml::closeTag('div');	
		echo CHtml::openTag('div',array('class'=>'wall-action-btn div-hidden','id'=>'btnDoc'));
			echo 'Documentos';
		echo CHtml::closeTag('div');
	echo CHtml::closeTag('div');	
?> 
</div>
<!-- *************** NOTE ******************************* -->

<div id="wall-action-note"  class='wall-action-area-note' style="display:none">
	<div class="review-action-area-dialog" style="left: 310px;">
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
	<div class="review-action-area-dialog" style="left: 190px;">
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
<div class="review-update-data">

	<div class="review-update-data-info">
		<?php echo CHtml::label('Revis&oacute;n', 'Review_review');?>
		<?php echo CHtml::activeTextField($model,'review',array('class'=>'review-update-data-number')); ?>
	</div>
	<div class="review-update-data-info-descr">
		<?php echo CHtml::activeTextArea($model,'description',array('class'=>'review-update-data-text','rows'=>2, 'cols'=>70)); ?>
	</div>
</div>
	