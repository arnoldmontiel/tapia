<?php
$this->breadcrumbs=array(
	'Walls',
);

?>

<h1>Walls</h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'wall-form',
		'enableAjaxValidation'=>true,
));

?>
<?php

 $this->widget('ext.richtext.jwysiwyg', array(
 	'id'=>'noteContainer',	// default is class="ui-sortable" id="yw0"	
 	'notes'=> null
 			));

?>
<div style="display: inline-block;widht:100px;">
					<?php
						echo CHtml::imageButton(
			                                'images/share.png',
			                                array(
			                                'title'=>'Publicar',
			                                'width'=>'100px',
			                                'id'=>'share',
			                                	'ajax'=> array(
													'type'=>'POST',
													'url'=>WallController::createUrl('AjaxShare'),
													'beforeSend'=>'function(){
																if(! $.trim($("#wysiwyg-wysiwyg-iframe").contents().find("body").text()).length > 0)
																{
																	alert("You can not post an empty note");
																	return false;
																}
													}',
													'success'=>'js:function(data)
													{
														$.fn.yiiListView.update("listWall-view");
														$("#wysiwyg-wysiwyg-iframe").contents().find("body").text("");
													}'
			                                	)
			                                )
			                                                         
			                            ); 
					?>
		</div>
		
<br>
<div id="wallView">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'id'=>'listWall-view',
	'summaryText' =>"",
)); ?>
</div>		
<?php $this->endWidget(); ?>

</div><!-- form -->