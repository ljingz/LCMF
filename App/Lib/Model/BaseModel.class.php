<?php
if(!defined('APP_NAME')) exit('Access Denied');

class BaseModel extends Model {
	public function getPageList($options = array()){
		import("ORG.Util.Page");
		$count = $this->count();
		$Page = new Page($count, C("NUM_PER_PAGE"));
		$datas = $this->limit($Page->firstRow, $Page->listRows)
					  ->select();
		return array(
			"datas"=>$datas,
			"page"=>$Page
		);
	}
}