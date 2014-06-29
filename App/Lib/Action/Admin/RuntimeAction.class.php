<?php
if(!defined('APP_NAME')) exit('Access Denied');

class RuntimeAction extends BaseAction {
	protected $path = RUNTIME_PATH;
	
	public function _initialize(){
		parent::_initialize();
		//文件路径
		$path = $this->_get("path");
		$path = str_replace("\\", "/", $path);
		$path = str_replace("../", "", $path);
		$path = array_filter(explode("/", $path));
		if(!empty($path)){
			$this->path .= implode("/", $path);
			if(is_dir($this->path)){
				$this->path .= "/";
			}
		}
	}
	
    public function index(){
    	$handle = opendir($this->path);
    	if($handle){
	    	while(($file = readdir($handle)) !== false){
	    		if($file != "." && $file != ".."){
	    			$stat = stat($this->path.$file);
	    			$datas[$file] = array(
	    				"name"=>$file,
	    				"path"=>implode("/", array_filter(array($this->_get("path"), $file))),
	    				"type"=>is_dir($this->path.$file)?"dir":"file",
	    				"size"=>$stat["size"],
	    				"mtime"=>$stat["mtime"],
	    				"group"=>$stat["gid"],
	    				"user"=>$stat["uid"]
	    			);
	    		}
	    	}
	    	ksort($datas);
    	}
    	$this->assign("datas", $datas);
    	$this->display();
    }
    
    public function delete(){
    	if(rmrf($this->path) === false){
    		$this->error("删除失败");
    	}else{
    		$this->success("删除成功", null, array(
				"navTabId"=>MODULE_NAME
			));
    	}
    }
    
    public function view(){
    	$listRows = 500;
    	$buffer = array();
    	if(is_file($this->path)){
    		$handle = fopen($this->path, "r");
			while(!feof($handle)) {
			    $buffer[] = trim(fgets($handle, 4096));
			}
			fclose($handle);
			$totalRows = count($buffer);
			if($totalRows > $listRows){
				$buffer = array_slice($buffer, $totalRows - $listRows);
			}
    	}
    	$this->assign("listRows", $listRows);
    	$this->assign("totalRows", $totalRows);
    	$this->assign("content", implode("\r\n", $buffer));
    	$this->display();
    }
}