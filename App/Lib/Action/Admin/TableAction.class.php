<?php
if(!defined('APP_NAME')) exit('Access Denied');

class TableAction extends BaseAction {
	public function _initialize(){
		parent::_initialize();
		if($this->loginUser["adminid"] > 0){
			exit();
		}
	}
	
	public function index(){
		$Table = D("Table");
		$type = $this->_get("type");
		$title = $this->_get("title");
		if(!empty($title)){
			$options["query"]["title|name"] = array("like", sprintf("%%%s%%", $title));
		}
		if(!empty($type)){
			$options["query"]["type"] = $type;
		}
		$datas = $Table->getList($options);
		$this->assign("datas", $datas);
		$this->display();
	}
	
	public function add(){
		$this->assign("data", array("field"=>array(array(
			"name"=>"title",
			"title"=>"标题",
			"element"=>"text",
			"validate"=>"required",
			"list"=>"1"
		))));
		$this->display("info");
	}
	
	public function insert(){
		$Table = D("Table");
		$table = $this->_post("table");
		$field = $this->_post("field");
		try{
			$Table->build($table, $field);
			$this->success("添加模型成功", null, array(
				"callbackType"=>"closeCurrent",
				"navTabId"=>MODULE_NAME
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
	
	public function edit($tableid){
		$Table = D("Table");
		$data = $Table->getInfo($tableid);
		$this->assign("data", $data);
		$this->display("info");
	}
	
	public function update(){
		$Table = D("Table");
		$table = $this->_post("table");
		$field = $this->_post("field");
		try{
			$Table->alter($table, $field);
			$this->success("编辑模型成功", null, array(
				"callbackType"=>"closeCurrent",
				"navTabId"=>MODULE_NAME
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
	
	public function delete($tableid){
		$Table = D("Table");
		try{
			$Table->drop($tableid);
			$this->success("删除模型成功", null, array(
				"navTabId"=>MODULE_NAME
			));
		}catch(Exception $ex){
			$this->error($ex->getMessage());
		}
	}
}