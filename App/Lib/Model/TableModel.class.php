<?php
if(!defined('APP_NAME')) exit('Access Denied');

class TableModel extends BaseModel {
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
			$TableField->build($tableid, $field);
			$this->_build($table["name"]);
			$TableField->_alter($table["name"], $field);
			$this->commit();
		}catch(Exception $ex){
			$this->rollback();
			throw new Exception($ex->getMessage());
		}
		
	}
	
	protected function _build($tablename){
		$sql = sprintf("CREATE TABLE `%s%s` (`dataid` int(11) NOT NULL,PRIMARY KEY (`dataid`)) ENGINE=InnoDB DEFAULT CHARSET=utf8", C("DB_PREFIX"), $tablename);
		if($this->execute($sql) === false){
			Log::write($this->getDbError());
			throw new Exception("创建模型表失败");
		}
	}
	
	public function alter($table, $field){
		
	}
}