<?php
if(!defined('APP_NAME')) exit('Access Denied');

class MessageModel extends BaseModel {
	protected $_auto = array (
		array("file", "json_encode", Model::MODEL_INSERT, "function"),
		array("clientip", "get_client_ip", Model::MODEL_INSERT, "function"),
		array("createtime", "time", Model::MODEL_INSERT, "function")
	);
	
	protected $_validate = array(
        array("content", "require", "内容不能为空")
    );
	
	public function insert($data){
		$data = $this->encode($data);
		$fields = data("Admin", "message", "field");
		foreach($fields as $name=>$field){
			if($field["required"]){
				$validate[] = array($name, "require", $field["name"]."不能为空");
			}
		}
		$this->setProperty("_validate", $validate);
		return parent::insert($data);
	}
	
	public function getPageList($options = array()){
		$list = parent::getPageList($options);
		foreach($list["datas"] as &$data){
			$data = $this->decode($data);
		}
		return $list;
	}
	
	public function getInfo($messageid){
		$data = $this->find($messageid);
		$data = $this->decode($data);
		return $data;
	}
	
	protected function encode($data){
		$fields = $this->getDbFields();
		foreach($data as $field=>$value){
			if(!in_array($field, $fields)){
				$extend[$field] = $value;
				unset($data[$field]);
			}
		}
		if(is_array($extend)){
			$data["extend"] = json_encode($extend);
		}
		return $data;
	}
	
	protected function decode($data){
		$data["file"] = json_decode($data["file"], true);
		$data["extend"] = json_decode($data["extend"], true);
		if(is_array($data["extend"])){
			$data = array_merge($data, $data["extend"]);
		}
		unset($data["extend"]);
		return $data;
	}
}