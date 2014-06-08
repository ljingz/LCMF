<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ContentAction extends BaseAction {
	public function index($columnid){
		$Column = D("Column");
		$Data = D("Data");
		$table = $Column->getTable($columnid);
		$data = $Data->getInfo($columnid);
		$this->assign("table", $table);
		$this->assign("data", $data);
		$this->display();
	}
	
	public function insert(){
		$Data = D("Data");
		$data = $this->_post();
		try{
			$Data->insert($data);
			$this->success("添加信息成功", null, array(
				"navTabId"=>implode("-", array(
					"Data",
					$data["columnid"]
				))
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
	
	public function update(){
		$Data = D("Data");
		$data = $this->_post();
		try{
			$Data->update($data);
			$this->success("编辑信息成功", null, array(
				"navTabId"=>implode("-", array(
					"Data",
					$data["columnid"]
				))
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
}