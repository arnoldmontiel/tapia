<div class="view-index">

<?php 
$data = $dataProvider->getData();
$left=true;
$first = true;
foreach ($data as $item){
	if($left)
	{
		$left=false;
		$this->renderPartial('_viewLeft',array('data'=>$item));
	}else 
	{
		$left=true;
		$this->renderPartial('_viewRight',array('data'=>$item,'first'=>$first));		
		$first=false;
	}
}
?>
</div>
