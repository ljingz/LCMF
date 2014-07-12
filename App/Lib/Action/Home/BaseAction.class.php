<?php
if(!defined('APP_NAME')) exit('Access Denied');

class BaseAction extends Action {
	public function _initialize(){
		
	}
	
	public function lists($columnid){
    	$Data = D("Data");
    	$kw = $this->_get("kw");
    	if(!empty($kw)){
    		$options["query"]["title"] = array("like", "%".$kw."%");
    	}
    	$list = $Data->getPageList($columnid, $options);
    	$this->assign($list);
		$this->display();
    }
    
    public function view($columnid, $dataid=""){
    	$Data = D("Data");
    	$data = $Data->getInfo($columnid, $dataid);
    	$this->assign("data", $data);
    	$this->display();
    }
}