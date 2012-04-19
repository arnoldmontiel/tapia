<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-with-gallery.js',CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-exe.js',CClientScript::POS_HEAD);
$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide.css');

Yii::app()->clientScript->registerScript(__CLASS__.'#review_update'.$model->Id, "

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


if('".$idNote."'!=''){
	$('#wall-action-note').animate({opacity: 'show'},240);
	SelectAButton($('#btnNote'));
}

beginBind();

function beginBind()
{
	$('#review-view').children().each(
		function(index, item){
			bindEvents(item);
		}
	);
}


function bindEvents(item)
{
	var idMainNote = $(item).attr('id').split('_')[1];
	$(item).find('#note_'+idMainNote).autoResize();

	$(item).find('#note_'+idMainNote).focus(function()
	{
		$(item).find('#create_note_'+idMainNote).removeClass('div-hidden');
		$(item).find('#create_note_cancel_'+idMainNote).removeClass('div-hidden');
	}
	);

	$(item).find('#main_note'+idMainNote).focus(function()
	{
		$(this).addClass('review-white');
		$(item).find('#edit_main_note_'+idMainNote).removeClass('div-hidden');
		$(item).find('#edit_main_note_cancel_'+idMainNote).removeClass('div-hidden');
	}
	);
		
	$(item).find('#edit_main_note_cancel_'+idMainNote).click(function(){
		$(item).find('#main_note'+idMainNote).removeClass('review-white');
		var note = $(item).find('#main_note'+idMainNote);
		$(item).find('#edit_main_note_'+idMainNote).addClass('div-hidden');
		$(this).addClass('div-hidden');
		$(note).val($(item).find('#main_original_note'+idMainNote).val());
	})

	$(item).find('#create_note_cancel_'+idMainNote).click(function(){
		var note = $(item).find('#note_'+idMainNote);
		$(note).height(55);
		$(item).find('#create_note_'+idMainNote).addClass('div-hidden');
		$(this).addClass('div-hidden');
		$(note).val('');
	})

	$(item).find('#create_note_'+idMainNote).click(function(){
		var value = $(item).find('#note_'+idMainNote).val();
		$.post(
			'".ReviewController::createUrl('AjaxAddNote')."',
			{
			 	id: idMainNote,
				value: $(item).find('#note_'+idMainNote).val(),
				idCustomer: ".$model->Id_customer."
			 }).success(
					function(data) 
					{ 
						$('#noteContainer_'+idMainNote).html(data);
						bindEvents($('#noteContainer_'+idMainNote));
					}
			);
	});
	    
	$(item).find('#edit_main_note_'+idMainNote).click(function(){
		var note = $(item).find('#main_note'+idMainNote);
		var value = $(note).val();
		$.post(
			'".NoteController::createUrl('note/AjaxUpdateNoteDesc')."',
			{
			 	id: idMainNote,
				note: $(note).val(),
			 }).success(
					function(data) 
					{ 
						$(item).find('#main_note'+idMainNote).removeClass('review-white');
						$(item).find('#main_original_note'+idMainNote).val($(item).find('#main_note'+idMainNote).val());
						$(item).find('#edit_main_note_'+idMainNote).addClass('div-hidden');
						$(item).find('#edit_main_note_cancel_'+idMainNote).addClass('div-hidden');
					}
			);
 
	});
	
	$(item).find('#singleNoteContainer').find('img').each(
		function(i, imgItem){
			$(imgItem).click(function(){
				var id = $(imgItem).attr('id');								
				var idNote = id.split('_')[2];
				var idParent = id.split('_')[3];
				
				var getParam = '&id='+idNote+'&idParent='+idParent;
												
				$.ajax({
						type : 'GET',
						url : '" . ReviewController::createUrl('AjaxRemoveSingleNote') ."' + getParam,
						beforeSend : function(){
									if(!confirm('Seguro que quiere borrar esta nota?')) 
										return false;
										},
						success : function(data)
						{
							$('#noteContainer_'+idParent).html(data);
							bindEvents($('#noteContainer_'+idParent))
						}
				});
			});
	});
	
	$(item).find('#delete_'+idMainNote).click(function(){
		$.ajax({
				type : 'POST',
				data : 'id='+idMainNote,
				url : '" . NoteController::createUrl('note/AjaxDelete') ."',
				beforeSend : function(){
							if(!confirm('Seguro que quiere borrar la nota entera?')) 
								return false;
								},
				success : function(data)
				{
					$('#noteContainer_'+idMainNote).html(data);
					bindEvents($('#noteContainer_'+idMainNote))
				}
		});
	});
	
	$('#edit_image'+idMainNote).hover(
		function(){
			$(this).removeClass('div-hidden');
	});

	$('#review_image'+idMainNote).hover(
		function(){
			$('#edit_image'+idMainNote).removeClass('div-hidden');
		},
		function(){
			$('#edit_image'+idMainNote).addClass('div-hidden');
	});
	
	$('#chkNeedConf_'+idMainNote).change(function(){
		
		var chk = 0;
		if($(this).is(':checked'))
			chk = 1;
			
		$.post(
		'".ReviewController::createUrl('AjaxUpdateNoteNeedConf')."',
		{
			id: idMainNote,
			chk: chk
		}).success(
			function(data) 
			{ 
				$('#noteContainer_'+idMainNote).html(data);
				bindEvents($('#noteContainer_'+idMainNote))
			});
	});
	
	$('#confirm_note_'+idMainNote).click(function(){
		$.ajax({
				type : 'POST',
				data : 'id='+idMainNote,
				url : '" . ReviewController::createUrl('AjaxConfirmNote') ."',
				beforeSend : function(){
							if(!confirm('Esta de acuerdo en confirmar?')) 
								return false;
								},
				success : function(data)
				{
					$('#noteContainer_'+idMainNote).html(data);
					bindEvents($('#noteContainer_'+idMainNote))
				}
		});
	});

}

$('#btnAlbum').hover(function(){
	if(!EnableButton($(this)))
	{
		return false;
	}
	$(this).addClass('wall-action-btn-hover');
},

function(){
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

$('#btnDoc').hover(function(){
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
	
	});
	
});

$('#btnPublicNote').click(function(){
	$('#loading').addClass('loading');
	var id = $('#Note_Id_note').val()
	$.post('".NoteController::createUrl('AjaxPublicNote')."', 
		{
			id: id
		}
	).success(
	function(data){
		$('.review-container-single-view:first').before(data);
		$('#loading').removeClass('loading');
		$('#wall-action-note').animate({opacity: 'hide'},240,
		function(){		
			RestoreButtons();
			$('#Note_note').val('');
			
		});
		bindEvents($('#noteContainer_'+id));
	
	});	

});

$('#btnPublicAlbum').click(function(){

	var url = '".ReviewController::createUrl('update',array('id'=>$model->Id))."';
	window.location = url;
	return false;
	
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
			$('#note-images').html('');
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
			review:$(this).val()
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
			description:$(this).val()
		}).success(
			function() 
			{ 

			});
});
	
