<?php
if(!defined('APP_NAME')) exit('Access Denied');

//加载扩展函数库
load("extend");

/**
 * 获取自定义数据,自定义数据目录为/Data
 * 
 * @param string $filename
 * @param string $keys 参数个数无限制
 * @return 
 * 
 * @example data("module", "Setting", "name")
 * */
function data(){
	static $filedatas = array();
	$params = func_get_args();
	$filename = sprintf("%sData/%s/%s.php", APP_PATH, GROUP_NAME, array_shift($params));
	if(!file_exists($filename)){
		throw new Exception("The File Is Not Exist");
	}
	if(array_key_exists($filename, $filedatas)){
		$filedata = $filedatas[$filename];
	}else{
		$filedata = $filedatas[$filename] = require($filename);
	}
	foreach($params as $param){
		$filedata = $filedata[$param];
	}
	return $filedata;
}

/**
 * 默认参数过滤函数
 * */
function requestFilterHandler($data){
	if(is_array($data)){
		return array_map("requestFilterHandler", $data);
	}else{
		return htmlspecialchars(trim($data));
	}
}

/**
 * 计算整个目录文件大小
 * 
 * @param string $dir
 * */
function getDirSize($dir){
	$handle = opendir($dir);
	while (false !== ($file = readdir($handle))){
		if($file != "." && $file != ".."){
			$fullpath = $dir."/".$file;
			if(is_dir($fullpath)){
				$size += getDirSize($fullpath);
			}else{
				$size += filesize($fullpath);
			}
		}
	}
	closedir($handle);
	return $size;
}