<?php
if(!defined('APP_NAME')) exit('Access Denied');

class DataModel extends BaseModel {
	protected $_auto = array (	
		array("createtime", "strtotime", Model::MODEL_BOTH, "function"),
		array("modifiedtime", "time", Model::MODEL_BOTH, "function")
	);
	
	protected $_defaults = array(
		"order"=>"createtime DESC,dataid DESC"
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
				case "checkbox":
					$data[$field["name"]] = implode(",", $data[$field["name"]]);
				break;
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
	
	public function getList($columnid, $options = array()){
		$Column = D("Column");
		$table = $Column->getTable($columnid);
		$options["alias"] = "d";
		$options["join"] = "__".strtoupper($table["name"])."__ t USING(dataid)";
		$options["query"]["d.columnid"] = $columnid;
		$datas = parent::getList($options);
		foreach($datas as &$data){
			$data = $this->disassembly($data, $table["field"]);
		}
		return $datas;
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
	
	public function getSearchList($keyword){
		import("ORG.Util.Page");
		$Table = D("Table");
		$datas = array();
		if($keyword != ""){
			$tables = $Table->getList(array("query"=>array("type"=>"list")));
			foreach($tables as $table){
				$Model = D(ucfirst($table["name"]));
				$fields = $Model->getDbFields();
				if(in_array("title", $fields)){
					$options["alias"] = "d";
					$options["join"] = "__".strtoupper($table["name"])."__ t USING(dataid)";
					$options["query"] = array("title"=>array("like", "%".$keyword."%"));
					$_datas = parent::getList($options);
					if(is_array($_datas)){
						$datas = array_merge($datas, $_datas);
					}
				}
			}
		}
		$Page = new Page(count($datas), C("NUM_PER_PAGE"));
		return array(
			"datas"=>array_slice($datas, $Page->firstRow, $Page->listRows),
			"page"=>$Page
		);
	}
	
	protected function disassembly($data, $fields){
		foreach($fields as $field){
			if(in_array($field["element"], array("checkbox"))){
				if(isset($data[$field["name"]])){
					$data[$field["name"]] = explode(",", $data[$field["name"]]);
				}
			}
			if(in_array($field["element"], array("file", "image", "imagegroup"))){
				if(isset($data[$field["name"]])){
					$data[$field["name"]] = json_decode($data[$field["name"]], true);
				}
			}
		}
		return $data;
	}
}