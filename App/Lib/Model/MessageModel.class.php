<?php
if(!defined('APP_NAME')) exit('Access Denied');

class MessageModel extends BaseModel {
	protected $_auto = array (
		array("clientip", "get_client_ip", Model::MODEL_INSERT, "function"),
		array("createtime", "time", Model::MODEL_INSERT, "function")
	);
	
	public function insert($data = array()){
		
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
		$data["extend"] = json_encode($data["extend"]);
		return $data;
	}
	
	protected function decode($data){
		$data["extend"] = json_decode($data["extend"], true);
		if(is_array($data["extend"])){
			$data = array_merge($data, $data["extend"]);
		}
		unset($data["extend"]);
		return $data;
	}
}