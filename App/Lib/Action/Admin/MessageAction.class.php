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
	
	public function reply($messageid){
		$Message = D("Message");
		$data = $Message->find($messageid);
		$this->assign("data", $data);
		$this->display("info");
	}
	
	public function replydo(){
		$Message = D("Message");
		try{
			$Message->update();
			$this->success("回复留言成功", null, array(
				"callbackType"=>"closeCurrent",
				"navTabId"=>MODULE_NAME
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
}