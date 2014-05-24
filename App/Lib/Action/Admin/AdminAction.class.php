<?php
if(!defined('APP_NAME')) exit('Access Denied');

class AdminAction extends BaseAction {
	public function index(){
		import("ORG.Util.Page");
		$Admin = D("Admin");
		$this->assign($Admin->getPageList());
		$this->display();
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