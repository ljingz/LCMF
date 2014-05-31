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
		$this->execute("SET AUTOCOMMIT = 0");
		$this->startTrans();
		try{
			$tableid = $this->insert($table);
			$this->_build($table["name"]);
			$TableField->build($tableid, $field);
			$TableField->_alter($table["name"], $field);
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
		
	}
	
	public function getMap(){
		$datas = $this->getList();
		foreach($datas as $data){
			$map[$data["tableid"]] = $data;
		}
		return $map;
	}
}