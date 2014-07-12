<?php
if(!defined('APP_NAME')) exit('Access Denied');

class DataLinkWidget extends Widget {
	public function render($data){
		$Column = D("Column");
		$table = $Column->getTable($data["columnid"]);
		return sprintf("__GROUP__/%s/view/columnid/%d/dataid/%d", ucfirst($table["name"]), $data["columnid"], $data["dataid"]);
	}
}