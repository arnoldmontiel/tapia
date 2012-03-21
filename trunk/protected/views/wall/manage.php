<?php
Yii::app()->clientScript->registerScript('customerMovies', "
$('#Wall_Id_customer').change(function(){
	if($(this).val()!= ''){
		$.fn.yiiListView.update('listWall-view', {
			data: $(this).serialize()
		});
		$('#display').animate({opacity: 'show'},240);
	}
	else{
		$('#display').animate({opacity: 'hide'},240);	
	}
	return false;
});

");
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'manage-form',
			'enableAjaxValidation'=>true,
	));
		
?>
	<div id="customer" style="margin-bottom: 5px">
		
		<?php	$customers = CHtml::listData($ddlSource, 'Id', 'CustomerDesc');?>

		<?php echo $form->labelEx($model,'Customer'); ?>

		<?php echo $form->dropDownList($model, 'Id_customer', $customers,		
			array(
				'prompt'=>'Select a Customer'
			)		
		);
		
		
		
		?>
		
	</div>
	<div id="display"
	style="display: none">
		<div class="view-index">

		<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_view',
			'id'=>'listWall-view',
			'summaryText' =>"",
		)); ?>		
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->