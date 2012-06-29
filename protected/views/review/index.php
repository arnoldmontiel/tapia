<?php
Yii::app()->clientScript->registerScript('indexWall', "

loadPage();
function loadPage()
{
	var id_customer =".$Id_customer."; 
	if(id_customer!=-1)
	{
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
					$('#linkCustomers').text('Clientes');
				doFilter();
			});
	}
}


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
	if($('#Id_customer').val()!= '' && $('#Id_customer').val()!= -1 ){
		$('#btn-actions-box').removeClass('div-hidden');
		$('#loading').addClass('loading');
		$.post('".ReviewController::createUrl('AjaxFillInbox')."', 
		{
			tagFilter: $('#tagFilter').val(),
			Id_customer: $('#Id_customer').val(),
			typeFilter: $('#typeFilter').val(),
			reviewTypeFilter: $('#reviewTypeFilter').val(),
			dateFromFilter: $('#dateFromFilter').val(),
			dateToFilter: $('#dateToFilter').val()
			
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
	else
	{
		$('#btn-actions-box').addClass('div-hidden');
		$('#review-area').html('');
		$('#review-area').addClass('div-hidden');
	}
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

$('#linkCustomers').click(function(){
	jQuery('#SelectCustomer').dialog('open'); return false;
});

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
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
			'id'=>'SelectCustomer',
			// additional javascript options for the dialog plugin
			'options'=>array(
					'title'=>'Seleccionar Cliente',
					'autoOpen'=>false,
					'modal'=>true,
					'width'=> '500',
					'buttons'=>	array(
							'Cancelar'=>'js:function(){jQuery("#SelectCustomer").dialog( "close" );}',
							'Aceptar'=>'js:function()
							{
								jQuery("#wating").dialog("open");
								$("#Id_customer").val($.fn.yiiGridView.getSelection("customer-grid"));
								jQuery("#wating").dialog("close");
								jQuery("#SelectCustomer").dialog( "close" );
								$.post("'.ReviewController::createUrl('AjaxGetCustomerName').'", 
								{
									Id_customer: $("#Id_customer").val()
								}	
								).success(
								function(data){
									if(data != "")
										$("#linkCustomers").text(data);
									else
										$("#linkCustomers").text("Clientes");
									doFilter();
								});		
								
							

}'),
			),
	));
	
	$modelCustomer=new Customer('search');
	$modelCustomer->unsetAttributes();  // clear any default values

	//echo $this->renderPartial('../customer/_select', array('model'=>$modelCustomer, 'idCustomer'=>$Id_customer));

	$this->endWidget('zii.widgets.jui.CJuiDialog');
	?>
</div>
<?php endif;?>
<?php if(User::canCreate()):?>

<?php
	echo CHtml::openTag('div',array('class'=>'review-action-box-btn div-hidden','id'=>'btn-actions-box'));	
		echo CHtml::link('Nuevo','',array('id'=>'btn-create','class'=>'submit-btn'));
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