$('#btnAttachImgToNote').click(function(){
	
	var url = '".ReviewController::createUrl('AjaxAttachImage',array('id'=>$model->Id))."';
	window.location = url + '&idNote='+$('#Note_Id_note').val();
	return false;
});

$('#btnAttachDocToNote').click(function(){
	
	var url = '".ReviewController::createUrl('AjaxAttachDoc',array('id'=>$model->Id))."';
	window.location = url + '&idNote='+$('#Note_Id_note').val();
	return false;
});

$('#btnDoc').click(function(){
	if(!EnableButton($(this)))
	{
		return false;
	}
	SelectAButton($(this));

	$('#Note_note').val('');
	$('#wall-action-note').animate({opacity: 'hide'},240,function()
	{
		$('#wall-action-album').animate({opacity: 'hide'},240,function()
		{
			$('#wall-action-doc').animate({opacity: 'show'},240);
			$('#docType').val('3'); // PDF
			$('#arrow').removeClass('wall-action-area-images-dialog');
			$('#arrow').addClass('wall-action-area-docs-dialog');
		});
	});
});

$(':checkbox').click(function() {
		if($(this).val() != '' && $(this).attr('name') == 'chklist-tag-review[]')
 	 	{
 	 		if($(this).is(':checked'))
 	 		{
 	 			$.post(
					'".ReviewController::createUrl('AjaxAddTag')."',
					{
						id: ".$model->Id.",
						idTag:$(this).val()
					}).success(
						function() 
						{ 
			
					});
 	 		}
 	 		else
 	 		{
 	 			$.post(
					'".ReviewController::createUrl('AjaxRemoveTag')."',
					{
						id: ".$model->Id.",
						idTag:$(this).val()
					}).success(
						function() 
						{ 
			
					});
 	 		}

 	 	}
	});

	$('#Review_Id_priority').change(function(){
		if($(this).val()!= ''){
			$.post('".ReviewController::createUrl('AjaxSetPriority')."', 
			{
				id: ".$model->Id.",
				idPriority: $(this).val()	
			}	
			).success(
			function(data){
			
			});		
		}
	});
	
");
?>
<div class="review-update-data">

	<div class="review-update-data-info">
		<?php echo CHtml::label('Revis&oacute;n', 'Review_review');?>
		<?php echo CHtml::activeTextField($model,'review',array('class'=>'review-update-data-number')); ?>
	</div>
	<div class="review-update-data-info-descr">
		<?php echo CHtml::activeTextArea($model,'description',array('class'=>'review-update-data-text','rows'=>2, 'cols'=>70)); ?>
	</div>
