<?php
if(!defined('APP_NAME')) exit('Access Denied');

class TagLibTool extends TagLib {
	protected $tags = array(
		'homepage'=>array(),
		'favorite'=>array()
	);
	
	public function _homepage($attr, $content){
		$parseStr = '<a href="#" onclick="this.style.behavior=\'url(#default#homepage)\';this.setHomePage(\'http://<?php echo $_SERVER[\'HTTP_HOST\'];?>__ROOT__\');">';
		$parseStr .= $content;
		$parseStr .= '</a>';
		return $parseStr;
	}
	
	public function _favorite($attr, $content){
		$parseStr = '<a href="#" onclick="window.external.AddFavorite(\'http://<?php echo $_SERVER[\'HTTP_HOST\'];?>\',\'__PAGE__TITLE__\')">';
		$parseStr .= $content;
		$parseStr .= '</a>';
		return $parseStr;
	}
}