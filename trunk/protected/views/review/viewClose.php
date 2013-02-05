<?php
$browser = get_browser(null, true);

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-with-gallery.js',CClientScript::POS_HEAD);
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide-exe.js',CClientScript::POS_HEAD);
$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide.css');
if($browser['browser']=='IE')
{
	$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.uploadify-3.1.js',CClientScript::POS_HEAD);
	$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/uploadify.css');
}

Yii::app()->clientScript->registerScript(__CLASS__.'#review_update'.$model->Id, "


");
?>
<div class="review-update-data">

	<div class="review-update-data-info">
		<?php 			
			echo CHtml::openTag('div',array('class'=>'review-update-data-info-descr-number'));				
			echo CHtml::encode($model->review.' -');				
			echo CHtml::closeTag('div');				
		?>
	</div>
	<div class="review-update-data-info-descr">
		<?php 
			echo CHtml::openTag('div',array('class'=>'review-update-data-info-descr-text'));				
			echo CHtml::encode($model->description);				
			echo CHtml::closeTag('div');				 
			echo CHtml::image('images/reload.png','',array('class'=>'review-need-update', 'id'=>'need_reload','title'=>'Recargar'));
		?>
	</div>
</div>
<div class="review-close-area">
<?php 
	echo CHtml::encode($model->closing_description);
?>
</div>
<div class="wall-action-area" id="wall-action-area">
	<div id="customer" class="review-action-back" >
	<?php echo CHtml::link($model->customer->name.' '.$model->customer->last_name,
		ReviewController::createUrl('index',array('Id_customer'=>$model->Id_customer)),
		array('class'=>'index-review-single-link')
		);
	 ?>
	</div>

	<div id="loading" class="loading-place-holder" >
	</div>
	<?php
	echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'btn-box'));
	
		echo CHtml::openTag('div',array('class'=>'review-type'));
			echo CHtml::openTag('div',array('class'=>'review-attr-level'));		
				echo CHtml::label('Tipo: ','Id_review_type');
			echo CHtml::closeTag('div');
			echo CHtml::openTag('div',array('class'=>'review-attr-text'));		
				echo CHtml::encode($model->reviewType->description);
			echo CHtml::closeTag('div');
		echo CHtml::closeTag('div');
	echo CHtml::closeTag('div');
	?>
</div>
	
<div id="review-view">
	<?php 
		$modelUserGroupNote = new UserGroupNote();
		$modelUserGroupNote->Id_review = $model->Id;
		$modelUserGroupNote->Id_user_group = User::getCurrentUserGroup()->Id;
		$dataProviderUserGroupNote = $modelUserGroupNote->search();
		$infOrder = 'note.change_date DESC';
		if(isset($order))
			$infOrder = "t.". $order . " DESC , " . $infOrder;
		else
			$infOrder = "t.addressed DESC , " . $infOrder;
		
		$dataProviderUserGroupNote->criteria->order= $infOrder;
		
		$noteData = $dataProviderUserGroupNote->data;
		echo CHtml::openTag('div',array('class'=>'review-container-single-view','style'=>'display:none;','id'=>'noteContainer_place_holder'));
		echo CHtml::closeTag('div');
		foreach ($noteData as $item) {
			echo CHtml::openTag('div',array('class'=>'review-container-single-view','id'=>'noteContainer_'.$item->note->Id));
			$this->renderPartial('_viewCloseData',array('data'=>$item->note,'dataUserGroupNote'=>$item));
			echo CHtml::closeTag('div');
		}
	?>
</div>

<?php
$this->widget('ext.processingDialog.processingDialog', array(
		'idDialog'=>'dialogProcessing',
));
?>