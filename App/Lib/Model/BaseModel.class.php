<?php
if(!defined('APP_NAME')) exit('Access Denied');

class BaseModel extends Model {
	public function insert($data = array()){
		if($this->create($data)){
			$insertid = $this->add();
			if($insertid === false){
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
				throw new Exception("编辑数据失败");
			}
			return $affectedrows;
		}else{
			throw new Exception($this->getError());
		}
	}
	
	public function getPageList($options = array()){
		import("ORG.Util.Page");
		$count = $this->where($options["query"])->count();
		$Page = new Page($count, C("NUM_PER_PAGE"));
		if(!empty($options["order"])){
			$this->order($options["order"]);
		}else{
			$this->order(sprintf("%s DESC", $this->getPk()));
		}
		$datas = $this->where($options["query"])
					  ->limit($Page->firstRow, $Page->listRows)
					  ->select();
		return array(
			"datas"=>$datas,
			"page"=>$Page
		);
	}
}