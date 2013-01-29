<?php
	Yii::app()->clientScript->registerScript(__CLASS__.'#uploadifyWidget', "
	
	$('#file_upload').uploadify({
	        'swf'      : '".$assets."/uploadify.swf',
	        'uploader' : '".$action."&idAlbum='+$('#uploadify_id_album').val()+'&idCustomer='+$('#uploadify_id_customer').val(),
	        // Put your options here
			        'buttonText' : 'Seleccione',
			        'onUploadSuccess' : function(file, data, response) {
	         		   //alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
						var target = $('.album-view-image:first');
						$(target).before(data);
						target = $('.album-view-image:first');
						$(target).animate({opacity: 'show'},400);
						$(target).find('#photo_description').change(function(){
							$.get('".$AjaxAddImageDescriptionURL."',
 							{
								IdMultimedia:$(target).attr('id'),
								description:$(this).val()
 							}).success(
 								function(data) 
 								{
								}
							);                         		
						});
						$(target).find('#photo_cancel').click(function(){
								
							$.get('".$AjaxRemoveImageURL."',
 							{
								IdMultimedia:$(target).attr('id')
							}).success(
								function(data) 
								{
									$(target).remove();	
								}
							);
						});
			        }
			});
	");
	echo CHtml::openTag('input',array('type'=>'file','name'=>'file_upload', 'id'=>'file_upload'));
 	echo CHtml::hiddenField('uploadify_id_customer',$idCustomer);
 	echo CHtml::hiddenField('uploadify_id_album',$idAlbum);
 	?>
 	<div id="uploaded">
 	<div class="album-view-image" style="display:none">
 	</div>
 	</div>
 	
 	
		