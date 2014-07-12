<?php
if(!defined('APP_NAME')) exit('Access Denied');

class FormatDateTimeWidget extends Widget {
	public function render($data){
		return date("Y-m-d H:i:s", $data["createtime"]);
	}
}