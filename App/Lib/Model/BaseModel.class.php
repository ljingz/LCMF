<?php
if(!defined('APP_NAME')) exit('Access Denied');

class BaseModel extends Model {
	public function insert($data = array()){
		if($this->create($data)){
			$insertid = $this->add();
			if($insertid === false){
				if($this->getError()){
					Log::write($this->getError());
				}
				if($this->getDbError()){
					Log::write($this->getDbError());
				}
				throw new Exception("添加数据失败");
			}
			return $insertid;
		}else{
			throw new Exception($this->getError());
		}
	}
	
	public function update($data = array()){
		if($this->create($data)){
			$affectedrows = $this->save();
			if($affectedrows === false){
				if($this->getError()){
					Log::write($this->getError());
				}
				if($this->getDbError()){
					Log::write($this->getDbError());
				}
				throw new Exception("编辑数据失败");
			}
			return $affectedrows;
		}else{
			throw new Exception($this->getError());
		}
	}
	
	protected function parse($options){
		if(!empty($options["alias"])){
			$this->alias($options["alias"]);
		}
		if(!empty($options["join"])){
			if(!is_array($options["join"])){
				$options["join"] = array($options["join"]);
			}
			foreach($options["join"] as $join){
				$this->join($join);
			}
		}
		if(!empty($options["query"])){
			$this->where($options["query"]);
		}
		if(!empty($options["limit"])){
			$this->limit($options["limit"]);
		}
		if(empty($this->_defaults["order"])){
			$this->_defaults["order"] = sprintf("%s ASC", $this->getPk());
		}
		$this->order(implode(",", array_filter(array($options["order"], $this->_defaults["order"]))));
		return $this;
	}
	
	public function getList($options = array()){
		$datas = $this->parse($options)
					  ->select();
		return $datas;
	}
	
	public function getPageList($options = array()){
		import("ORG.Util.Page");
		$count = $this->parse($options)->count();
		$Page = new Page($count, C("NUM_PER_PAGE"));
		$datas = $this->parse($options)
					  ->limit($Page->firstRow, $Page->listRows)
					  ->select();
		return array(
			"datas"=>$datas,
			"page"=>$Page
		);
	}
}