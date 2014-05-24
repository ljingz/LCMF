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
		$this->save(array(
			"adminid"=>$info["adminid"],
			"loginip"=>get_client_ip(),
			"logintime"=>time()
		));
		unset($info["password"]);
		return $info;
	}
	
	public function password($adminid, $password, $npassword){
		$info = $this->find($adminid);
		if(md5($password) != $info["password"]){
			throw new Exception("原密码错误");
		}
		$data = array(
			"adminid"=>$adminid,
			"password"=>md5($npassword)
		);
		if($this->save($data) === false){
			throw new Exception("修改密码失败");
		}
		return true;
	}
}