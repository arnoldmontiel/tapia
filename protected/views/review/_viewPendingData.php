<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#review-view-pending-data'.$data->Id, "

");
?>

<div class="review-single-view" id="<?php echo $data->Id?>" >
	<div class="view-text-date"><?php echo $data->creation_date;?></div>
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
		
			echo CHtml::openTag('div', array('id'=>'loco'.$data->Id));
			echo "aaaaaaaaaaaaaaaaaaa";
			echo CHtml::closeTag('div');
			
			
			echo CHtml::openTag('div', array('id'=>'publicArea_'.$data->Id, 'style'=>'width:500px;'));
			
			foreach($modelUserGroup as $item)
			{
				echo CHtml::openTag('div', array('id'=>'userGroup_'.$item->Id));
					echo CHtml::openTag('div', array('style'=>'display: inline-block; width:50%;'));
						echo CHtml::label($item->description, 'chkUserGroup');
						echo CHtml::checkBox('chkUserGroup','',array('value'=>$item->Id));
					echo CHtml::closeTag('div');
					echo CHtml::openTag('div', array('style'=>'display: inline-block;'));
						echo CHtml::checkBox('chkAddressed','',array('value'=>$item->Id));
					echo CHtml::closeTag('div');
					echo CHtml::openTag('div', array('style'=>'display: inline-block;'));
						echo CHtml::checkBox('chkCanFeedback','',array('value'=>$item->Id));
					echo CHtml::closeTag('div');
					echo CHtml::openTag('div', array('style'=>'display: inline-block;'));
						echo CHtml::checkBox('chkNeedConfirmation','',array('value'=>$item->Id));
					echo CHtml::closeTag('div');
				echo CHtml::closeTag('div');
			}
			
			echo CHtml::closeTag('div');
		?>
	</div>
</div>

