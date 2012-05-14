<?php
class uploadifyWidget extends CInputWidget {
    public $mult;
    private $assets;
    public $action;
    public $idReview;
    public $idAlbum;
    public $AjaxRemoveImageURL;
    public $AjaxAddImageDescriptionURL;
    
    public function init()
    {
    	$assetsDir = dirname(__FILE__).'/assets';
    	$cs = Yii::app()->getClientScript();
    	$cs->registerCoreScript("jquery");
    
    	// Publishing and registering CSS file
    	$var = Yii::app()->assetManager->publish($assetsDir);
    	$this->assets = $var;
    	//$cs->registerScriptFile($var.'/highslide.js',CClientScript::POS_HEAD);
    	$cs->registerScriptFile($var.'/jquery.uploadify-3.1.js',CClientScript::POS_HEAD);
    	//$cs->registerScriptFile($var.'/highslide-exe.js',CClientScript::POS_HEAD);
    	$cs->registerCssFile($var.'/uploadify.css');
    }
    
    public function run() {
        $controller=$this->controller;
        $action=$controller->action;
        $this->render('uploadifyWidget',
        	array('mult'=>$this->mult,
        		'assets'=>$this->assets,
        		'action'=>$this->action,
        		'idReview'=>$this->idReview,
        		'idAlbum'=>$this->idAlbum,
        		'AjaxRemoveImageURL'=>$this->AjaxRemoveImageURL,
        		'AjaxAddImageDescriptionURL'=>$this->AjaxAddImageDescriptionURL,
        )
        );
    }
}