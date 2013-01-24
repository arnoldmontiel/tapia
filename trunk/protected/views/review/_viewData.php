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
	<div class="view-text-date"><?php echo $data->change_date;?></div>
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
			<div class="review-single-view-autor">
				<?php
				echo CHtml::encode($data->user->name.' '.$data->user->last_name);								
				?>
			</div>
		</div>
		<div class="review-single-view-actions">
			<div class="review-single-view-actions-need-conf">
				<?php
				echo CHtml::openTag('div',array('class'=>'review-note-users-groups'));								
					echo CHtml::decode('Para: ');
				echo CHtml::closeTag('div');								
				$first = true;
				foreach ($data->userGroupNotes as $item){
					if($item->addressed){
						if(!$first)
						{
							echo CHtml::openTag('div',array('class'=>'review-note-users-groups'));								
								echo CHtml::encode(',');								
							echo CHtml::closeTag('div');								
						}
						$first = false;							
						$group = User::getCurrentUserGroup();
						if($item->Id_user_group==$group->Id)
						{
							$user=User::getCurrentUser();
							
							echo CHtml::openTag('div',array('class'=>'review-note-users-names'));								
								echo CHtml::encode($user->name.' '.$user->last_name);								
							echo CHtml::closeTag('div');								
						}
						else 
						{
							echo CHtml::openTag('div',array('class'=>'review-note-users-groups'));								
								echo CHtml::encode(' '.$item->userGroup->description);								
							echo CHtml::closeTag('div');								
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
		 				$color = 'background-color:';
		 				$color.=($confirmed)?'#80e765;color:black;':'#ed5656;color:black;';
		 				echo CHtml::openTag('div',
		 					array(
		 						'class'=>'review-confirmed-note-btn review-confirm-note-btn-pos',
		 						'style'=>$color,
		 					)
		 				);
		 				echo ($confirmed)?'Confirmardo':'Rechazado';
		 				echo CHtml::closeTag('div');	 				
		 				echo CHtml::openTag('div',array('class'=>'review-conf-note-pos'));
		 				echo '('. $dataUserGroupNote->getConfirmDate() .')';
		 				echo CHtml::closeTag('div');
		 			}
		 			else 
		 			{
		 				$outOfDate = isset($dataUserGroupNote)?$dataUserGroupNote->isOutOfDate():false;
		 				if($outOfDate)
		 				{
		 					echo CHtml::openTag('div',
		 						array(
 							 			'class'=>'review-confirmed-note-btn review-confirm-note-btn-pos',
 							 			'style'=>'background-color:#80e765;color:black;',
		 							)
		 						);
		 					echo 'Auto Conf';
		 					echo CHtml::closeTag('div');
		 					echo CHtml::openTag('div',array('class'=>'review-conf-note-pos'));
		 					echo '('. $dataUserGroupNote->getDueDate() .')';
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
				
				if($data->review->reviewType->is_internal && User::getCurrentUserGroup()->Id != User::getAdminUserGroupId())
					$criteria->addCondition('t.is_internal = 1');
				
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
					
					$isAdmin = false;
					
					if(User::getAdminUserGroupId() == $item->Id)
						$isAdmin = true;
					
					$outOfDate = isset($modelUserGroupNoteInstance)?$modelUserGroupNoteInstance->isOutOfDate():false;
										
					$canEditNeedConf = !$modelUserGroupNoteInstance || (!($modelUserGroupNoteInstance->confirmed || $modelUserGroupNoteInstance->declined) && !$outOfDate);
					
						echo CHtml::openTag('div', array('id'=>'userGroup_'.$item->Id));
						
							echo CHtml::openTag('div', array('class'=>'review-permission-row review-permission-row-first'));
								echo CHtml::openTag('div',array('class'=>'review-permission-title'));
									echo CHtml::encode($item->description);
								echo CHtml::closeTag('div');
							echo CHtml::closeTag('div');
					
						echo CHtml::openTag('div', array('class'=>'review-permission-row'));
							if($modelUserGroupNoteInstance)
							{
								echo CHtml::checkBox('chkUserGroup',$modelUserGroupNoteInstance,array('id'=>'chkUserGroup','value'=>$item->Id,'style'=>'display:none'));
								echo CHtml::openTag('div',array('id'=>'divChkUserGroup', 'isadmin'=>($isAdmin)?'yes':'no', 'title'=>'Permite visualizar la nota','class'=>'review-permission-chk-decoration review-permission-chk-decoration-chk'));
							}
							else 
							{
								echo CHtml::checkBox('chkUserGroup','',array('id'=>'chkUserGroup','value'=>$item->Id,'style'=>'display:none'));
								echo CHtml::openTag('div',array('id'=>'divChkUserGroup', 'isadmin'=>($isAdmin)?'yes':'no', 'title'=>'Permite visualizar la nota', 'class'=>'review-permission-chk-decoration'));
							}
								echo CHtml::encode('Visualiza');
							echo CHtml::closeTag('div');
						echo CHtml::closeTag('div');
						
						echo CHtml::openTag('div', array('class'=>'review-permission-row'));
							if($modelUserGroupNoteInstance && $modelUserGroupNoteInstance->addressed )
							{
								echo CHtml::checkBox('chkAddressed',true,array('id'=>'chkAddressed','value'=>$item->Id,'style'=>'display:none'));
								echo CHtml::openTag('div',array('id'=>'divChkAddressed','title'=>'Indica a quien va dirigida la nota','class'=>'review-permission-chk-decoration review-permission-chk-decoration-chk'));
							}
							else
							{
								echo CHtml::checkBox('chkAddressed','',array('id'=>'chkAddressed','value'=>$item->Id,'style'=>'display:none'));
								echo CHtml::openTag('div',array('id'=>'divChkAddressed','title'=>'Indica a quien va dirigida la nota','class'=>'review-permission-chk-decoration'));
							}
								echo CHtml::encode('Para');
							echo CHtml::closeTag('div');						
						echo CHtml::closeTag('div');
						
						echo CHtml::openTag('div', array('class'=>'review-permission-row'));
							if($modelUserGroupNoteInstance && $modelUserGroupNoteInstance->can_feedback )
							{
								echo CHtml::checkBox('chkCanFeedback',true,array('id'=>'chkCanFeedback','value'=>$item->Id,'style'=>'display:none'));
								echo CHtml::openTag('div',array('id'=>'divChkCanFeedback','isadmin'=>($isAdmin)?'yes':'no','title'=>'Permite dar respuesta a la nota','class'=>'review-permission-chk-decoration review-permission-chk-decoration-chk'));
							}
							else
							{
								echo CHtml::checkBox('chkCanFeedback','',array('id'=>'chkCanFeedback','value'=>$item->Id,'style'=>'display:none'));
								echo CHtml::openTag('div',array('id'=>'divChkCanFeedback','isadmin'=>($isAdmin)?'yes':'no','title'=>'Permite dar respuesta a la nota','class'=>'review-permission-chk-decoration'));
							}
								echo CHtml::encode('Respuesta');
							echo CHtml::closeTag('div');						
						echo CHtml::closeTag('div');
						
						$label ="";
		 				$color= '';
		 				$date = '';
						if($canEditNeedConf)
						{
							$label= CHtml::decode('Confirmaci&oacute;n');
						}
						else
						{
							if($outOfDate)
							{
								$color= 'background-color:#80e765;color:black;';
								$label= CHtml::decode('Auto Conf');
								$date = '('. $modelUserGroupNoteInstance->getDueDate() .')';
							}
							else 
							{
								if($modelUserGroupNoteInstance->confirmed)
								{
			 						$color= 'background-color:#80e765;color:black;';
									$label= CHtml::decode('Confirmado');
								}
								else
								{
			 						$color= 'background-color:#ed5656;color:black;';
									$label= CHtml::decode('Declinado');
								}
								$date = '('. $modelUserGroupNoteInstance->getConfirmDate() .')';
							}
						}
						
						echo CHtml::openTag('div', array('class'=>'review-permission-row'));
							if($modelUserGroupNoteInstance && $modelUserGroupNoteInstance->need_confirmation)
							{
								echo CHtml::checkBox('chkNeedConfirmation',true,array('id'=>'chkNeedConfirmation','value'=>$item->Id,'style'=>'display:none'));
								echo CHtml::openTag('div',array('id'=>'divChkNeedConfirmation','title'=>'Indica que la nota necesita ser Aceptada/Rechazada', 'class'=>'review-permission-chk-decoration review-permission-chk-decoration-chk','style'=>'width:70px;'.$color));
							}
							else
							{
								echo CHtml::checkBox('chkNeedConfirmation','',array('id'=>'chkNeedConfirmation','value'=>$item->Id,'style'=>'display:none'));
								echo CHtml::openTag('div',array('id'=>'divChkNeedConfirmation','title'=>'Indica que la nota necesita ser Aceptada/Rechazada','class'=>'review-permission-chk-decoration','style'=>'width:70px;'.$color));
							}
							echo $label;
							echo CHtml::closeTag('div');												
						echo CHtml::closeTag('div');
						echo CHtml::openTag('div',array('class'=>'review-permission-row-date'));
							echo $date;
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
				if($item->Id_multimedia_type < 3 || $item->Id_document_type != null) continue;
				echo CHtml::openTag('div');
				
				echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
				switch ( $item->Id_multimedia_type) {
					case 4:
						echo CHtml::image('images/autocad_resource.png','',array('style'=>'width:25px;'));
						break;
					case 5:
						echo CHtml::image('images/word_resource.png','',array('style'=>'width:25px;'));
						break;
					case 6:
						echo CHtml::image('images/excel_resource.png','',array('style'=>'width:25px;'));
						break;
					case 3:
						echo CHtml::image('images/pdf_resource.png','',array('style'=>'width:25px;'));
						break;
				}
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
	<?php if (User::useTechnicalDocs()):?>
	<div class="review-text-docs">
	<?php
	
		foreach($data->multimedias as $item)
		{
			if($item->Id_multimedia_type < 3 || $item->Id_document_type == null) continue;
			echo CHtml::openTag('div');
		
			echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
			switch ( $item->Id_multimedia_type) {
				case 4:
					echo CHtml::image('images/autocad_resource.png','',array('style'=>'width:25px;'));
					break;
				case 5:
					echo CHtml::image('images/word_resource.png','',array('style'=>'width:25px;'));
					break;
				case 6:
					echo CHtml::image('images/excel_resource.png','',array('style'=>'width:25px;'));
					break;
				case 3:
					echo CHtml::image('images/pdf_resource.png','',array('style'=>'width:25px;'));
					break;
			}
			echo CHtml::closeTag('div');
		
			echo CHtml::link(
			CHtml::encode($item->documentType->name),
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
			echo CHtml::link('Adjuntar Documentos Tecnicos',
				ReviewController::createUrl('AjaxAttachTechDoc',array('id'=>$data->review->Id, 'idNote'=>$data->Id)),
					array('class'=>'review-text-docs'));
		}
		echo CHtml::closeTag('div');
	?>
	</div>
	<?php endif;?>
	</div>
	<div class="singles-notes-confirmations">
		<?php if ($needConfirmation):?>
		<div class="singles-notes-confirmations-title">
			<?php 
			echo CHtml::encode("Estado de confirmaciones:");
			?>
		</div>
		<div class="singles-notes-confirmations-row">
			<?php 
				$criteria=new CDbCriteria;
				
				$criteria->addCondition('t.Id_user_group <> '. User::getCurrentUserGroup()->Id);
				$criteria->addCondition('t.Id_note = '. $data->Id);
				$criteria->addCondition('t.need_confirmation = 1');
				
				$modelUserGroupNote = UserGroupNote::model()->findAll($criteria);
				echo CHtml::openTag('div',array('class'=>'status-permission-row'));
				foreach ($modelUserGroupNote as $item)
				{
					$outOfDate = isset($item)?$item->isOutOfDate():false;
					
					echo CHtml::openTag('div',array('class'=>'review-permission-row'));
						echo CHtml::openTag('div',array('class'=>'status-permission-title'));
						echo $item->userGroup->description.":";					
						echo CHtml::closeTag('div');
						$text = "";
						$color = 'background-color:';
						$date = "";
						if($item->confirmed)
						{
							$text = CHtml::encode("Confirmado");
							$color.='#80e765;color:black;';
							$date = '('. $item->getConfirmDate() .')';
						}
						else if($item->declined)
						{
							$text = CHtml::encode("Declinado");						
							$color.='#ed5656;color:black;';
							$date = '('. $item->getConfirmDate() .')';
						}
						else if($outOfDate)
						{
							$text = CHtml::encode("Auto Conf");
							$color.='#80e765;color:black;';
							$date = '('. $item->getDueDate() .')';
						}
						else
						{
							$text = CHtml::encode("Pendiente");						
							$color.='#AFBAD7;color:black;';
						}
						echo CHtml::openTag('div',array('class'=>'status-permission-data','style'=>$color));
						echo $text;
						echo CHtml::closeTag('div');
						
						echo CHtml::openTag('div',array('class'=>'status-permission-date'));
							echo $date;
						echo CHtml::closeTag('div');
						
					echo CHtml::closeTag('div');
				}
				echo CHtml::closeTag('div');
			?>
		</div>
		<?php endif;?>		
	</div>
	<div id="singleNoteContainer" class="singles-notes-container">
	<?php $notes=$data->notes;?>
	<?php if (!empty($notes)):?>
		<?php 
		foreach($notes as $item)
		{
			echo CHtml::openTag('div',array('class'=>'view-text-note'));
				echo CHtml::openTag('div',array('class'=>'view-text-user'));
					echo CHtml::encode($item->user->name.' '.$item->user->last_name);
				echo CHtml::closeTag('div');
				echo CHtml::openTag('div',array('class'=>'view-text-date'));
					echo $item->creation_date;
				echo CHtml::closeTag('div');
				if($editable||User::isOwnerOf($item))
				{
					echo CHtml::image('images/remove.png','',
					array('id'=>'left_note_'.$item->Id.'_'.$data->Id, 'class'=>'wall-action-remove-small','title'=>'Eliminar'));						
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


