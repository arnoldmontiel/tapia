<?php $this->beginContent('//layouts/main'); ?>
<div class="span-5 first">
	<div class="search-box">
		<div class="search-box-title">
		Etiquetas
		</div>
		<div class="search-box-list">
		<?php
			$modelTag = Tag::model()->findAll();
			$checkTags = CHtml::listData($modelTag, 'Id', 'description');		
			echo CHtml::checkBoxList('chklist-tag', '', $checkTags);
		?>
		</div>
		<?php
			echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'filter-btn-box'));	
			echo CHtml::link('Filtrar','',array('id'=>'btn-filter-tag','class'=>'submit-btn'));
			echo CHtml::closeTag('div');	
		?>		
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
		<?php
			echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'filter-btn-box-type'));	
			echo CHtml::link('Filtrar','',array('id'=>'btn-filter-type','class'=>'submit-btn'));
			echo CHtml::closeTag('div');	
		?>		
	</div>
</div>

<div id="content">
	<?php echo $content; ?>
</div><!-- content -->
<?php $this->endContent(); ?>