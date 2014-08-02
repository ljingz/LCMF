<?php
if(!defined('APP_NAME')) exit('Access Denied');

class SearchAction extends BaseAction {
	public function index(){
    	$Data = D("Data");
    	$kw = $this->_get("kw");
    	$list = $Data->getSearchList($kw);
    	$this->assign($list);
		$this->display();
    }
}