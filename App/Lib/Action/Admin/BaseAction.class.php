<?php
if(!defined('APP_NAME')) exit('Access Denied');

class BaseAction extends Action {
	protected $loginUser = array();
	
	public function _initialize(){
		session("[start]");
		//解决第三方登录cookie问题
		header("P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
		
		//验证登录
		if(!in_array(GROUP_NAME, C("NOT_AUTH_GROUP"))){
			$this->loginUser = session(C("USER_AUTH_KEY"));
			$this->assign("loginUser", $this->loginUser);
			if(!in_array(MODULE_NAME, C("NOT_AUTH_MODULE.".GROUP_NAME))){
				if(!isset($this->loginUser["adminid"])){
					if($this->isAjax()){
						$this->error("会话超时，请重新登陆。", null, array("statusCode"=>-1));
					}else{
						$this->redirect("/Admin/Login");
					}
				}
			}
		}
		
		//设置每页显示数据条数
		$numPerPage = $this->_request("numPerPage");
		if(!empty($numPerPage)){
			C("NUM_PER_PAGE", $numPerPage);
		}
	}
}