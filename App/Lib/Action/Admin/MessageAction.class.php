<?php
if(!defined('APP_NAME')) exit('Access Denied');

class MessageAction extends BaseAction {
	public function index(){
		$Message = D("Message");
		$keyword = $this->_get("keyword");
		if(!empty($keyword)){
			$field = "name|address|email|phone|clientip|content";
			$options["query"][$field] = array("like", sprintf("%%%s%%", $keyword));
		}
		$list = $Message->getPageList($options);
		$this->assign($list);
		$this->display();
	}
	
	public function delete($messageid){
		$Message = D("Message");
		if($Message->where(array("messageid"=>array("in", $messageid)))->delete() === false){
			$this->error("删除留言失败");
		}else{
			$this->success("删除留言成功", null, array(
				"navTabId"=>"Message"
			));
		}
	}
	
	public function view($messageid){
		$Message = D("Message");
		$data = $Message->getInfo($messageid);
		$this->assign("data", $data);
		$this->display();
	}
	
	public function file($messageid){
		import("ORG.Net.Http");
		$Message = D("Message");
		$data = $Message->getInfo($messageid);
		$Http = new Http();
		$Http->download(APP_ROOT.$data["file"]["savepath"], $data["file"]["name"]);
	}
	
	public function reply(){
		$Message = D("Message");
		try{
			$Message->update();
			$this->success("回复留言成功", null, array(
				"callbackType"=>"closeCurrent"
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
}