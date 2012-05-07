<a href="<?php echo ReviewController::createUrl('update',array('id'=>$data->Id))?>" class="index-review-single-link">
<div class="index-review-single" id='review_<?php echo $data->Id; ?>'>
	<div class="index-review-single-container">

	<?php
		$classStyle = 'index-review-summary-unread';
		$modelReviewUser = ReviewUser::model()->findByPk(array('Id_review'=>$data->Id,'username'=>User::getCurrentUser()->username));
		if($modelReviewUser && $modelReviewUser->read)
			$classStyle = 'index-review-summary'; ?>
	
	<div class='<?php echo $classStyle ?>'>
		#<?php echo CHtml::encode($data->review); ?>:
		<?php echo CHtml::encode($data->description); ?>
	</div>
	<div class="index-review-date">
	<?php echo $data->change_date; ?>
		</div>
		
	<?php 
		$tags = $data->tags;
		
		echo CHtml::openTag('div',array('class'=>'index-review-tag-box'));
		foreach($tags as $tag)
		{
			echo CHtml::openTag('div',array('class'=>'index-review-single-tag'));
			echo $tag->description;
			echo CHtml::closeTag('div');
		}
		echo CHtml::closeTag('div');
	?>
	<?php	
		echo CHtml::openTag('div',array('class'=>'index-review-priority-box'));
		echo $data->priority->description;
		echo CHtml::closeTag('div');
	?>

	<?php	
		echo CHtml::openTag('div',array('class'=>'index-review-type-box'));
		echo $data->reviewType->description;
		echo CHtml::closeTag('div');
	?>
		<?php 
		$multimedia = new Multimedia;
		$multimedia->Id_review = $data->Id;
		$multimedia->Id_multimedia_type = 1;
		$dataProvider = $multimedia->search();
		$multimedias = $dataProvider->data;
		
		echo CHtml::openTag('div',array('class'=>'index-review-resource-box'));
		if(sizeof($multimedias))
		{
			echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
			echo CHtml::image('images/image_resource.png','',array('style'=>'width:25px;'));
			echo CHtml::closeTag('div');
		}
		
		$multimedia->Id_review = $data->Id;
		$multimedia->Id_multimedia_type = 2;
		$dataProvider = $multimedia->search();
		$multimedias = $dataProvider->data;
		if(sizeof($multimedias))
		{
			echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
			echo CHtml::image('images/video_resource.png','',array('style'=>'width:25px;'));
			echo CHtml::closeTag('div');
		}
		
		$multimedia->Id_review = $data->Id;
		$multimedia->Id_multimedia_type = 3;
		$dataProvider = $multimedia->search();
		$multimedias = $dataProvider->data;
		if(sizeof($multimedias))
		{
			echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
			echo CHtml::image('images/pdf_resource.png','',array('style'=>'width:25px;'));
			echo CHtml::closeTag('div');
		}
		
		$multimedia->Id_review = $data->Id;
		$multimedia->Id_multimedia_type = 4;
		$dataProvider = $multimedia->search();
		$multimedias = $dataProvider->data;
		if(sizeof($multimedias))
		{
			echo CHtml::openTag('div',array('class'=>'index-review-single-resource'));
			echo CHtml::image('images/autocad_resource.png','',array('style'=>'width:25px;'));
			echo CHtml::closeTag('div');
		}
		echo CHtml::closeTag('div');
		?>
	</div>
	<?php 		
		echo CHtml::openTag('div',array('class'=>'index-users'));
		$first = true;
		foreach ($data->reviewUsers as $item)
		{			
			echo CHtml::openTag('div',array('class'=>$item->read?'index-text-user-readed':'index-text-user'));
			$name = '';
			if(!$first)
				$name.=', ';
			if($first)
				$first = false;
			$name.=$item->user->name.' '.$item->user->last_name;
			echo $name;
			echo CHtml::closeTag('div');
		}				
		echo CHtml::closeTag('div');
		?>
</div>
</a>
