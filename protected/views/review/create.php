<?php
Yii::app()->clientScript->registerScript('index-review', "
");
?>

<div class="review-action-area" id="review-action-area">
<div id="loading" class="loading-place-holder" >
</div>
<div id="customer" class="wall-action-ddl" >
	<?php echo CHtml::label($modelCustomer->name.' '.$modelCustomer->last_name,'Id_customer'); ?>
</div>

</div>
<div id="review-area" class="index-review-area" >


<?php echo $this->renderPartial('_form', array('model'=>$model,'modelPriority'=>$modelPriority,'modelReviewType'=>$modelReviewType)); ?>
</div>
