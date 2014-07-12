<?php
if(!defined('APP_NAME')) exit('Access Denied');

class TableModel extends BaseModel {
	protected $_auto = array (
		array("action", "json_encode", Model::MODEL_BOTH, "function")
	);
	
	protected $_validate = array(
		array("name", "", "数据表名已存在", Model::MUST_VALIDATE, "unique", Model::MODEL_INSERT),
		array("title", "", "模型名称已存在", Model::MUST_VALIDATE, "unique", Model::MODEL_INSERT)
	);
	
	public function build($table, $field){
		$TableField = D("TableField");
		if($this->_exists($table["name"])){
			throw new Exception("数据表名被占用");
		}
		$this->execute("SET AUTOCOMMIT = 0");
		$this->startTrans();
		try{
			$tableid = $this->insert($table);
			$this->_build($table["name"]);
			$TableField->build($tableid, $field);
			$this->commit();
		}catch(Exception $ex){
			$this->rollback();
			throw new Exception($ex->getMessage());
		}
	}
	
	protected function _build($tablename){
		$sql = sprintf("CREATE TABLE `%s%s` (`dataid` int(11) NOT NULL, PRIMARY KEY (`dataid`), CONSTRAINT `FK_%s` FOREIGN KEY (`dataid`) REFERENCES `%sdata` (`dataid`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8", C("DB_PREFIX"), $tablename, $tablename, C("DB_PREFIX"));
		if($this->execute($sql) === false){
			Log::write($this->getDbError());
			throw new Exception("创建模型表失败");
		}
	}
	
	public function alter($table, $field){
		$TableField = D("TableField");
		$this->startTrans();
		try{
			$this->update($table);
			$TableField->alter($table["tableid"], $field);
			$this->commit();
		}catch(Exception $ex){
			$this->rollback();
			throw new Exception($ex->getMessage());
		}
	}
	
	public function drop($tableid){
		$tablename = $this->getFieldByTableid($tableid, "name");
		$this->startTrans();
		try{
			if($this->delete($tableid) === false){
				throw new Exception("删除数据失败");
			}
			$this->_drop($tablename);
			$this->commit();
		}catch(Exception $ex){
			$this->rollback();
			throw new Exception($ex->getMessage());
		}
	}
	
	protected function _drop($tablename){
		if($this->_exists($tablename)){
			$sql = sprintf("DROP TABLE `%s%s`", C("DB_PREFIX"), $tablename);
			if($this->execute($sql) === false){
				Log::write($this->getDbError());
				throw new Exception("删除模型表失败");
			}
		}
	}
	
	protected function _exists($tablename){
		$tables = $this->db->getTables();
		if(in_array(sprintf("%s%s", C("DB_PREFIX"), $tablename), $tables)){
			return true;
		}
		return false;
	}
	
	public function getMap(){
		$datas = $this->getList();
		foreach($datas as $data){
			$map[$data["tableid"]] = $data;
		}
		return $map;
	}
	
	public function getInfo($tableid){
		static $info = array();
		if(!isset($info[$tableid])){
			$TableField = D("TableField");
			$data = $this->find($tableid);
			$data["action"] = json_decode($data["action"], true);
			$data["field"] = $TableField->getList(array(
				"query"=>array("tableid"=>$tableid)
			));
			$info[$tableid] = $data;
		}
		return $info[$tableid];
	}
}