</div>

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
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnDoc'));
			echo 'Documentos';
		echo CHtml::closeTag('div');
		echo CHtml::openTag('div',array('class'=>'review-priority'));
			$prioritys = CHtml::listData($ddlPriority, 'Id', 'description');
			echo CHtml::label('Prioridad: ','Id_priority'); 
			echo CHtml::activeDropDownList($model, 'Id_priority', $prioritys);
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
		<?php echo CHtml::button('Publicar',array('class'=>'wall-action-submit-btn','id'=>'btnPublicNote'));?>
		<?php echo CHtml::button('Adjuntar Imagen',array('class'=>'wall-action-submit-btn','id'=>'btnAttachImgToNote', 'style'=>'width:150px'));?>
		<?php echo CHtml::button('Adjuntar Docs',array('class'=>'wall-action-submit-btn','id'=>'btnAttachDocToNote', 'style'=>'width:150px'));?>
		<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancelNote'));?>
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
		<?php echo CHtml::button('Publicar',array('class'=>'wall-action-submit-btn','id'=>'btnPublicAlbum'));?>
		<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancelAlbum'));?>
	</div>
		
</div>

<!-- *************** DOCUMENT ******************************* -->

<div id="wall-action-doc"  class='wall-action-area-note' style="display:none">
	<div class="review-action-area-dialog" style="left: 430px;">
	</div>
	<?php 
		$modelMulti = new Multimedia;
		$this->renderPartial('_formDocument',array('model'=>$modelMulti, 'Id_review'=>$model->Id, 'Id_customer'=>$model->Id_customer));
	?>
</div>
	
<div id="review-view">
	<?php 
		$modelNote = new Note;
		$modelNote->Id_review = $model->Id;
		$dataProviderNote = $modelNote->search();
		$dataProviderNote->criteria->order= 'creation_date DESC';
		$noteData = $dataProviderNote->data;
		echo CHtml::openTag('div',array('class'=>'review-container-single-view','style'=>'display:none;','id'=>'noteContainer_place_holder'));
		echo CHtml::closeTag('div');
		foreach ($noteData as $item) {
			echo CHtml::openTag('div',array('class'=>'review-container-single-view','id'=>'noteContainer_'.$item->Id));
			$this->renderPartial('_viewData',array('data'=>$item));
			echo CHtml::closeTag('div');
		}
	?>
</div>
<div id="resources-view" class="review-single-view">
	<div class="review-resources-title">
	Recursos Multimedias
	</div>
<?php
	
		echo CHtml::openTag('div',array('class'=>'review-container-album'));
		foreach($model->albums as $item)
		{
				
			echo CHtml::openTag('div',array('class'=>'review-container-single-album'));	
			echo CHtml::openTag('div',array('id'=>'edit_image'.$data->Id,'class'=>"review-edit-image review-edit-image-album"));
						
			echo CHtml::link('Editar Album',
			ReviewController::createUrl('updateAlbum',array('id'=>$item->Id)),
			array('class'=>'review-edit-image')
			);
			echo CHtml::closeTag('div');
			$images = array();
			$height=0;
			foreach($item->multimedias as $multi_item)
			{
				$image= array();
				$image['image'] = "images/".$multi_item->file_name;
				$image['small_image'] = "images/".$multi_item->file_name_small;
				$image['caption'] = $multi_item->description;
				if($multi_item->height_small>$height)
				{
					$height = $multi_item->height_small;
				}
				$images[]=$image;
			}
			$this->widget('ext.highslide.highslide', array(
											'images'=>$images,
											'Id'=>$item->Id,
											'height'=>$height,
			));
						
			echo CHtml::openTag('div',array('style'=>'margin-top:10px;font-weight:bold;'));
			echo $item->title;	
			echo CHtml::closeTag('div');
			echo CHtml::openTag('p',array('class'=>'single-formated-text'));
			echo $item->description;	
			echo CHtml::closeTag('p');
			echo CHtml::closeTag('div');
		}
		echo CHtml::closeTag('div');

		echo CHtml::openTag('div',array('class'=>'review-container-documents'));	
		?>
		<div class="review-text-docs">
		<?php 
			$multimedias = $model->multimedias;
			if(sizeof($multimedias)>0)
			{
				echo CHtml::link('Editar documentos',
				ReviewController::createUrl('updateDocuments',array('id'=>$model->Id)),array('class'=>'review-text-docs'));
			}
			foreach($multimedias as $item)
			{
				if($item->Id_multimedia_type!=3
					&&$item->Id_multimedia_type!=4) continue;
				echo CHtml::openTag('div');
				echo CHtml::link(
					CHtml::encode($item->file_name),
					Yii::app()->baseUrl.'/docs/'.$item->file_name,
					array('target'=>'_blank','class'=>'review-text-docs')
				);
				echo CHtml::encode(' '.round(($item->size / 1024), 2));
				echo CHtml::encode(' (Kb) ');
				
				echo CHtml::openTag('div',array('class'=>'review-area-single-files-description'));
				echo CHtml::encode($item->description);
				echo CHtml::closeTag('div');
				
				echo CHtml::closeTag('div');
					
			}
			echo CHtml::closeTag('div');
		?>
	</div>
		
</div>