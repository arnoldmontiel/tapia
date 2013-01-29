<?php
Yii::app()->clientScript->registerScript('indexWall', "

loadPage();
function loadPage()
{

	var id_customer =".$Id_customer."; 
	
		$('#Id_customer').val(id_customer);
		$.post('".ReviewController::createUrl('AjaxGetCustomerName')."', 
			{
				Id_customer: $('#Id_customer').val()
			}	
			).success(
			function(data){
				if(data != '')
					$('#linkCustomers').text(data);
				else
					$('#linkCustomers').text('Buscar');
				doFilter();
			});

}

$('#linkCustomers').click(function(){

	var id_customer =".$Id_customer.";
	if(id_customer == -1)
	{
		doFilter();
	}
});

$('#Id_customer').change(function(){
	
	$('#typeFilter').val('');
	$('#typeFilter').val(getCheck('chklist-type[]'));
	
	$('#reviewTypeFilter').val('');
	$('#reviewTypeFilter').val(getCheck('chklist-reviewType[]'));
	
	$('#tagFilter').val('');
	$('#tagFilter').val(getCheck('chklist-tag[]'));
	
	doFilter();
	
	return false;
});

setInterval(function() {
   doFilter();
}, 5000)

function doFilter()
{

		$('#btn-actions-box').removeClass('div-hidden');
		$('#loading').addClass('loading');
		$.post('".ReviewController::createUrl('AjaxFillInbox')."', 
		{
			tagFilter: $('#tagFilter').val(),
			Id_customer: $('#Id_customer').val(),
			typeFilter: $('#typeFilter').val(),
			reviewTypeFilter: $('#reviewTypeFilter').val(),
			dateFromFilter: $('#dateFromFilter').val(),
			dateToFilter: $('#dateToFilter').val(),
			customerNameFilter: $('#txtSearchCustomer').val()
			
		}	
		).success(
		function(data){
			$('#review-area').removeClass('div-hidden');
			$('#loading').removeClass('loading');
			$('#review-area').html(data);
			$('#review-area').animate({opacity: 'show'},240);
			$('#btn-create').attr('href','".ReviewController::createUrl('create')."'+'&Id_customer='+$('#Id_customer').val());
		});		

}


$('#btn-filter').click(function(){
	$('#typeFilter').val('');
	$('#typeFilter').val(getCheck('chklist-type[]'));
	
	$('#reviewTypeFilter').val('');
	$('#reviewTypeFilter').val(getCheck('chklist-reviewType[]'));
	
	$('#tagFilter').val('');
	$('#tagFilter').val(getCheck('chklist-tag[]'));
	
	$('#dateFromFilter').val('');
	$('#dateFromFilter').val($('#dateFrom').val());
	
	$('#dateToFilter').val('');
	$('#dateToFilter').val($('#dateTo').val());
	
	doFilter();
});

$('#btn-clear-filter').click(function(){
	$('#typeFilter').val('');
	
	$('#tagFilter').val('');
	
	$('#reviewTypeFilter').val('');
	
	$('input:checked').attr('checked', false);
	
	$('#dateFromFilter').val('');
	$('#dateFrom').val('')
	
	$('#dateToFilter').val('');
	$('#dateTo').val('')
	
	doFilter();
});

$('#btnDoc').click(function(){
	if(!EnableButton($(this)))
	{
		return false;
	}
	SelectAButton($(this));
	
	$('#wall-action-album').animate({opacity: 'hide'},240,function()
	{
		$('#wall-action-doc').animate({opacity: 'show'},240);
		$('#docType').val('3'); // PDF
		$('#arrow').removeClass('wall-action-area-images-dialog');
		$('#arrow').addClass('wall-action-area-docs-dialog');
	});

});

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

$('#btnPublicAlbum').click(function(){
	var url = '".ReviewController::createUrl('index&Id_customer='.$Id_customer)."';
	window.location = url;
	return false;
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
				idCustomer: ".$Id_customer."
			}
		).success(
		function(data){
			$('#loading').removeClass('loading');
			var param = '&idAlbum='+data+'&idCustomer='+".$Id_customer.";
		
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
	
		
			$('#wall-action-album').animate({opacity: 'show'},240);
			$('#files').html('');
			$('#Album_description').val('');
			$('#Album_title').val('');			
		
		}
		);
});

function getCheck(checkName)
{
	var data = { 'value[]' : []};

	$('input:checked').each(function() {
		if($(this).val() != '' && $(this).attr('name') == checkName)
 	 		data['value[]'].push($(this).val());
	});
	
	return data['value[]'];
}


