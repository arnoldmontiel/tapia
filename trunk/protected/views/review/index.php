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
			customerNameFilter: $('#txtSearchCustomer').val(),
			
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
	echo CHtml::openTag('div',array('class'=>'review-action-box-btn div-hidden','id'=>'btn-actions-box'));	
		echo CHtml::link('Nuevo','',array('id'=>'btn-create','class'=>'submit-btn'));
	echo CHtml::closeTag('div');	
?>
<?php endif;?>

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
