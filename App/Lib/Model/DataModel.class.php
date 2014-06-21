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
			$data = $this->assembly($data, $table["field"]);
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
			$data = $this->assembly($data, $table["field"]);
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
	
	protected function assembly($data, $fields){
		foreach($fields as $field){
			if(!isset($data[$field["name"]])){
				$data[$field["name"]] = NULL;
				continue;
			}
			switch($field["element"]){
				case "file":
				case "image":
					$data[$field["name"]] = requestFilterDecode($data[$field["name"]]);
				break;
				case "imagegroup":
					foreach($data[$field["name"]] as &$value){
						$value = json_decode(requestFilterDecode($value), true);
					}
					$data[$field["name"]] = json_encode($data[$field["name"]]);
				break;
			}
		}
		return $data;
	}
	
	public function setValue($columnid, $dataid, $data = array()){
		try{
			$result = $this->where(array("columnid"=>$columnid, "dataid"=>array("in", $dataid)))->save($data);
			if($result === false){
				throw new Exception("设置字段值失败");
			}
		}catch(Exception $ex){
			throw new Exception($ex->getMessage());
		}
	}
	
	public function getPageList($columnid, $options = array()){
		$Column = D("Column");
		$table = $Column->getTable($columnid);
		$options["alias"] = "d";
		$options["join"] = "__".strtoupper($table["name"])."__ t USING(dataid)";
		$options["query"]["d.columnid"] = $columnid;
		$list = parent::getPageList($options);
		foreach($list["datas"] as &$data){
			$data = $this->disassembly($data, $table["field"]);
		}
		return $list;
	}
	
	public function getInfo($columnid, $dataid = null){
		$Column = D("Column");
		$table = $Column->getTable($columnid);
		$Model = D($table["name"]);
		if(!empty($dataid)){
			$this->where(array("dataid"=>$dataid));
		}
		$data = $this->where(array("columnid"=>$columnid))->find();
		$tabledata = $Model->find($data["dataid"]);
		$tabledata = $this->disassembly($tabledata, $table["field"]);
		return array_merge($tabledata, $data);
	}
	
	protected function disassembly($data, $fields){
		foreach($fields as $field){
			if(in_array($field["element"], array("file", "image", "imagegroup"))){
				if(isset($data[$field["name"]])){
					$data[$field["name"]] = json_decode($data[$field["name"]], true);
				}
			}
		}
		return $data;
	}
}