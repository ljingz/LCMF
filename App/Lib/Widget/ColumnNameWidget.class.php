<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnNameWidget extends Widget {
	public function render($columnid){
		$Column = D("Column");
		if(empty($columnid)){
			$columnid = I("columnid");
		}
		$column = $Column->getInfo($columnid);
		return $column["name"];
	}
}