<?php
if(!defined('APP_NAME')) exit('Access Denied');

class FormatSizeWidget extends Widget {
	public $unit = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB", "NB", "DB");
	
	public function render($data){
		for($i=0;$i<count($this->unit);$i++){
			if($data["size"]<1024){
				return sprintf("%s %s", round($data["size"], 2), $this->unit[$i]);
			}
			$data["size"] = $data["size"]/1024;
		}
	}
}