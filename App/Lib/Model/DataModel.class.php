<?php
if(!defined('APP_NAME')) exit('Access Denied');

class DataModel extends BaseModel {
	protected $_auto = array (	
		array("createtime", "time", Model::MODEL_INSERT, "function"),
		array("modifiedtime", "time", Model::MODEL_BOTH, "function")
	);
	
	public function insert($data){
		$Column = D("Column");
		$table = $Column->getTable($data["columnid"]);
		$this->startTrans();
		try{
			$data["dataid"] = parent::insert();
			$Model = D($table["name"]);
			if($Model->add($data) === false){
				Log::write($this->getDbError());
				throw new Exception("添加数据失败");
			}
			$this->commit();
		}catch(Exception $ex){
			$this->rollback();
			throw new Exception($ex->getMessage());
		}
	}
	
	public function update($data){
		$Column = D("Column");
		$table = $Column->getTable($data["columnid"]);
		$this->startTrans();
		try{
			parent::update();
			$Model = D($table["name"]);
			if($Model->save($data) === false){
				Log::write($this->getDbError());
				throw new Exception("编辑数据失败");
			}
			$this->commit();
		}catch(Exception $ex){
			$this->rollback();
			throw new Exception($ex->getMessage());
		}
	}
	
	public function getPageList($columnid, $options = array()){
		$Column = D("Column");
		$options["query"]["columnid"] = $columnid;
		$list = parent::getPageList($options);
		$table = $Column->getTable($columnid);
		$Model = D($table["name"]);
		foreach($list["datas"] as &$data){
			$data = array_merge($Model->find($data["dataid"]), $data);
		}
		return $list;
	}
	
	public function getInfo($columnid, $dataid = null){
		$Column = D("Column");
		if(!empty($dataid)){
			$this->where(array("dataid"=>$dataid));
		}
		$data = $this->where(array("columnid"=>$columnid))->find();
		$table = $Column->getTable($columnid);
		$Model = D($table["name"]);
		$data = array_merge($Model->find($data["dataid"]), $data);
		return $data;
	}
}