<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnAction extends BaseAction {
	public function index(){
		$Column = D("Column");
		$tree = $Column->getTree();
		$this->assign("datas", $this->buildTree($tree));
		$this->display();
	}
	
	protected function buildTree($tree){
		$html = "<ul>";
		foreach($tree as $data){
			$html .= "<li>";
			$html .= $data["name"];
			$html .= "<a class='btnDel' href='javascript:void(0)'>删除栏目</a>";
			$html .= "<a class='btnAdd' href='javascript:void(0)'>添加子栏目</a>";
			if(is_array($data["child"])){
				$html .= $this->buildTree($data["child"]);
			}
			$html .= "</li>";
		}
		$html .= "</ul>";
		return $html;
	}
	
	public function add(){
		$this->display("info");
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
	
	public function delete($adminid){
		$Admin = D("Admin");
		if($Admin->delete($adminid) === false){
			$this->error("删除用户失败");
		}else{
			$this->success("删除用户成功", null, array(
				"navTabId"=>MODULE_NAME
			));
		}
	}
}