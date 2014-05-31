<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnAction extends BaseAction {
	public function index(){
		$Column = D("Column");
		$Table = D("Table");
		$this->assign("tree", $Column->getTree());
		$this->assign("tables", $Table->getMap());
		$this->display();
	}
	
	public function add(){
		$this->display("info");
	}
	
	public function save(){
		$Column = D("Column");
		$columns = $this->_post("column");
		$Column->startTrans();
		try{
			foreach($columns as $columnid=>$column){
				if(array_key_exists($column["parentid"], $tmp)){
					$column["parentid"] = $tmp[$column["parentid"]];
				}
				if($Column->exists($columnid)){
					$Column->update($column);
				}else{
					$tmp[$columnid] = $Column->insert($column);
				}
			}
			$Column->commit();
			$this->success("保存栏目成功", null, array(
				"navTabId"=>MODULE_NAME
			));
		}catch(Exception $ex){
			$Column->rollback();
			$this->error($ex->getMessage());
		}
	}
	
	public function insert(){
		$Admin = D("Admin");
		try{
			$Admin->insert();
			$this->success("添加用户成功", null, array(
				"callbackType"=>"closeCurrent",
				"navTabId"=>MODULE_NAME
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
	
	public function edit($adminid){
		$Admin = D("Admin");
		$data = $Admin->find($adminid);
		$this->assign("data", $data);
		$this->display("info");
	}
	
	public function update(){
		$Admin = D("Admin");
		try{
			$Admin->update();
			$this->success("编辑用户成功", null, array(
				"callbackType"=>"closeCurrent",
				"navTabId"=>MODULE_NAME
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
}