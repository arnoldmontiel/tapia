<?php
Yii::app()->clientScript->registerScript('index-review', "
");
?>

<div class="wall-action-area" id="wall-action-area">
<div id="loading" class="loading-place-holder" >
</div>
<div id="customer" class="wall-action-ddl" >
	<?php echo CHtml::label($modelCustomer->name.' '.$modelCustomer->last_name,'Id_customer'); ?>
</div>

<?php
	echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'btn-box'));
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnImage'));
			echo 'Imagenes';
		echo CHtml::closeTag('div');
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnAlbum'));
			echo 'Album';
		echo CHtml::closeTag('div');	
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnNote'));
			echo 'Notas';
		echo CHtml::closeTag('div');	
		echo CHtml::openTag('div',array('class'=>'wall-action-btn','id'=>'btnDoc'));
			echo 'Documentos';
		echo CHtml::closeTag('div');
	echo CHtml::closeTag('div');	
?>

</div>
<div id="review-area" class="div-hidden index-review-area" >


<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
