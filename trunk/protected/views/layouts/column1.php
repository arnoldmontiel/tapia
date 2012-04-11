<?php $this->beginContent('//layouts/main'); ?>
<div class="span-5 first">
	<div class="search-box">
		<div class="search-box-title">
		Etiquetas
		</div>
		<div class="search-box-list">
		<?php
			$modelTag = Tag::model()->findAll();
			$customers = CHtml::listData($modelTag, 'Id', 'description');		
			echo CHtml::checkBoxList('chklist-tag', '', $customers);
		?>
		</div>
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