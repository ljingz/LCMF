<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnCrumbsWidget extends Widget {
	public function render($columnid){
		$Column = D("Column");
		if(empty($columnid)){
			$columnid = I("columnid");
		}
		$columns = $Column->getCrumbs($columnid);
		$crumbs[] = "首页";
		foreach($columns as $column){
			$crumbs[] = $column["name"];
		}
		return implode(" &gt; ", $crumbs);
	}
}