<?php $this->beginContent('//layouts/main'); ?>

<div class="span-5 first">
	<?php if($this->modelTag):?>
	<div class="search-box">
		<div class="search-box-title">
		Etiquetas
		</div>
		<div class="search-box-list">
		<?php
 			$modelTags = Tag::model()->findAll();
 			$checkTags = CHtml::listData($modelTags, 'Id', 'description');		
 			
 			$checked = array();
 			foreach($this->modelTag->tags as $tag)
 			{
 				$checked[] = $tag->Id;
 			}
 			echo CHtml::checkBoxList('chklist-tag-review', $checked, $checkTags);
		?>
		</div>
	</div>
<?php endif?>
	<div class="search-box">
		<div class="search-box-title">
		Etiquetas
		</div>
		<div class="search-box-list">
		<?php
			$modelTags = Tag::model()->findAll();
			$checkTags = CHtml::listData($modelTags, 'Id', 'description');		
			echo CHtml::checkBoxList('chklist-tag', '', $checkTags);
		?>
		</div>
	</div>
	
	<div class="search-box">
		<div class="search-box-title">
		Documents
		</div>
		<div class="search-box-list">
		<?php
			$modelType = MultimediaType::model()->findAll();
			$checkType = CHtml::listData($modelType, 'Id', 'description');		
			echo CHtml::checkBoxList('chklist-type', '', $checkType);
		?>
		</div>
	</div>
	
	<div class="search-box">
		<?php
			echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'filter-btn-box'));	
			echo CHtml::link('Filtrar','',array('id'=>'btn-filter','class'=>'submit-btn'));
			echo CHtml::closeTag('div');	
		?>
	</div>		
</div>


		
<div id="content">
	<?php echo $content; ?>
</div><!-- content -->
<?php $this->endContent(); ?>