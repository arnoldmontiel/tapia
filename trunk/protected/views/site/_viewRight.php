<?php 
Yii::app()->clientScript->registerScript(__CLASS__.'#site_view'.$data->Id, "
});

");

?>
<div class="view-single" >
	view <?php echo $data->Id;?> order <?php echo $data->index_order;?>
	<div class="text-note"><?php echo $data->note->note;?></div>		
	<div class="view-dialog-right" ></div>
</div>

