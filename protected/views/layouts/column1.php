<?php $this->beginContent('//layouts/main'); ?>

<div class="span-5 first">
	<?php if($this->modelTag):?>			
		<div class="search-box">
			<div class="search-box-title">
			Etiquetas
			</div>
			<div class="search-box-list">
			<?php
				if(User::isAdministartor())
				{
					$modelTags = Tag::model()->findAll();
					$checkTags = CHtml::listData($modelTags, 'Id', 'description');
						
					$checked = array();
					foreach($this->modelTag->tags as $tag)
					{
						$checked[] = $tag->Id;
					}
					echo CHtml::checkBoxList('chklist-tag-review', $checked, $checkTags);						
				}else{					
					
					echo CHtml::openTag('div',array('class'=>'review-tag-containier')); 
					foreach($this->modelTag->tags as $tag)
					{
						echo CHtml::openTag('div',array('class'=>'review-single-tag')); 
						echo CHtml::encode($tag->description);
						echo CHtml::closeTag('div');
					}
					echo CHtml::closeTag('div');
				}
			?>
			</div>
		</div>
	<?php endif?>
	<?php if($this->showFilter):?>
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
			Documentos
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
			<div class="search-box-title">
			Tipo de agrupador
			</div>
			<div class="search-box-list">
			<?php
				$modelReviewType = ReviewType::model()->findAll();
				$checkReviewType = CHtml::listData($modelReviewType, 'Id', 'description');		
				echo CHtml::checkBoxList('chklist-reviewType', '', $checkReviewType);
			?>
			</div>
		</div>
		
		<div class="search-box">
			<div class="search-box-title">
				Fecha de creacion
			</div>
			<div class="search-box-list">
				Desde
				<?php 
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					    'name'=>'dateFrom',
					    // additional javascript options for the date picker plugin
					    'options'=>array(
					        'showAnim'=>'fold',
							'dateFormat'=>'yy-mm-dd',
							'changeYear'=>true,
							'yearRange'=>'1999:2020',
							'beforeShow'=>"js:function() {
				                    $('.ui-datepicker').css('font-size', '0.8em');
				                    $('.ui-datepicker').css('z-index', parseInt($(this).parents('.ui-dialog').css('z-index'))+1);
				                }",
					    ),
					    'htmlOptions'=>array(
					        'style'=>'height:20px;'
					    ),
					));
				?>
				Hasta
				<?php 
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					    'name'=>'dateTo',
						
					    // additional javascript options for the date picker plugin
					    'options'=>array(
					        'showAnim'=>'fold',
							'dateFormat'=>'yy-mm-dd',
							'changeYear'=>true,
							'yearRange'=>'1999:2020',
							'beforeShow'=>"js:function() {
					                    $('.ui-datepicker').css('font-size', '0.8em');
					                    $('.ui-datepicker').css('z-index', parseInt($(this).parents('.ui-dialog').css('z-index'))+1);
					                }",
					    ),
					    'htmlOptions'=>array(
					        'style'=>'height:20px;'
					    ),
					));
				?>
			</div>
		</div>
		
		<div class="search-box">
			<?php
				echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'filter-btn-box'));	
				echo CHtml::link('Filtrar','',array('id'=>'btn-filter','class'=>'submit-btn'));
				echo CHtml::closeTag('div');	
				echo '<br>';
				echo CHtml::openTag('div',array('class'=>'wall-action-box-btn','id'=>'clear-filter-btn-box'));
				echo CHtml::link('Limpiar filtros','',array('id'=>'btn-clear-filter','class'=>'submit-btn'));
				echo CHtml::closeTag('div');
			?>
		</div>		
	<?php endif?>
</div>


		
<div id="content">
	<?php echo $content; ?>
</div><!-- content -->
<?php $this->endContent(); ?>