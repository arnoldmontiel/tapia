<?php
class Highslide extends CWidget
{
	public $smallImage;
	public $image;
	public $caption;
	protected $graphics;
	public function init()
	{
		$assetsDir = dirname(__FILE__).'/assets';
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript("jquery");
		
	
		
// 		// Publishing and registering JavaScript file
// 		//$cs->registerScriptFile(Yii::app()->assetManager->publish($assetsDir.'/highslide.js'),CClientScript::POS_HEAD);
// 		$cs->registerScriptFile(Yii::app()->assetManager->publish($assetsDir.'/highslide-with-gallery.js'),CClientScript::POS_HEAD);
		
// 		// Publishing and registering CSS file
// 		$cs->registerCssFile(Yii::app()->assetManager->publish($assetsDir.'/highslide-styles.css'));
// 		$this->graphics = Yii::app()->assetManager->publish($assetsDir.'/graphics');
		
		
		// Publishing and registering CSS file
		$var = Yii::app()->assetManager->publish($assetsDir);
		$cs->registerScriptFile($var.'/highslide.js',CClientScript::POS_HEAD);
		$cs->registerCssFile($var.'/highslide.css');
		$this->graphics = $var.'/graphics';
		
	}
	public function run()
	{
		if($this->id != null){
			$this->render("body", array(
						'image'=>$this->image,
						'smallImage'=>$this->smallImage,
						'camptio'=>$this->caption,
			));
		}
		
	}
}