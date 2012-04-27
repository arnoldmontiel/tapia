<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#review-view-data'.$data->Id, "

");
$canDoFeeback = $dataUserGroupNote->can_feedback;
$needConfirmation = $dataUserGroupNote->need_confirmation;
$confirmed = $dataUserGroupNote->confirmed;
$declined = $dataUserGroupNote->declined;
$isAdministrator = User::isAdministartor();
$isOwner = User::isOwnerOf($data);
$editable = $isAdministrator||$isOwner; 
?>

<div class="review-single-view" id="<?php echo $data->Id?>" >
	<div class="view-text-date"><?php echo $data->creation_date;?></div>
	<?php if($editable):?>
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
	<?php endif;?>
	<div class="review-text-simple-note">
		<div class="review-single-view-actions">
			<div class="review-single-view-actions-need-conf">
				<?php
				echo CHtml::encode($data->user->username);								
				?>
			</div>
		</div>
		<div class="review-single-view-actions">
			<div class="review-single-view-actions-need-conf">
				<?php
				echo CHtml::decode('Para:');
				$first = true;
				foreach ($data->userGroupNotes as $item){
					if($item->addressed){
						if(!$first)
						{
							echo CHtml::encode(',');								
						}
						$first = false;							
						$group = User::getCurrentUserGroup();
						if($item->Id_user_group==$group->Id)
						{
							$user=User::getCurrentUser();
							echo CHtml::encode(' '.$user->username);								
						}
						else 
						{
							echo CHtml::encode(' '.$item->userGroup->description);								
						}
					}
				}
				?>
			</div>
			<div class="review-single-view-actions-conf">
				<?php 	 		
		 		if($needConfirmation)
		 		{
		 			if($confirmed || $declined)
		 			{
		 				echo CHtml::openTag('div',array('class'=>'review-confirmed-note-btn review-confirm-note-btn-pos'));
		 				echo ($confirmed)?'Confirmardo':'Rechazado';
		 				echo CHtml::closeTag('div');	 				
		 			}
		 			else 
		 			{
		 				echo CHtml::openTag('div',array('class'=>'review-confirm-note-btn review-confirm-note-btn-pos','id'=>'confirm_note_'.$data->Id));
		 				echo 'Confirmar';
		 				echo CHtml::closeTag('div');
		 				echo CHtml::openTag('div',array('class'=>'review-decline-note-btn review-decline-note-btn-pos','id'=>'decline_note_'.$data->Id));
		 				echo 'Rechazar';
		 				echo CHtml::closeTag('div');
		 			}
		 		}
		 	?>
			</div>
		</div>
		<div id='edit_main_note_<?php echo $data->Id?>' class="review-create-note-btn review-create-note-btn-main div-hidden">
			Grabar
		</div>
		<div id='edit_main_note_cancel_<?php echo $data->Id?>' class="review-create-note-btn review-create-note-btn-main-cancel div-hidden">
			Cancelar
		</div>
		<?php if($editable):?>
			<textarea id='main_note<?php echo $data->Id?>' class="wall-action-edit-main-note" placeholder='Escriba una nota...'><?php echo $data->note;?></textarea>
			<textarea id='main_original_note<?php echo $data->Id?>' class="wall-action-edit-main-note" style="display: none;" placeholder='Escriba una nota...'><?php echo $data->note;?></textarea>
		<?php else:?>
			<div class="wall-action-edit-main-note" >
			<p class="single-formated-text"><?php echo $data->note;?></p>
			</div>
		<?php endif;?>
		
	
	<?php if($isOwner):?>
		<div>
			<?php
				$criteria=new CDbCriteria;
				
				$criteria->addCondition('t.Id <> '. User::getCurrentUserGroup()->Id);
				
				$modelUserGroup = UserGroup::model()->findAll($criteria);
			
				$modelUserGroupNote = UserGroupNote::model()->findAllByAttributes(array('Id_note'=>$data->Id));
				
				$modelNoteNote = NoteNote::model()->findAllByAttributes(array('Id_parent'=>$data->Id));
				
				echo CHtml::openTag('div', array('id'=>'publicArea_'.$data->Id,'name'=>'edit-permissions', 'class'=>'review-permission-area'));
				echo CHtml::decode('Editar Permisos');
				
				foreach($modelUserGroup as $item)
				{
					$modelUserGroupNoteInstance = null;
					foreach($modelUserGroupNote as $itemGroupNote)
					{
						if($itemGroupNote->Id_user_group == $item->Id)
						{
							$modelUserGroupNoteInstance = $itemGroupNote;
							break;
						}
					}	
					
					$canEditFeedback = true;
					foreach($modelNoteNote as $itemNoteNote)
					{
						if($itemNoteNote->child->Id_user_group_owner == $item->Id)
						{
							$canEditFeedback = false;
							break;
						}
					}	
					
					
					
					$canEditNeedConf = !($modelUserGroupNoteInstance->confirmed || $modelUserGroupNoteInstance->declined);
					
					echo CHtml::openTag('div', array('id'=>'userGroup_'.$item->Id));
					
						echo CHtml::openTag('div', array('class'=>'review-permission-row','style'=>'display: inline-block; width:50%;'));
							if($modelUserGroupNoteInstance)
							{
								echo CHtml::checkBox('chkUserGroup',$modelUserGroupNoteInstance,array('id'=>'chkUserGroup','value'=>$item->Id,'style'=>'display:none'));
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
							if($modelUserGroupNoteInstance && $modelUserGroupNoteInstance->addressed )
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
							if($modelUserGroupNoteInstance && $modelUserGroupNoteInstance->can_feedback )
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
							if($modelUserGroupNoteInstance && $modelUserGroupNoteInstance->need_confirmation)
							{
								echo CHtml::checkBox('chkNeedConfirmation',true,array('id'=>'chkNeedConfirmation','value'=>$item->Id,'style'=>'display:none'));
								echo CHtml::openTag('div',array('id'=>'divChkNeedConfirmation','class'=>'review-permission-chk-decoration review-permission-chk-decoration-chk','style'=>'width:70px;'));
							}
							else
							{
								echo CHtml::checkBox('chkNeedConfirmation','',array('id'=>'chkNeedConfirmation','value'=>$item->Id,'style'=>'display:none'));
								echo CHtml::openTag('div',array('id'=>'divChkNeedConfirmation','class'=>'review-permission-chk-decoration','style'=>'width:70px;'));
							}
							if($canEditNeedConf)
							{
								echo CHtml::decode('Confirmaci&oacute;n');
							}
							else
							{
								if($modelUserGroupNoteInstance->confirmed)
								{
									echo CHtml::decode('Confirmado');										
								}
								else 
								{
									echo CHtml::decode('Declinado');										
								}								
							}
							echo CHtml::closeTag('div');												
						echo CHtml::closeTag('div');
					echo CHtml::closeTag('div');
				}
				echo CHtml::closeTag('div');
					
			?>
		</div>	
		<?php endif;?>
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
				if($editable){
					echo CHtml::link('Adjuntar Imagenes',
						ReviewController::createUrl('AjaxAttachImage',array('id'=>$data->review->Id, 'idNote'=>$data->Id)),
						array('class'=>'review-text-docs')
					);
				}
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
			if($editable){
				echo CHtml::link('Adjuntar Documentos',
					ReviewController::createUrl('AjaxAttachDoc',array('id'=>$data->review->Id, 'idNote'=>$data->Id)),
					array('class'=>'review-text-docs'));
			}
			echo CHtml::closeTag('div');
				
		?>
	</div>
	</div>
	<div id="singleNoteContainer" class="singles-notes-container">
	<?php $notes=$data->notes;?>
	<?php if (!empty($notes)):?>
		<?php 
		foreach($notes as $item)
		{
			echo CHtml::openTag('div',array('class'=>'view-text-note'));
				echo CHtml::openTag('div',array('class'=>'view-text-date'));
					echo $item->creation_date;
				echo CHtml::closeTag('div');
				if($editable)
				{
					echo CHtml::image('images/remove.png','',
					array('id'=>'left_note_'.$item->Id.'_'.$data->Id, 'class'=>'wall-action-remove-small','title'=>'Remove'));						
				}
				echo CHtml::openTag('p',array('class'=>'single-formated-text'));
					echo $item->note;
				echo CHtml::closeTag('p');
			echo CHtml::closeTag('div');
		}
		?>		
	<?php endif?>
	</div>
	<?php if($canDoFeeback):?>
	<div class="review-text-note-add">

		<div id='create_note_<?php echo $data->Id?>' class="review-create-note div-hidden">
			Grabar
		</div>
		<div id='create_note_cancel_<?php echo $data->Id?>' class="review-create-note-cancel div-hidden">
			Cancelar
		</div>
				
		<textarea id="note_<?php echo $data->Id?>" class="review-action-add-note" placeholder='Escriba una nota...'></textarea>
	</div>
		<?php endif;?>
</div>


