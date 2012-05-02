<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#review-view-pending-data'.$data->Id, "
$('#publicArea_".$data->Id."').children().each(
	function(index, item){

	}
);

");
?>

<div class="review-single-view" id="<?php echo $data->Id?>" >
	<div class="view-text-date"><?php echo $data->change_date;?></div>
	<div id='edit_image<?php echo $data->Id?>' class="review-edit-image div-hidden">
	<?php
		echo CHtml::link('Editar Imagenes',
			ReviewController::createUrl('AjaxAttachImage',array('id'=>$data->review->Id, 'idNote'=>$data->Id)),
			array('class'=>'review-edit-image')
		);
	?>
	</div>
	<?php
	 echo CHtml::image('images/remove.png','',
			array('id'=>'delete_'.$data->Id, 'class'=>'wall-action-remove', 'title'=>'Eliminar'));
	?>
	<div class="review-text-simple-note">
		<div id='edit_main_note_<?php echo $data->Id?>' class="review-create-note-btn review-create-note-btn-main div-hidden">
			Grabar
		</div>
		<div id='edit_main_note_cancel_<?php echo $data->Id?>' class="review-create-note-btn review-create-note-btn-main-cancel div-hidden">
			Cancelar
		</div>
	
	<textarea id='main_note<?php echo $data->Id?>' class="wall-action-edit-main-note" placeholder='Escriba una nota...'><?php echo $data->note;?></textarea>
	<textarea id='main_original_note<?php echo $data->Id?>' class="wall-action-edit-main-note" style="display: none;" placeholder='Escriba una nota...'><?php echo $data->note;?></textarea>
	</div>		
	<div class="review-multimedia-conteiner">
		<div id='review_image<?php echo $data->Id?>' class="review-text-images">
				
		<?php
		
		$images = array();
		$height=0;
		foreach($data->multimedias as $item)
		{
			if($item->Id_multimedia_type!=1) continue;
			$image= array();
			$image['image'] = "images/".$item->file_name;
			$image['small_image'] = "images/".$item->file_name_small;
			$image['caption'] = $item->description;
			if($item->height_small>$height)
			{
				$height = $item->height_small;
			}
			$images[]=$image;
		}
		if(sizeof($images)>0)
		{
		
			$this->widget('ext.highslide.highslide', array(
													'images'=>$images,
													'Id'=>$data->Id,
													'height'=>$height,
			));
		}
		?>
		</div>
		<div class="review-text-docs">
			<?php 
				if(sizeof($images)==0)
				{
					echo CHtml::openTag('div', array('class'=>'review-add-images-container'));				
					echo CHtml::link('Adjuntar Imagenes',
						ReviewController::createUrl('AjaxAttachImage',array('id'=>$data->review->Id, 'idNote'=>$data->Id)),
						array('class'=>'review-text-docs')
					);
					echo CHtml::closeTag('div');
				}
				foreach($data->multimedias as $item)
				{
					if($item->Id_multimedia_type!=3
						&&$item->Id_multimedia_type!=4) continue;
					echo CHtml::openTag('div');
					
					echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
					if($item->Id_multimedia_type == 4)
						echo CHtml::image('images/autocad_resource.png','',array('style'=>'width:25px;'));
					else
						echo CHtml::image('images/pdf_resource.png','',array('style'=>'width:25px;'));
					echo CHtml::closeTag('div');
					
					echo CHtml::link(
						CHtml::encode($item->file_name),
						Yii::app()->baseUrl.'/docs/'.$item->file_name,
						array('target'=>'_blank','class'=>'review-text-docs')
					);
					echo CHtml::encode(' '.round(($item->size / 1024), 2));
					echo CHtml::encode(' (Kb) ');
					
					echo CHtml::openTag('div',array('class'=>'review-area-single-files-description'));
					echo CHtml::encode($item->description);
					echo CHtml::closeTag('div');
					
					echo CHtml::closeTag('div');
						
				}
				echo CHtml::openTag('div', array('class'=>'review-add-docs-container'));				
				echo CHtml::link('Adjuntar Documentos',
					ReviewController::createUrl('AjaxAttachDoc',array('id'=>$data->review->Id, 'idNote'=>$data->Id)),
					array('class'=>'review-text-docs'));
				echo CHtml::closeTag('div');
					
			?>
		</div>
	</div>
	<div>
		<?php
			$criteria=new CDbCriteria;
			
			$criteria->addCondition('t.Id <> '. User::getCurrentUserGroup()->Id);
			
			$modelUserGroup = UserGroup::model()->findAll($criteria);
		
			echo CHtml::openTag('div', array('id'=>'publicArea_'.$data->Id, 'class'=>'review-public-permission-area'));
			echo CHtml::decode('Permisos');
			
			foreach($modelUserGroup as $item)
			{
				$dafaultFeedback = $item->can_feedback;
				$dafaultRead = $item->can_read;
				$dafaultAddressed = $item->addressed;
				$dafaultConfirmation = $item->need_confirmation;
				
				echo CHtml::openTag('div', array('id'=>'userGroup_'.$item->Id));
					echo CHtml::openTag('div', array('class'=>'review-permission-row','style'=>'display: inline-block; width:50%;'));
						if($dafaultFeedback || $dafaultRead || $dafaultAddressed || $dafaultConfirmation)
						{
							echo CHtml::checkBox('chkUserGroup',true,array('id'=>'chkUserGroup','value'=>$item->Id,'style'=>'display:none'));
							echo CHtml::openTag('div',array('id'=>'divChkUserGroup','class'=>'review-permission-chk-decoration review-permission-chk-decoration-chk'));
						}
						else
						{
							echo CHtml::checkBox('chkUserGroup','',array('id'=>'chkUserGroup','value'=>$item->Id,'style'=>'display:none'));
							echo CHtml::openTag('div',array('id'=>'divChkUserGroup','class'=>'review-permission-chk-decoration'));
						}
							echo CHtml::encode($item->description);
						echo CHtml::closeTag('div');
					echo CHtml::closeTag('div');
					echo CHtml::openTag('div', array('class'=>'review-permission-row'));
						if($dafaultAddressed)
						{
							echo CHtml::checkBox('chkAddressed',true,array('id'=>'chkAddressed','value'=>$item->Id,'style'=>'display:none'));
							echo CHtml::openTag('div',array('id'=>'divChkAddressed','class'=>'review-permission-chk-decoration review-permission-chk-decoration-chk'));
						}
						else
						{
							echo CHtml::checkBox('chkAddressed','',array('id'=>'chkAddressed','value'=>$item->Id,'style'=>'display:none'));
							echo CHtml::openTag('div',array('id'=>'divChkAddressed','class'=>'review-permission-chk-decoration'));
						}	
							echo CHtml::encode('Para');
						echo CHtml::closeTag('div');						
					echo CHtml::closeTag('div');
					echo CHtml::openTag('div', array('class'=>'review-permission-row'));
						if($dafaultFeedback)
						{
							echo CHtml::checkBox('chkCanFeedback',true,array('id'=>'chkCanFeedback','value'=>$item->Id,'style'=>'display:none'));
							echo CHtml::openTag('div',array('id'=>'divChkCanFeedback','class'=>'review-permission-chk-decoration review-permission-chk-decoration-chk'));
						}
						else
						{
							echo CHtml::checkBox('chkCanFeedback','',array('id'=>'chkCanFeedback','value'=>$item->Id,'style'=>'display:none'));
							echo CHtml::openTag('div',array('id'=>'divChkCanFeedback','class'=>'review-permission-chk-decoration'));
						}
							echo CHtml::encode('Respuesta');
						echo CHtml::closeTag('div');						
					echo CHtml::closeTag('div');
					echo CHtml::openTag('div', array('class'=>'review-permission-row'));
						if($dafaultConfirmation)
						{
							echo CHtml::checkBox('chkNeedConfirmation',true,array('id'=>'chkNeedConfirmation','value'=>$item->Id,'style'=>'display:none'));
							echo CHtml::openTag('div',array('id'=>'divChkNeedConfirmation','class'=>'review-permission-chk-decoration review-permission-chk-decoration-chk','style'=>'width:70px;'));
						}
						else
						{
							echo CHtml::checkBox('chkNeedConfirmation','',array('id'=>'chkNeedConfirmation','value'=>$item->Id,'style'=>'display:none'));
							echo CHtml::openTag('div',array('id'=>'divChkNeedConfirmation','class'=>'review-permission-chk-decoration','style'=>'width:70px;'));
						}
							echo CHtml::decode('Confirmaci&oacute;n');
						echo CHtml::closeTag('div');												
					echo CHtml::closeTag('div');
				echo CHtml::closeTag('div');
			}
			echo CHtml::openTag('div', array('class'=>'review-action-permissions-box-btn'));
			echo CHtml::openTag('div', array('id'=>'public_'.$data->Id,'class'=>'review-action-btn'));
			echo "Publicar";
			echo CHtml::closeTag('div');
			echo CHtml::closeTag('div');
			echo CHtml::closeTag('div');
				
		?>
	</div>
</div>


