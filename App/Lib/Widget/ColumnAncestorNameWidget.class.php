<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnAncestorNameWidget extends Widget {
	public function render($columnid){
		$Column = D("Column");
		if(empty($columnid)){
			$columnid = I("columnid");
		}
		$crumbs = $Column->getCrumbs($columnid);
		$ancestor = array_shift($crumbs);
		return $ancestor["name"];
	}
}