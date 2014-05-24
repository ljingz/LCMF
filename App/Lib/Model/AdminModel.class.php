<?php
if(!defined('APP_NAME')) exit('Access Denied');

class AdminModel extends BaseModel {
	public function getInfo($query){
		
	}
	
	public function sign($username, $password){
		$info = $this->where(array("username"=>$username))->find();
		if(empty($info)){
			throw new Exception("用户名不存在");
		}
		if(md5($password) != $info["password"]){
			throw new Exception("密码错误");
		}
		$this->loginRecord($info["adminid"]);
		unset($info["password"]);
		return $info;
	}
	
	protected function loginRecord($adminid){
		$this->save(array(
			"adminid"=>$adminid,
			"loginip"=>get_client_ip(),
			"logintime"=>time()
		));
	}
}