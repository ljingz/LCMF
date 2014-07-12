<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnTitleWidget extends Widget {
	public function render($columnid){
		$Column = D("Column");
		if(empty($columnid)){
			$columnid = I("columnid");
		}
		$columns = $Column->getCrumbs($columnid);
		foreach($columns as $column){
			$crumbs[] = $column["name"];
		}
		return implode("_", array_reverse($crumbs));
	}
}