");
?>

<div class="review-action-area" id="review-action-area">
<div id="loading" class="loading-place-holder" >
</div>
<?php echo CHtml::hiddenField('Id_customer',$Id_customer,array('id'=>'Id_customer'))?>
<?php if(User::getCustomer()):?>


<div id="customer" class="review-action-back" >
	<?php echo CHtml::link(User::getCustomer()->name.' '.User::getCustomer()->last_name,
		ReviewController::createUrl('index',array('Id_customer'=>User::getCustomer()->Id)),
		array('class'=>'index-review-single-link')
		);
	 ?>
</div>
<?php else:?>
<div id="customer" class="review-action-back" >
	<?php echo CHtml::link('Clientes',
		'',
		array('class'=>'index-review-single-link', 'id'=>'linkCustomers')
		);
	 ?>
	
	<?php 

	
	$this->widget('ext.processingDialog.processingDialog', array(
			'buttons'=>array('save'),
			'idDialog'=>'wating',
	));
	?>
</div>
<?php endif;?>
<?php if(User::canCreate() && isset($Id_customer) && $Id_customer > 0):?>

<?php
	echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'btn-box'));
		echo CHtml::openTag('div',array('class'=>'review-action-box-btn div-hidden','id'=>'btn-actions-box'));
			echo CHtml::link('Nuevo','',array('id'=>'btn-create','class'=>'submit-btn'));
		echo CHtml::closeTag('div');
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnAlbum'));
			echo 'Album';
		echo CHtml::closeTag('div');	
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnDoc'));
			echo 'Documentos';
		echo CHtml::closeTag('div');
	echo CHtml::closeTag('div');
?>
<?php endif;?>

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
		$this->renderPartial('_formDocument',array('model'=>$modelMulti, 'Id_review'=>null, 'Id_customer'=>$Id_customer));
	?>
</div>

<?php if(User::canCreate() && $Id_customer == -1):?>

<?php
	echo CHtml::openTag('div',array('class'=>'review-action-box-btn div-hidden','id'=>'btn-actions-box'));
		echo CHtml::textField('txtSearchCustomer','',array('Id'=>'txtSearchCustomer'));			
	echo CHtml::closeTag('div');	
?>
<?php endif;?>

</div>
<?php
	echo CHtml::hiddenField('tagFilter','',array('id'=>'tagFilter'));	
	echo CHtml::hiddenField('typeFilter','',array('id'=>'typeFilter'));
	echo CHtml::hiddenField('reviewTypeFilter','',array('id'=>'reviewTypeFilter'));
	echo CHtml::hiddenField('dateFromFilter','',array('id'=>'dateFromFilter'));
	echo CHtml::hiddenField('dateToFilter','',array('id'=>'dateToFilter'));
?>
<div id="review-area" class="index-review-area div-hidden" >
</div>

<?php if(isset($Id_customer) && $Id_customer > 0):?>
<div id="resources-view" class="review-single-view">
	<div class="review-resources-title">
		Recursos Multimedias
	</div>
<?php
		if($hasAlbum)
		{
			echo CHtml::openTag('div', array('style'=>'width:30%;display:inline-block'));
				echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
					echo CHtml::image('images/image_resource.png','',array('style'=>'width:25px;'));
				echo CHtml::closeTag('div');
				echo CHtml::link("Im&aacute;genes",
							ReviewController::createUrl('AjaxViewImageResource',array('Id_customer'=>$Id_customer))
							);
			echo CHtml::closeTag('div');			
		}
		
		if($hasDocs)
		{
			echo CHtml::openTag('div', array('style'=>'width:30%;display:inline-block'));
			echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
			echo CHtml::image('images/document_resource.png','',array('style'=>'width:25px;'));
			echo CHtml::closeTag('div');
			echo CHtml::link("Documentos Generales",
			ReviewController::createUrl('AjaxViewDocResource',array('Id_customer'=>$Id_customer))
			);
			echo CHtml::closeTag('div');
		}
		
		if($hasTechDocs)
		{
			echo CHtml::openTag('div', array('style'=>'width:30%;display:inline-block'));
			echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
			echo CHtml::image('images/tech_document_resource.png','',array('style'=>'width:25px;'));
			echo CHtml::closeTag('div');
			echo CHtml::link("Documentos T&eacute;cnicos",
			ReviewController::createUrl('AjaxViewTechDocResource',array('Id_customer'=>$Id_customer))
			);
			echo CHtml::closeTag('div');
		}		
?>
</div>
<?php endif;?>
