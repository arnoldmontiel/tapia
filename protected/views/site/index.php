<div class="view-index">

<?php 
$data = $dataProvider->getData();
$left=true;
foreach ($data as $item){
	if($left)
	{
		$left=false;
		$this->renderPartial('_viewLeft',array('data'=>$item));
	}else 
	{
		$left=true;
		$this->renderPartial('_viewRight',array('data'=>$item));		
	}
}
?>
</div>
