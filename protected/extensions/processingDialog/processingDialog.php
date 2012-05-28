<?php
class processingDialog extends CWidget
{
	public $buttons = array();
	public $idDialog;
	public $assets;
	public function init()
	{
		$assetsDir = dirname(__FILE__).'/assets';
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript("jquery");

		// Publishing and registering CSS file
		$var = Yii::app()->assetManager->publish($assetsDir);
		$this->assets = $var;
		//$cs->registerScriptFile($var.'/highslide.js',CClientScript::POS_HEAD);
		$cs->registerScriptFile($var.'/processingDialog.js',CClientScript::POS_HEAD);
		//$cs->registerScriptFile($var.'/highslide-exe.js',CClientScript::POS_HEAD);
		$cs->registerCssFile($var.'/processingDialog.css');

	}
	public function run()
	{
		if($this->id != null){
			$script= '';
			foreach ($this->buttons as $item)
			{
				$script.= "$('#".$item."').click(
						function(index, item){
					$('#".$this->idDialog."').dialog('open');
				}
				);
				";
				
			}
			Yii::app()->clientScript->registerScript(__CLASS__.'#P_D'.$this->id, $script);
			$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
				'id'=>$this->idDialog,
				// additional javascript options for the dialog plugin
				'options'=>array(
				'autoOpen'=>false,
				'closeOnEscape'=>false,
				'modal'=>true,
				'resizable'=>false,
				'dialogClass'=>' dialog-no-body dialog-no-title',
				'height'=>60,
				'width'=>60,
				),
			));
		 
			echo CHtml::image($this->assets.'/ajax-loader.gif');
 
			$this->endWidget('zii.widgets.jui.CJuiDialog');
		}
	}
}