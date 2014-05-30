<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnModel extends BaseModel {
	protected $_validate = array(
		array("name", "", "栏目名已存在", Model::MUST_VALIDATE, "unique", Model::MODEL_INSERT)
	);
	
	public function getTree(){
		$datas = $this->getList();
		foreach($datas as &$data){
			$tmp[$data["columnid"]] = &$data;
			$tmp[$data["parentid"]]["child"][] = &$data;
			if(empty($data["parentid"])){
				$tree[] = &$data;
			}
		}
		return $tree;
	}
	
	public function exists($columnid){
		if($this->where(array("columnid"=>$columnid))->count() > 0){
			return true;
		}
		return false;
	}
}