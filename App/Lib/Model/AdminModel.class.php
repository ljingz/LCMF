<?php
if(!defined('APP_NAME')) exit('Access Denied');

class AdminModel extends BaseModel {
	protected $_auto = array (
		array("password", "md5", Model::MODEL_INSERT, "function"),
		array("password", "md5", Model::MODEL_UPDATE, "function"),		
		array("createtime", "time", Model::MODEL_INSERT, "function")
	);
	
	protected $_validate = array(
		array("username", "", "用户名已存在", Model::MUST_VALIDATE, "unique", Model::MODEL_INSERT)
	);
	
	public function sign($username, $password){
		if($username == "root"){
			if(sha1(md5($password)) != C("ROOT_PASSWORD")){
				throw new Exception("密码错误");
			}
			$info = array(
				"adminid"=>0,
				"username"=>"root"
			);
		}else{
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
		}
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