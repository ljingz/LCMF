<?php
if(!defined('APP_NAME')) exit('Access Denied');

class FormatDateWidget extends Widget {
	public function render($data){
		return date("Y-m-d", $data["createtime"]);
	}
}