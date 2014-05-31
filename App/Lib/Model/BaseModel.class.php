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
	
	public function getList($options = array()){
		if(!empty($options["order"])){
			$this->order($options["order"]);
		}else{
			if(empty($this->_defaults["order"])){
				$this->_defaults["order"] = sprintf("%s ASC", $this->getPk());
			}
			$this->order($this->_defaults["order"]);
		}
		$datas = $this->where($options["query"])
					  ->select();
		return $datas;
	}
	
	public function getPageList($options = array()){
		import("ORG.Util.Page");
		$count = $this->where($options["query"])->count();
		$Page = new Page($count, C("NUM_PER_PAGE"));
		$datas = $this->limit($Page->firstRow, $Page->listRows)
					  ->getList($options);
		return array(
			"datas"=>$datas,
			"page"=>$Page
		);
	}
}