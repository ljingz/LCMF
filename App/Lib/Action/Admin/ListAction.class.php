<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ListAction extends BaseAction {
	public function index($columnid){
		$Column = D("Column");
		$Data = D("Data");
		$querys = $this->_get();
		$table = $Column->getTable($columnid);
		foreach($table["field"] as $field){
			$fields[] = $field["name"];
		}
		foreach($querys as $name=>$value){
			if(in_array($name, $fields) && strlen($value) > 0){
				$options["query"][$name] = array("like", "%".$value."%");
			}
		}
		$list = $Data->getPageList($columnid, $options);
		$this->assign("table", $table);
		$this->assign($list);
		$this->display();
	}
	
	public function add($columnid){
		$Column = D("Column");
		$table = $Column->getTable($columnid);
		$this->assign("table", $table);
		$this->display("info");
	}
	
	public function insert(){
		$Data = D("Data");
		$data = $this->_post();
		try{
			$Data->insert($data);
			$this->success("添加信息成功", null, array(
				"callbackType"=>"closeCurrent",
				"navTabId"=>implode("-", array(
					"Data",
					$data["columnid"]
				))
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
	
	public function edit($columnid, $dataid){
		$Column = D("Column");
		$Data = D("Data");
		$table = $Column->getTable($columnid);
		$data = $Data->getInfo($columnid, $dataid);
		$this->assign("table", $table);
		$this->assign("data", $data);
		$this->display("info");
	}
	
	public function update(){
		$Data = D("Data");
		$data = $this->_post();
		try{
			$Data->update($data);
			$this->success("编辑信息成功", null, array(
				"callbackType"=>"closeCurrent",
				"navTabId"=>implode("-", array(
					"Data",
					$data["columnid"]
				))
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
	
	public function delete($columnid, $dataid){
		$Data = D("Data");
		$query = array("columnid"=>$columnid, "dataid"=>array("in", $dataid));
		if($Data->where($query)->delete() === false){
			$this->error("删除信息失败");
		}else{
			$this->success("删除信息成功", null, array(
				"navTabId"=>implode("-", array(
					"Data",
					$columnid
				))
			));
		}
	}
	
	public function _empty($name){
		$Data = D("Data");
		$columnid = $this->_get("columnid");
		$value = $this->_get("value");
		$dataid = $this->_post("dataid");
		if(array_key_exists($name, data("table", "action"))){
			try{
				$Data->setValue($columnid, $dataid, array(
					$name=>$value
				));
				$this->success("操作信息成功", null, array(
					"navTabId"=>implode("-", array(
						"Data",
						$columnid
					))
				));
			}catch(Exception $ex){
				$this->error($ex->getMessage());
			}
		}
	}
}