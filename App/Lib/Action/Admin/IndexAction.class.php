<?php
if(!defined('APP_NAME')) exit('Access Denied');

class IndexAction extends BaseAction {
	public function index(){
		$this->display();
	}
	
	public function getRootSize(){
		echo byte_format(getDirSize(APP_ROOT));
	}
}