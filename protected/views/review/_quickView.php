<div class="index-review-quick-view">
<div class="review-action-customer" >
	<?php 
		//echo CHtml::link($customer->name.' '.$customer->last_name. ' - ' . $customer->tagDesc,
	echo CHtml::link($customer->name.' '.$customer->last_name,
		ReviewController::createUrl('index',array('Id_customer'=>$customer->Id)),
		array('class'=>'index-review-single-link')
		);
	 ?>
</div>
<?php 
foreach ($data as $item){
	$this->renderPartial('_view',array('data'=>$item));
}

if(count($data) == 0)
	echo '<div  class="index-review-customer-separator"></div>'; 
?>

</div>