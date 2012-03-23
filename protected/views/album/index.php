<?php

Yii::app()->clientScript->registerScript('indexWall', "
	
$('#btnNote').click(function(){

	$('#divNote').animate({opacity: 'show'},240);
	$('#divImage').animate({opacity: 'hide'},240);
	$('#uploadFile').val('');
});

$('#btnImage').click(function(){
	$('#Note_note').val('');
	$('#divNote').animate({opacity: 'hide'},240);
	$('#divImage').animate({opacity: 'show'},240);
});

$('#eee').click(function(){
	alert(1);

});
");
?>
<?php
$this->widget('ext.xupload.XUploadWidget', array(
                    'url' => AlbumController::createUrl('upload'),
					'multiple'=>true,
					'name'=>'file',
					'options' => array(
						'onComplete' => 'js:function (event, files, index, xhr, handler, callBack) {

							id = jQuery.parseJSON(xhr.response).id;
							$tr = $(document).find("#"+id);
							$tr.find(".file_upload_cancel button").click(function(){
								var target = $(this);
											
								$.get("'.AlbumController::createUrl('AjaxRemoveImage').'",
 									{
										IdMultimedia:$(target).parent().parent().attr("id")
 								}).success(
 									function(data) 
 									{
 										
 										$(target).parent().parent().attr("style","display:none");	
 									}
 								);
                         		
 							});
                        		

                         }'
					),
));
?>

<button id="eee" class="ui-state-default ui-corner-all" title="Cancel">
	<span class="ui-icon ui-icon-cancel">Cancel</span>
</button>