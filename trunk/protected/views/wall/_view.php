<?php 
	if($data->index_order%2)
	{?>
		<div class="view-single" >
				view <?php echo $data->Id;?> order <?php echo $data->index_order;?>
			<div class="text-note"><?php echo $data->note->note;?></div>		
			<div class="view-dialog-right" ></div>
		</div>
<?php }else {?>
		<div class="view-single" >
		view <?php echo $data->Id;?> order <?php echo $data->index_order;?>
			<div class="text-note"><?php echo $data->note->note;?></div>		
			<div class="view-dialog-left"></div>
		</div>
<?php } ?>