<?php
if(!defined('APP_NAME')) exit('Access Denied');

class TableFieldModel extends BaseModel {
	protected $_defaults = array(
		"order"=>"sequence ASC"
	);
	
	public function build($tableid, $field){
		$Table = D("Table");
		try{
			$sequence = 0;
			foreach($field as $data){
				$data["tableid"] = $tableid;
				$data["sequence"] = $sequence;
				$this->insert($data);
				$sequence ++;
			}
			$tablename = $Table->getFieldByTableid($tableid, "name");
			$this->_alter($tablename, $field);
		}catch(Exception $ex){
			throw new Exception($ex->getMessage());
		}
	}
	
	public function alter($tableid, $field){
		$Table = D("Table");
		try{
			$sequence = 0;
			foreach($field as $data){
				$data["sequence"] = $sequence;
				if(empty($data["fieldid"])){
					$data["tableid"] = $tableid;
					$data["fieldid"] = $this->insert($data);
				}else{
					$this->update($data);
				}
				$exclude[] = $data["fieldid"];
				$sequence ++;
			}
			$result = $this->where(array("tableid"=>$tableid, "fieldid"=>array("not in", $exclude)))->delete();
			if($result === false){
				throw new Exception("删除模型表数据失败");
			}
			$tablename = $Table->getFieldByTableid($tableid, "name");
			$this->_alter($tablename, $field);
		}catch(Exception $ex){
			throw new Exception($ex->getMessage());
		}
	}
	
	public function _alter($tablename, $field){
		$schema = $this->db->getFields(C("DB_PREFIX").$tablename);
		if(empty($schema)){
			throw new Exception("获取模型表字段失败");
		}
		unset($schema["dataid"]);
		foreach($field as $data){
			$name = $data["name"];
			$type = strtoupper(data("table", "field", "element", $data["element"], "type"));
			$comment = $data["title"];
			if(array_key_exists($name, $schema)){
				$alter[] = sprintf("CHANGE `%s` `%s` %s NULL COMMENT '%s'", $name, $name, $type, $comment);
				unset($schema[$name]);
			}else{
				$alter[] = sprintf("ADD COLUMN `%s` %s NULL COMMENT '%s'", $name, $type, $comment);
			}
		}
		foreach($schema as $data){
			$alter[] = sprintf("DROP COLUMN `%s`", $data["name"]);
		}
		$sql = sprintf("ALTER TABLE `%s%s` %s", C("DB_PREFIX"), $tablename, implode(",", $alter));
		if($this->execute($sql) === false){
			Log::write($this->getDbError());
			throw new Exception("更新模型表字段失败");
		}
	}
}