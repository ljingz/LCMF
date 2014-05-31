<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnModel extends BaseModel {
	protected $_defaults = array(
		"order"=>"sequence ASC"
	);
	
	public function getMap(){
		$datas = $this->getList();
		foreach($datas as $data){
			$map[$data["columnid"]] = $data;
		}
		return $map;
	}
	
	public function getTree(){
		$datas = $this->getMap();
		foreach($datas as &$data){
			if(empty($data["parentid"])){
				$tree[] = &$data;
			}else{
				$datas[$data["parentid"]]["child"][] = &$data;
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