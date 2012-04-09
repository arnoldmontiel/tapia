<?php
Yii::app()->clientScript->registerScript('indexWall', "

$('#Id_customer').change(function(){
	
	if($(this).val()!= ''){
		$('#btn-box').removeClass('div-hidden');
		$('#loading').addClass('loading');
		$.post('".ReviewController::createUrl('AjaxFillInbox')."', 
			$(this).serialize()
		).success(
		function(data){
			$('#loading').removeClass('loading');
			$('#review-area').html(data);
			//bindEvents(data);
			$('#review-area').animate({opacity: 'show'},240);
			$('#btn-create').attr('href','".ReviewController::createUrl('create')."'+'&Id_customer='+$('#Id_customer').val());
		});		
	}
	else
	{
		$('#btn-box').addClass('div-hidden');
		$('#review-area').addClass('div-hidden');
}
	return false;
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
	echo CHtml::openTag('div',array('class'=>'div-hidden wall-action-box-btn','id'=>'btn-box'));	
			echo CHtml::link('Nuevo','',array('id'=>'btn-create','class'=>'submit-btn'));
	echo CHtml::closeTag('div');	
?>

</div>
<div id="review-area" class="div-hidden index-review-area" >
</div>
