<?php
	Yii::app()->clientScript->registerScript(__CLASS__.'#uploadifyWidget', "
	
	$('#file_upload').uploadify({
	        'swf'      : '".$assets."/uploadify.swf',
	        'uploader' : '".$action."&idAlbum='+$('#uploadify_id_album').val()+'&idReview='+$('#uploadify_id_review').val(),
	        // Put your options here
	        'buttonText' : 'Seleccione'
		});
	");
	echo CHtml::openTag('input',array('type'=>'file','name'=>'file_upload', 'id'=>'file_upload'));
 	echo CHtml::hiddenField('uploadify_id_review',$idReview);
 	echo CHtml::hiddenField('uploadify_id_album',$idAlbum);
 	
		