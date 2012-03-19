<?php echo SHtml::beginForm(); ?>
<?php echo SHtml::activeDropDownList(
    $this->module->getUserModel(),
    $this->module->userid,
    SHtml::listData($users, $this->module->userid, $this->module->username),
    array(
        'id'=>'users-list',
        'size'=>1,
        'class'=>'dropdown',
        'ajax' => array(
            'type'=>'POST',
            'url'=>array('showAssignments'),
            'update'=>'#assignments',
            'beforeSend' => 'function(){
                              $("#assignments").addClass("srbacLoading");
                          }',
            'complete' => 'function(){
                              $("#assignments").removeClass("srbacLoading");
                          }'
        ),
        'prompt'=>Helper::translate('srbac','select user')
    )
); ?>
<?php echo SHtml::endForm(); ?>
