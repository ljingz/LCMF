<?php
if(!defined('APP_NAME')) exit('Access Denied');

class TableAction extends BaseAction {
	public function index(){
		$Table = D("Table");
		$title = $this->_get("title");
		if(!empty($title)){
			$options["query"]["title|name"] = array("like", sprintf("%%%s%%", $title));
		}
		$datas = $Table->getList($options);
		$this->assign("datas", $datas);
		$this->display();
	}
	
	public function add(){
		$this->display("info");
	}
	
	public function insert(){
		$Admin = D("Admin");
		try{
			$Admin->insert();
			$this->success("添加用户成功", null, array(
				"callbackType"=>"closeCurrent",
				"navTabId"=>MODULE_NAME
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
	
	public function edit($adminid){
		$Admin = D("Admin");
		$data = $Admin->find($adminid);
		$this->assign("data", $data);
		$this->display("info");
	}
	
	public function update(){
		$Admin = D("Admin");
		try{
			$Admin->update();
			$this->success("编辑用户成功", null, array(
				"callbackType"=>"closeCurrent",
				"navTabId"=>MODULE_NAME
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
	
	public function delete($adminid){
		$Admin = D("Admin");
		if($Admin->delete($adminid) === false){
			$this->error("删除用户失败");
		}else{
			$this->success("删除用户成功", null, array(
				"navTabId"=>MODULE_NAME
			));
		}
	}
	
	public function password($password, $npassword){
		$Admin = D("Admin");
		try{
			$Admin->password($this->loginUser["adminid"], $password, $npassword);
			$this->success("密码修改成功", null, array(
				"navTabId"=>"profile"
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
}