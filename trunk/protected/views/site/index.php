<?php
Yii::app()->clientScript->registerScript('site-index', "
$(window).scroll(function(){

	if  ($(window).scrollTop() == $(document).height() - $(window).height()){
		var lastId;
		var lastLeft = 0;
		$('#big-loading').addClass('big-loading');
		if($('.view-single-right:last').attr('id')<$('.view-single-left:last').attr('id'))
		{
			lastId = $('.view-single-right:last').attr('id');
		}
		else
		{
			lastId= $('.view-single-left:last').attr('id');
			lastLeft = 1;
		}
		$.post('".SiteController::createUrl('AjaxFillNextWall')."',
		'&lastId='+lastId+'&lastLeft='+lastLeft
		).success(
		function(data){
			$('#big-loading').removeClass('big-loading');
			if(lastLeft){
				$('.view-single-left:last').after(data);
			}else{
				$('.view-single-right:last').after(data);
			}
		});
	}
});
");
echo CHtml::openTag('div',array('class'=>'view-index')); 
$data = $dataProvider->getData();
$left=true;
$first = true;
foreach ($data as $item){
	if($left)
	{
		$left=false;
		$this->renderPartial('_viewLeft',array('data'=>$item));
	}else 
	{
		$left=true;
		$this->renderPartial('_viewRight',array('data'=>$item,'first'=>$first));		
		$first=false;
	}
}
echo CHtml::closeTag('div');
?>
<div id="big-loading" class="big-loading-place-holder" >
</div>
