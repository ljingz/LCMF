<?php
if(!defined('APP_NAME')) exit('Access Denied');

class UploadAction extends Action {
	public function index(){
		@set_time_limit(3600);
		import("ORG.Net.UploadFile");
		$config = array(
			"maxSize" => C("UPLOAD_MAX_SIZE") * 1024 * 1024,
			"allowExts" => array_merge(C("UPLOAD_ALLOW_TYPE.IMAGE.extension"), C("UPLOAD_ALLOW_TYPE.FILE.extension")),
			"savePath" =>  APP_ROOT.C("UPLOAD_PATH"),
			"autoSub" => true,
			"subType" => "date",
			"dateFormat" => "Ym"
		);
		mkdirs($config["savePath"]);
		$upload = new UploadFile($config);
		if(!$upload->upload()) {
			$this->error($upload->getErrorMsg());
		}else{
			$info = $upload->getUploadFileInfo();
			$info = array_shift($info);
			$info["savepath"] = C("UPLOAD_PATH").$info["savename"];
			$this->success($info);
		}
	}
	
	protected function success($info){
		$this->ajaxReturn(array(
			"status" => "1",
			"info" => $info
		));
	}
	
	protected function error($info){
		$this->ajaxReturn(array(
			"status" => "0",
			"info" =>  $info
		));
	}
}