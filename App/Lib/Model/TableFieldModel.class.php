<?php
if(!defined('APP_NAME')) exit('Access Denied');

class TableFieldModel extends BaseModel {
	public function build($tableid, $field){
		try{
			foreach($field as $data){
				$data["tableid"] = $tableid;
				$this->insert($data);
			}
		}catch(Exception $ex){
			throw new Exception("添加模型字段失败");
		}
	}
	
	public function alter($tableid, $field){
		
	}
	
	public function _alter($tablename, $field){
		foreach($field as $data){
			$alter[] = sprintf("ADD1 COLUMN `%s` %s NULL COMMENT '%s'", $data["name"], strtoupper(data("table", "element", $data["element"], "type")), $data["title"]);
		}
		$sql = sprintf("ALTER TABLE `%s%s` %s", C("DB_PREFIX"), $tablename, implode(",", $alter));
		if($this->execute($sql) === false){
			Log::write($this->getDbError());
			throw new Exception("更新模型表字段失败");
		}
	}
}