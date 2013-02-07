<?php
$browser = get_browser(null, true);

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-with-gallery.js',CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-exe.js',CClientScript::POS_HEAD);
$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide.css');
if($browser['browser']=='IE')
{
	$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.uploadify-3.1.js',CClientScript::POS_HEAD);
	$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/uploadify.css');
}

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
	$('#review-pending-view').children().each(
		function(index, item){
			bindEvents(item);
		}
	);
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
		$(this).addClass('review-action-add-note-focus');
	}
	);

	$(item).find('.check-last-doc').click(function()
	{	
		var url = $(this).attr('url');	
		$.post(
			'".ReviewController::createUrl('AjaxCheckLastDoc')."',
			{
				idCustomer: $(this).attr('idcustomer'),
			 	idMultimedia: $(this).attr('idmultimedia'),
				idDocType: $(this).attr('iddocType')				
			 }).success(
					function(data) 
					{ 
						
						if(data != '')
						{
							if(confirm('Existe una actualizaci\u00f3n de este documento, desea abrirla? \\nEn caso de cancelar, se abrir\u00e1 la version adjuntada a la nota'))
								url = data;
						}
						window.open(url,'_blank');
						return false;
						
					}
			);
	}
	);
	
	$(item).find('#main_title'+idMainNote).focus(function()
	{
		$(this).addClass('review-white');
		$(item).find('#edit_main_title_'+idMainNote).removeClass('div-hidden');
		$(item).find('#edit_main_title_cancel_'+idMainNote).removeClass('div-hidden');
	}
	);
	
	$(item).find('#main_note'+idMainNote).focus(function()
	{
		$(this).addClass('review-white');
		$(item).find('#edit_main_note_'+idMainNote).removeClass('div-hidden');
		$(item).find('#edit_main_note_cancel_'+idMainNote).removeClass('div-hidden');
	}
	);

	$(item).find('#edit_main_title_cancel_'+idMainNote).click(function(){
		$(item).find('#main_title'+idMainNote).removeClass('review-white');
		var title = $(item).find('#main_title'+idMainNote);
		$(item).find('#edit_main_title_'+idMainNote).addClass('div-hidden');
		$(this).addClass('div-hidden');
		$(title).val($(item).find('#main_original_title'+idMainNote).val());
	})
	
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
		$(item).find('#note_'+idMainNote).removeClass('review-action-add-note-focus');
	})

	$(item).find('#create_note_'+idMainNote).click(function(){
		var value = $(item).find('#note_'+idMainNote).val();
		var chk = 0;
		if($('#chkNoteNeedConf_'+idMainNote).is(':checked'))
			chk = 1;
		$.post(
			'".ReviewController::createUrl('AjaxAddNote')."',
			{
			 	id: idMainNote,
				value: $(item).find('#note_'+idMainNote).val(),
				idCustomer: ".$model->Id_customer.",
				chk: chk
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
			'".Yii::app()->createUrl('note/AjaxUpdateNoteDesc')."',
			{
			 	id: idMainNote,
				note: $(note).val()
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
	
	$(item).find('#edit_main_title_'+idMainNote).click(function(){
		var title = $(item).find('#main_title'+idMainNote);
		var value = $(title).val();
		$.post(
			'".Yii::app()->createUrl('note/AjaxUpdateNoteTitle')."',
			{
			 	id: idMainNote,
				title: $(title).val()
			 }).success(
					function(data) 
					{ 
						$(item).find('#main_title'+idMainNote).removeClass('review-white');
						$(item).find('#main_original_title'+idMainNote).val($(item).find('#main_title'+idMainNote).val());
						$(item).find('#edit_main_title_'+idMainNote).addClass('div-hidden');
						$(item).find('#edit_main_title_cancel_'+idMainNote).addClass('div-hidden');
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
									if(!confirm('\u00BFSeguro que quiere borrar esta nota?')) 
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
							if(!confirm('\u00BFSeguro que quiere borrar la nota entera?')) 
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
							if(!confirm('\u00BFEst\u00e1 de acuerdo en confirmar?')) 
								return false;
								},
				success : function(data)
				{
					$('#noteContainer_'+idMainNote).html(data);
					bindEvents($('#noteContainer_'+idMainNote))
				}
		});
	});

	$('#decline_note_'+idMainNote).click(function(){
		$.ajax({
				type : 'POST',
				data : 'id='+idMainNote,
				url : '" . ReviewController::createUrl('AjaxDeclineNote') ."',
				beforeSend : function(){
							if(!confirm('\u00BFEst\u00e1 de acuerdo en rechazar?')) 
								return false;
								},
				success : function(data)
				{
					$('#noteContainer_'+idMainNote).html(data);
					bindEvents($('#noteContainer_'+idMainNote))
				}
		});
	});
	function CheckPermission(parent,caller,check)
	{
		$(parent).find(check).attr('checked',true);	
		$(parent).find('#chkUserGroup').attr('checked',true);	
		$(parent).find('#divChkUserGroup').addClass('review-permission-chk-decoration-chk');
		$(caller).addClass('review-permission-chk-decoration-chk');
	}
	function UncheckPermission(parent,caller,check)
	{
		$(parent).find(check).attr('checked',false);	
		$(caller).removeClass('review-permission-chk-decoration-chk');
	}
	function SavePermissionsChanges(type,value,idUserGroup,idNote,parent,caller,check)
	{
		//alert('Grabando '+type+' '+value+' '+idUserGroup);
		$.ajax({
				type : 'POST',
				data : {type:type,value:value,idUserGroup:idUserGroup,idNote:idNote,idCustomer: ".$model->Id_customer."},
				url : '" . ReviewController::createUrl('AjaxSavePermissions') ."',
				beforeSend : function(){
								},
				success : function(data)
				{
					if(data=='ok')
					{
						if(type=='canSee')
						{
							if(value==false)
							{
								$(parent).find(':checkbox').attr('checked',false);
								$(caller).removeClass('review-permission-chk-decoration-chk');
								$(parent).find('#divChkAddressed').removeClass('review-permission-chk-decoration-chk');
								$(parent).find('#divChkCanFeedback').removeClass('review-permission-chk-decoration-chk');
								$(parent).find('#divChkNeedConfirmation').removeClass('review-permission-chk-decoration-chk');
							}else
							{
								$(parent).find('#chkUserGroup').attr('checked',true);
								$(caller).addClass('review-permission-chk-decoration-chk');
							}
						}
						else
						{
							if(value==true)
							{
								CheckPermission(parent,caller,check);
							}
							else
							{
								UncheckPermission(parent,caller,check);
							}
						}
					}
				}
		});

	}

	$('#publicArea_'+idMainNote).children().each(function(){
		var parent = $(this);
		var editPermisssions = false;
		if($('#publicArea_'+idMainNote).attr('name')=='edit-permissions')
		{
			editPermisssions = true;
		}
		$(this).find('#divChkUserGroup').click(function(){
			
			if($(this).attr('isadmin') == 'no')
			{
				if(!$(parent).find('#chkUserGroup').is(':checked'))
				{
					if(editPermisssions)
					{				
						SavePermissionsChanges('canSee',true,$(parent).find('#chkUserGroup').attr('value'),idMainNote,parent,this);
					}
					else
					{	
						$(parent).find('#chkUserGroup').attr('checked',true);
						$(this).addClass('review-permission-chk-decoration-chk');
					}
						
				}
				else
				{
					if(editPermisssions)
					{
						SavePermissionsChanges('canSee',false,$(parent).find('#chkUserGroup').attr('value'),idMainNote,parent,this);
					}
					else
					{
						$(parent).find(':checkbox').attr('checked',false);
						$(this).removeClass('review-permission-chk-decoration-chk');
						$(parent).find('#divChkAddressed').removeClass('review-permission-chk-decoration-chk');
						$(parent).find('#divChkCanFeedback').removeClass('review-permission-chk-decoration-chk');
						$(parent).find('#divChkNeedConfirmation').removeClass('review-permission-chk-decoration-chk');
					}
				}	
			}
		});
		
		$(this).find('#divChkAddressed').click(function(){
			if(!$(parent).find('#chkAddressed').is(':checked'))
			{
				if(editPermisssions)
				{
					SavePermissionsChanges('addressed',true,$(parent).find('#chkUserGroup').attr('value'),idMainNote,parent,this,'#chkAddressed');
				}
				else
				{
					CheckPermission(parent,this,'#chkAddressed');
				}
			}
			else
			{
				if(editPermisssions)
				{
					SavePermissionsChanges('addressed',false,$(parent).find('#chkUserGroup').attr('value'),idMainNote,parent,this,'#chkAddressed');
				}
				else
				{
					UncheckPermission(parent,this,'#chkAddressed');
				}
			}
		});
		
		$(this).find('#divChkCanFeedback').click(function(){
			if($(this).attr('isadmin') == 'no')
			{
				if(!$(parent).find('#chkCanFeedback').is(':checked'))
				{
					if(editPermisssions)
					{
						SavePermissionsChanges('canFeedback',true,$(parent).find('#chkUserGroup').attr('value'),idMainNote,parent,this,'#chkCanFeedback');
					}
					else
					{
						CheckPermission(parent,this,'#chkCanFeedback');
					}
				}
				else
				{
					if(editPermisssions)
					{
						SavePermissionsChanges('canFeedback',false,$(parent).find('#chkUserGroup').attr('value'),idMainNote,parent,this,'#chkCanFeedback');
					}
					else
					{
						UncheckPermission(parent,this,'#chkCanFeedback');
					}
				}
			}
		});
		
		$(this).find('#divChkNeedConfirmation').click(function(){
			if(!$(parent).find('#chkNeedConfirmation').is(':checked'))
			{
				if(editPermisssions)
				{
					SavePermissionsChanges('needConfirmation',true,$(parent).find('#chkUserGroup').attr('value'),idMainNote,parent,this,'#chkNeedConfirmation');
				}
				else
				{
					CheckPermission(parent,this,'#chkNeedConfirmation');
				}
			}
			else
			{
				if(editPermisssions)
				{
					SavePermissionsChanges('needConfirmation',false,$(parent).find('#chkUserGroup').attr('value'),idMainNote,parent,this,'#chkNeedConfirmation');
				}
				else
				{
					UncheckPermission(parent,this,'#chkNeedConfirmation');
				}
			}
		});
				
	});
	
	$('#public_'+idMainNote).click(function(){
		var dataUserGroup = { 'value[]' : []};
		var dataFeedback = { 'value[]' : []};
		var dataAddressed = { 'value[]' : []};
		var dataNeedConf = { 'value[]' : []};
		
		$('#publicArea_'+idMainNote).children().each(function(){
			var chkGroup = $(this).find('#chkUserGroup');
			if($(chkGroup).is(':checked'))
				dataUserGroup['value[]'].push($(chkGroup).val());
				
			var chkFeedback = $(this).find('#chkCanFeedback');
			if($(chkFeedback).is(':checked'))
				dataFeedback['value[]'].push($(chkFeedback).val());
				
			var chkAddress = $(this).find('#chkAddressed');
			if($(chkAddress).is(':checked'))
				dataAddressed['value[]'].push($(chkAddress).val());
			
			var chkNeedConf = $(this).find('#chkNeedConfirmation');
			if($(chkNeedConf).is(':checked'))
				dataNeedConf['value[]'].push($(chkNeedConf).val());
		});
		
			$('#dialogProcessing').dialog('open');		
			$.post('".ReviewController::createUrl('AjaxPublicNote')."', 
			{
				idNote: idMainNote,
				idCustomer: ".$model->Id_customer.",
				userGroup: dataUserGroup['value[]'],
				canFeedback: dataFeedback['value[]'],
				addressed: dataAddressed['value[]'],
				needConf: dataNeedConf['value[]']
			}
			).success(
			function(data){
				window.location = '".ReviewController::createUrl('update',array('id'=>$model->Id))."';
				$('#dialogProcessing').dialog('close');		
				return false;
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
		var url = '".AlbumController::createUrl('album/AjaxCreateAlbum')."';

		if('".$browser['browser']."'=='IE')
		{
			url = '".AlbumController::createUrl('album/AjaxCreateAlbumIE')."';
		}
		$.post(url, 
			{
				idCustomer: ".$model->Id_customer.",
				idReview: ".$model->Id."
			}
		).success(
		function(data){
			$('#loading').removeClass('loading');
			var param = '&idAlbum='+data+'&idCustomer='+".$model->Id_customer.";
		
			$('#XUploadWidget_form').attr('action','".AlbumController::createUrl('album/AjaxUpload')."'+param);
			$('#Album_Id_album').val(data);
			$('#uploader').html(data);
			if('".$browser['browser']."'=='IE')
			{
				$('#file_upload').uploadify({
			        'swf'      : '".Yii::app()->request->baseUrl."/js/uploadify.swf',
			        'uploader' : '".AlbumController::createUrl('album/AjaxUploadify')."&idAlbum='+$('#uploadify_id_album').val()+'&idCustomer='+$('#uploadify_id_customer').val(),
			        // Put your options here
			        'buttonText' : 'Seleccione',
			        'onUploadSuccess' : function(file, data, response) {
	         		   //alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
						var target = $('.album-view-image:first');
						$(target).before(data);
						target = $('.album-view-image:first');
						$(target).animate({opacity: 'show'},400);
						$(target).find('#photo_description').change(function(){
							$.get('".AlbumController::createUrl('album/AjaxAddImageDescription')."',
 							{
								IdMultimedia:$(target).attr('id'),
								description:$(this).val()
 							}).success(
 								function(data) 
 								{
								}
							);                         		
						});
						$(target).find('#photo_cancel').click(function(){
								
							$.get('".AlbumController::createUrl('album/AjaxRemoveImage')."',
 							{
								IdMultimedia:$(target).attr('id')
							}).success(
								function(data) 
								{
									$(target).remove();	
								}
							);
						});
			        }
				});
			}
	
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

$('#btnSaveNote').click(function(){
	$('#loading').addClass('loading');
	$('#dialogProcessing').dialog('open');
		
	var id = $('#Note_Id_note').val()
	$.post('".ReviewController::createUrl('AjaxSaveNote')."', 
		{
			id: id
		}
	).success(
	function(data){
		$('.review-container-single-view:first').before(data);
		$('#dialogProcessing').dialog('close');
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
				$('#uploaded').html(data);
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

$('#btnAttachTechDocToNote').click(function(){
	
	var url = '".ReviewController::createUrl('AjaxAttachTechDoc',array('id'=>$model->Id))."';
	window.location = url + '&idNote='+$('#Note_Id_note').val();
	return false;
});

$('#btnClose').click(function(){
	
	if(confirm('Si cierra el tema, todas las notas con confirmaciones pendientes se autoconfirmar\u00e1n'))
		jQuery('#ClosingReviewDialog').dialog('open'); 
		
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

	
	$('#Review_Id_review_type').change(function(){
		if($(this).val()!= ''){
			$.post('".ReviewController::createUrl('AjaxSetReviewType')."', 
			{
				id: ".$model->Id.",
				idReviewType: $(this).val()	
			}	
			).success(
			function(data){
			
			});		
		}
	});
	
	$('#info_order').change(function(){
		if($(this).val()!= ''){
			var url = '".ReviewController::createUrl('update',array('id'=>$model->Id))."';
			window.location = url + '&order='+$(this).val();
			return false;
		}
	});
	
setInterval(function() {

	$.post('".ReviewController::createUrl('AjaxCheckUpdate')."', 
			{
				id: ".$model->Id."	
			}	
			).success(
			function(data){
				if(data == 0){
					$('#need_reload').animate({opacity: 'show'},240);
					$('#notification').animate({opacity: 'show'},240);
					if('".$browser['browser']."'=='IE')
					{
						$('#notification').removeClass('div-hidden');
					}		
				}			
	});
}, 20000)
$('#notification').click(function(){
		
	var url = '".ReviewController::createUrl('update',array('id'=>$model->Id))."';
	window.location = url + '&order='+$('#info_order').val();
	return false;
		
	});
		
$('#need_reload').click(function(){
		
	var url = '".ReviewController::createUrl('update',array('id'=>$model->Id))."';
	window.location = url + '&order='+$('#info_order').val();
	return false;
		
	});
");
?>
<div id="notification" class="review-update-notification div-hidden">
	Hay novedades, click para actualizar
</div>
<div class="review-update-data">

	<div class="review-update-data-info">
		<?php 
			if(User::canCreate() && $model->username == User::getCurrentUser()->username)
			{
				echo CHtml::activeTextField($model,'review',array('class'=>'review-update-data-number'));
				echo CHtml::encode(' - ');				
			}
			else
			{
				echo CHtml::openTag('div',array('class'=>'review-update-data-info-descr-number'));				
				echo CHtml::encode($model->review.' -');				
				echo CHtml::closeTag('div');				
			} 
		?>
	</div>
	<div class="review-update-data-info-descr">
		<?php 
			if(User::canCreate() && $model->username == User::getCurrentUser()->username) 
				echo CHtml::activeTextArea($model,'description',array('class'=>'review-update-data-text','rows'=>2, 'cols'=>70)); 
			else
			{
				echo CHtml::openTag('div',array('class'=>'review-update-data-info-descr-text'));				
				echo CHtml::encode($model->description);				
				echo CHtml::closeTag('div');				
			} 
			echo CHtml::image('images/reload.png','',array('class'=>'review-need-update', 'id'=>'need_reload','title'=>'Recargar'));
		?>
	</div>
	<div class="review-close-review">
		<?php
			if(User::canCreate() && $model->username == User::getCurrentUser()->username)
			{
				echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnClose'));
					echo 'Cerrar';
				echo CHtml::closeTag('div');
			}
		?>
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

<?php if(User::canCreate() && $model->username == User::getCurrentUser()->username):?>
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
// 		if($model->username == User::getCurrentUser()->username )
// 		{
// 			echo CHtml::openTag('div',array('class'=>'review-type'));
// 				$reviewTypes = CHtml::listData($ddlReviewType, 'Id', 'description');
// 				echo CHtml::label('Tipo: ','Id_review_type');
// 				echo CHtml::activeDropDownList($model, 'Id_review_type', $reviewTypes);
// 			echo CHtml::closeTag('div');
// 		}
// 		else
// 		{
			echo CHtml::openTag('div',array('class'=>'review-type'));
				echo CHtml::openTag('div',array('class'=>'review-attr-level'));		
					echo CHtml::label('Tipo: ','Id_review_type');
				echo CHtml::closeTag('div');
				echo CHtml::openTag('div',array('class'=>'review-attr-text'));		
					echo CHtml::encode($model->reviewType->description);
				echo CHtml::closeTag('div');
			echo CHtml::closeTag('div');
// 		}
// 		echo CHtml::openTag('div',array('class'=>'order-info'));
// 			echo CHtml::label('Orden: ','info_order');
// 			$orderData = array('addressed'=>'Para','can_feedback'=>'Respuesta','need_confirmation'=>'Confirmaci'.utf8_encode('ó').'n');
// 			echo CHtml::dropDownList('info_order', ($order)?$order:'addressed', $orderData);
// 		echo CHtml::closeTag('div');
		
	echo CHtml::closeTag('div');	
?> 
<?php else:?>
<div id="loading" class="loading-place-holder" >
</div>
<?php
echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'btn-box'));

	echo CHtml::openTag('div',array('class'=>'review-type'));
		echo CHtml::openTag('div',array('class'=>'review-attr-level'));		
			echo CHtml::label('Tipo: ','Id_review_type');
		echo CHtml::closeTag('div');
		echo CHtml::openTag('div',array('class'=>'review-attr-text'));		
			echo CHtml::encode($model->reviewType->description);
		echo CHtml::closeTag('div');
	echo CHtml::closeTag('div');
// 	echo CHtml::openTag('div',array('class'=>'order-info'));
// 		echo CHtml::label('Orden: ','info_order');
// 		$orderData = array('addressed'=>'Para','can_feedback'=>'Respuesta','need_confirmation'=>'Confirmaci'.utf8_encode('ó').'n');
// 		echo CHtml::dropDownList('info_order', ($order)?$order:'addressed', $orderData);
// 	echo CHtml::closeTag('div');
echo CHtml::closeTag('div');
?>
<?php endif;?>
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
		<?php echo CHtml::button('Guardar',array('class'=>'wall-action-submit-btn','id'=>'btnSaveNote'));?>
		<?php echo CHtml::button('Adjuntar Imagen',array('class'=>'wall-action-submit-btn','id'=>'btnAttachImgToNote', 'style'=>'width:150px'));?>
		<?php echo CHtml::button('Adjuntar Docs',array('class'=>'wall-action-submit-btn','id'=>'btnAttachDocToNote', 'style'=>'width:150px'));?>
		<?php 
			if(User::useTechnicalDocs())
				echo CHtml::button('Adjuntar Tec Docs',array('class'=>'wall-action-submit-btn','id'=>'btnAttachTechDocToNote', 'style'=>'width:150px'));
		?>
		<?php echo CHtml::button('Cancelar',array('class'=>'wall-action-submit-btn','id'=>'btnCancelNote'));?>
	</div>
</div>

<!-- *************** ALBUM ******************************* -->

<div id="wall-action-album"  class='wall-action-area-note' style="display:none">
	<div class="review-action-area-dialog" style="left: 190px;">
	</div>
	<?php 
		$modeNewlAlbum = new Album;
		$browser = get_browser(null, true);
		if($browser['browser']=='IE')
		{
			$this->renderPartial('_formAlbumIE',array('model'=>$modeNewlAlbum));				
		}
		else 
		{
			$this->renderPartial('_formAlbum',array('model'=>$modeNewlAlbum));				
		}
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

<div id="review-pending-view">
	<?php 
		$modelNote = new Note;
		$modelNote->Id_review = $model->Id;
		$modelNote->Id_user_group_owner = User::getCurrentUserGroup()->Id;
		$dataProviderNote = $modelNote->search();
		$dataProviderNote->criteria->order= 'change_date DESC';
		$noteData = $dataProviderNote->data;
		echo CHtml::openTag('div',array('class'=>'review-container-single-view','style'=>'display:none;','id'=>'noteContainer_place_holder'));
		echo CHtml::closeTag('div');
		foreach ($noteData as $item) {
			echo CHtml::openTag('div',array('class'=>'review-container-single-view','id'=>'noteContainer_'.$item->Id));
			$this->renderPartial('_viewPendingData',array('data'=>$item));
			echo CHtml::closeTag('div');
		}
	?>
</div>
	
<div id="review-view">
	<?php 
		$modelUserGroupNote = new UserGroupNote();
		$modelUserGroupNote->Id_review = $model->Id;
		$modelUserGroupNote->Id_user_group = User::getCurrentUserGroup()->Id;
		$dataProviderUserGroupNote = $modelUserGroupNote->search();
		$infOrder = 'note.change_date DESC';
		if(isset($order))
			$infOrder = "t.". $order . " DESC , " . $infOrder;
		else
			$infOrder = "t.addressed DESC , " . $infOrder;
		
		$dataProviderUserGroupNote->criteria->order= $infOrder;
		
		$noteData = $dataProviderUserGroupNote->data;
		echo CHtml::openTag('div',array('class'=>'review-container-single-view','style'=>'display:none;','id'=>'noteContainer_place_holder'));
		echo CHtml::closeTag('div');
		foreach ($noteData as $item) {
			echo CHtml::openTag('div',array('class'=>'review-container-single-view','id'=>'noteContainer_'.$item->note->Id));
			$this->renderPartial('_viewData',array('data'=>$item->note,'dataUserGroupNote'=>$item));
			echo CHtml::closeTag('div');
		}
	?>
</div>

<?php
$this->widget('ext.processingDialog.processingDialog', array(
		'idDialog'=>'dialogProcessing',
));

//New Budget Version
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
			'id'=>'ClosingReviewDialog',
// additional javascript options for the dialog plugin
			'options'=>array(
					'title'=>'Cierre de Tema',
					'autoOpen'=>false,
					'modal'=>true,
					'width'=> '500',
					'buttons'=>	array(
							'Cancelar'=>'js:function(){
										jQuery("#ClosingReviewDialog").dialog( "close" );
										$("#Review_closing_description").val(null);
							}',
							'Guardar'=>'js:function()
							{
							jQuery("#wating").dialog("open");			
										
							jQuery.post("'.Yii::app()->createUrl("review/AjaxCloseReview").'", 
								{
									id: "'. $model->Id . '",									
									closingDescription: $("#Review_closing_description").val(),
								},
							function(data) {
								jQuery("#ClosingReviewDialog").dialog( "close" );
								window.location = "'.ReviewController::createUrl('index',array('Id_customer'=>$model->Id_customer)) .'";
							}
					);
	
			}'),
),
));

echo $this->renderPartial('_closeReview', array('id'=>8, 'version'=>9));

$this->endWidget('zii.widgets.jui.CJuiDialog');
?>