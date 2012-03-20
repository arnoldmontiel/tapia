<?php
class jwysiwyg extends CWidget
{
	public $notes;
	public $mode;
	public function init()
	{
		$assetsDir = dirname(__FILE__).'/assets';
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript("jquery");
		
		// Publishing and registering CSS file
		$var = Yii::app()->assetManager->publish($assetsDir);
		//$cs->registerScriptFile($var.'/lib/jquery1.5.js',CClientScript::POS_HEAD);
		$cs->registerScriptFile($var.'/jquery.wysiwyg.js',CClientScript::POS_HEAD);
		$cs->registerScriptFile($var.'/controls/wysiwyg.image.js',CClientScript::POS_HEAD);
		$cs->registerScriptFile($var.'/controls/wysiwyg.link.js',CClientScript::POS_HEAD);
		$cs->registerScriptFile($var.'/controls/wysiwyg.table.js',CClientScript::POS_HEAD);
		$cs->registerCssFile($var.'/jquery.wysiwyg.css');		
		
	}
	public function run()
	{
		if (isset($this->mode))
			$mode = $this->mode;
		else
			$mode = "edit";
		
		$this->render($mode, array('notes'=>$this->notes));
	}
}