<?php
Yii::app()->clientScript->registerScript('indexWall', "

$('#Id_customer').change(function(){
	
	var data = { 'filter[]' : []};

	$('input:checked').each(function() {
		if($(this).val() != '')
 	 		data['filter[]'].push($(this).val());
	});
	$('#tagFilter').val('');
	$('#tagFilter').val(data['filter[]']);
	doFilter();
	
	return false;
});

function doFilter()
{
	if($('#Id_customer').val()!= ''){
		$('#btn-actions-box').removeClass('div-hidden');
		$('#loading').addClass('loading');
		$.post('".ReviewController::createUrl('AjaxFillInbox')."', 
		{
			tagFilter: $('#tagFilter').val(),
			Id_customer: $('#Id_customer').val()
			
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

LoadPage();
function LoadPage()
{
	var id_customer =".$Id_customer."; 
	if(id_customer!=-1)
	{
		doFilter();
	}
}

$('#btn-filter').click(function(){
	var data = { 'filter[]' : []};

	$('input:checked').each(function() {
		if($(this).val() != '')
 	 		data['filter[]'].push($(this).val());
	});
	$('#tagFilter').val('');
	$('#tagFilter').val(data['filter[]']);
	doFilter();
});
");
?>

<div class="review-action-area" id="review-action-area">
<div id="loading" class="loading-place-holder" >
</div>
<div id="customer" class="wall-action-ddl" >
	<?php	$customers = CHtml::listData($ddlCustomer, 'Id', 'CustomerDesc');?>
	<?php echo CHtml::label('Cliente: ','Id_customer'); ?>
	<?php echo CHtml::dropDownList('Id_customer',$Id_customer, $customers,		
		array(
			'prompt'=>'Clientes',
			)		
		);
		?>
</div>

<?php
	echo CHtml::openTag('div',array('class'=>'review-action-box-btn div-hidden','id'=>'btn-actions-box'));	
		echo CHtml::link('Nuevo','',array('id'=>'btn-create','class'=>'submit-btn'));
	echo CHtml::closeTag('div');	
?>

</div>
<?php
	echo CHtml::hiddenField('tagFilter','',array('id'=>'tagFilter'));	
?>
<div id="review-area" class="index-review-area div-hidden" >
</div>
