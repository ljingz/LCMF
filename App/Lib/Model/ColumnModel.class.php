<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnModel extends BaseModel {
	protected $_defaults = array(
		"order"=>"sequence ASC"
	);
	
	public function getMap($query = array()){
		static $cache = array();
		if(!empty($cache[serialize($query)])){
			$map = $cache[serialize($query)];
		}else{
			$Table = D("Table");
			$datas = $this->getList(array(
				"query"=>$query
			));
			foreach($datas as $data){
				if(!empty($data["tableid"])){
					$data["table"] = $Table->getInfo($data["tableid"]);
				}
				$map[$data["columnid"]] = $data;
			}
			$cache[serialize($query)] = $map;
		}
		return $map;
	}
	
	public function getTree($query = array()){
		static $cache = array();
		if(!empty($cache[serialize($query)])){
			$tree = $cache[serialize($query)];
		}else{
			$datas = $this->getMap($query);
			foreach($datas as &$data){
				if(empty($data["parentid"])){
					$tree[] = &$data;
				}else{
					$datas[$data["parentid"]]["child"][] = &$data;
				}
			}
			$cache[serialize($query)] = $tree;
		}
		return $tree;
	}
	
	public function getInfo($columnid){
		static $info = array();
		if(empty($info[$columnid])){
			$info[$columnid] = $this->find($columnid);
		}
		return $info[$columnid];
	}
	
	public function getTable($columnid){
		$Table = D("Table");
		$data = $this->getInfo($columnid);
		$table = $Table->getInfo($data["tableid"]);
		return $table;
	}
	
	public function exists($columnid){
		if($this->where(array("columnid"=>$columnid))->count() > 0){
			return true;
		}
		return false;
	}
	
	public function getCrumbs($columnid){
		$datas = $this->getMap();
		while(!empty($datas[$columnid])){
			$crumbs[] = $datas[$columnid];
			$columnid = $datas[$columnid]["parentid"];
		}
		return array_reverse($crumbs);
	}
	
	public function getChilds($columnid){
		static $datas = array();
		if(empty($datas)){
			$datas = $this->getMap();
			foreach($datas as &$data){
				$datas[$data["parentid"]]["child"][] = &$data;
			}
		}
		return $datas[$columnid]["child"];
	}
	
	public function getSiblings($columnid){
		$column = $this->getInfo($columnid);
		return $this->getChilds($column["parentid"]);
	}
}