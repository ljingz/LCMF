<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnModel extends BaseModel {
	protected $_defaults = array(
		"order"=>"sequence ASC"
	);
	
	public function getMap($query = array()){
		$Table = D("Table");
		$datas = $this->getList(array(
			"query"=>$query
		));
		foreach($datas as $data){
			if(!empty($data["tableid"])){
				$data["table"] = $Table->find($data["tableid"]);
			}
			$map[$data["columnid"]] = $data;
		}
		return $map;
	}
	
	public function getTree($query = array()){
		$datas = $this->getMap($query);
		foreach($datas as &$data){
			if(empty($data["parentid"])){
				$tree[] = &$data;
			}else{
				$datas[$data["parentid"]]["child"][] = &$data;
			}
		}
		return $tree;
	}
	
	public function getInfo($columnid){
		$data = $this->find($columnid);
		return $data;
	}
	
	public function exists($columnid){
		if($this->where(array("columnid"=>$columnid))->count() > 0){
			return true;
		}
		return false;
	}
}