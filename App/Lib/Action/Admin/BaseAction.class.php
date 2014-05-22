<?php
if(!defined('APP_NAME')) exit('Access Denied');

class BaseAction extends Action {
	protected $loginUser = array();
	
	public function _initialize(){
		//解决第三方登录cookie问题
		header("P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
		
		//验证登录
		/*
		if(!in_array(GROUP_NAME, C("NOT_AUTH_GROUP"))){
			session("[start]");
			$this->loginUser = session(C("USER_AUTH_KEY"));
			$this->assign("loginUser", $this->loginUser);
			if(!in_array(MODULE_NAME, C("NOT_AUTH_MODULE.".GROUP_NAME))){
				if(empty($this->loginUser)){
					if($this->isAjax()){
						$this->_error("未登录");
					}else{
						$this->redirect("/Admin/Login");
					}
				}
			}
		}
		*/
		//参数解析
		$params = $this->_get();
		if(array_key_exists(C("VAR_URL_PARAMS"), $params)){
			unset($params[C("VAR_URL_PARAMS")]);
		}
		$this->assign("params", json_encode(new ArrayObject($params)));
	}
	
	protected function _success($msg, $data=null){
		if($data != null && !is_array($data)){
			$data["url"] = U($data);
		}
		header("Content-Type:text/html; charset=utf-8");
        exit(json_encode(array("status"=>"tip_success", "info"=>$msg, "data"=>$data)));
	}
	
	protected function _error($msg, $data=null){
		if($data != null && !is_array($data)){
			$data["url"] = U($data);
		}
		header("Content-Type:text/html; charset=utf-8");
        exit(json_encode(array("status"=>"tip_error", "info"=>$msg, "data"=>$data)));
	}
}