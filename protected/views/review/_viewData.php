<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#review-view-data'.$data->Id, "

");
$canDoFeeback = $dataUserGroupNote->can_feedback;
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
				echo CHtml::decode('De : ');
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
						$first = false;;								
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
				echo CHtml::image('images/remove.png','',
					array('id'=>'left_note_'.$item->Id.'_'.$data->Id, 'class'=>'wall-action-remove-small','title'=>'Remove'));
				echo CHtml::openTag('p',array('class'=>'single-formated-text'));
					echo $item->note;
				echo CHtml::closeTag('p');
			echo CHtml::closeTag('div');
		}
		?>		
	<?php endif?>
	</div>
	<div class="review-text-note-add">
	<?php if($canDoFeeback):?>

		<div id='create_note_<?php echo $data->Id?>' class="review-create-note div-hidden">
			Grabar
		</div>
		<div id='create_note_cancel_<?php echo $data->Id?>' class="review-create-note-cancel div-hidden">
			Cancelar
		</div>
				
		<textarea id="note_<?php echo $data->Id?>" class="review-action-add-note" placeholder='Escriba una nota...'></textarea>
		<?php endif;?>
	</div>
</div>


