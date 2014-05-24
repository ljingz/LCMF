<?php
if(!defined('APP_NAME')) exit('Access Denied');

class LoginAction extends BaseAction {
	public function index(){
		$this->display();
	}
	
	public function signin($username, $password){
		$Admin = D("Admin");
		try{
			$info = $Admin->sign($username, $password);
			session(C("USER_AUTH_KEY"), $info);
			$this->success("登陆成功");
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
	
	public function signout(){
		session(C("USER_AUTH_KEY"), null);
		$this->redirect("index");
	}
}