<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#review-view-data'.$data->Id, "
");
$needConfirmation = $dataUserGroupNote->need_confirmation;
$confirmed = $dataUserGroupNote->confirmed;
$declined = $dataUserGroupNote->declined;
$isOwner = User::isOwnerOf($data); 
?>

<div class="review-single-view" id="<?php echo $data->Id?>" >
	<div class="view-note-title">
	
		<div class="wall-action-edit-main-title" >
		<p class="single-formated-text"><?php echo $data->title;?></p>
		</div>
	</div>
	<div class="view-text-date"><?php echo $data->change_date;?></div>
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
		 		}
		 	?>
			</div>
		</div>
			<div class="wall-action-edit-main-note" >
			<p class="single-formated-text"><?php echo $data->note;?></p>
			</div>
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
		echo CHtml::closeTag('div');
	?>
	</div>
	<?php endif;?>
	</div>
	<div class="singles-notes-confirmations">
		<?php if ($needConfirmation || $isOwner):?>
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
						else 
						{
							$text = CHtml::encode("Auto Conf");
							$color.='#80e765;color:black;';
							$date = '('. $item->getDueDate() .')';
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
				echo CHtml::openTag('p',array('class'=>'single-formated-text'));
					echo $item->note;
				echo CHtml::closeTag('p');
			echo CHtml::closeTag('div');
		}
		?>		
	<?php endif?>
	</div>
</div>


