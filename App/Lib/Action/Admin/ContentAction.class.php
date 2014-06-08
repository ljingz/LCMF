<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ContentAction extends BaseAction {
	public function index($columnid){
		$Column = D("Column");
		$Table = D("Table");
		$column = $Column->getInfo($columnid);
		$table = $Table->getInfo($column["tableid"]);
		$this->assign("table", $table);
		$this->display();
	}
	
	
}