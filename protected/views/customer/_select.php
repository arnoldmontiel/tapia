<?php
Yii::app()->clientScript->registerScript('_selectCustomers', "

loadGrid();
function loadGrid()
{
	var index = 0;
	$('#customer-grid').find('.keys span').each(function(i)
	{
		if(  '". $idCustomer ."' == $(this).text())
			return false;
		index++;
	});
	
	$('#customer-grid > table > tbody > tr').each(function(i)
	{
		if(i == index)
		{
			$(this).addClass('selected');
		}
    });
}

");
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'customer-grid',
	'dataProvider'=>(User::isInternal())?$model->searchInternal():$model->searchNotInternal(),
	'filter'=>$model,
	'ajaxUrl'=>CustomerController::createUrl('customer/AjaxSelect'),
	'columns'=>array(
		'name',
		'last_name',
		'building_address',
		array(
 			'name'=>'tag_description',
			'value'=>'$data->tagDesc',
		),
	),
)); ?>
