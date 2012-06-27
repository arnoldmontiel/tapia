<h1>Auditoria de Logueo</h1>

<div class="search-form" style="display:none">
</div><!-- search-form -->

<?php 
$userGroup = UserGroup::model()->findAll();
$userGroupList = CHtml::listData($userGroup,'Id','description');

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'audit-login-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'username',
		'date',
		array(
 			'name'=>'user_group_desc',
			'value'=>'$data->user->userGroup->description',
			'filter'=>$userGroupList,
		),
		array(
 			'name'=>'user_name',
			'value'=>'$data->user->name',
		),
		array(
 			'name'=>'user_last_name',
			'value'=>'$data->user->last_name',
		),
	),
)); ?>
