<?php
if(!defined('APP_NAME')) exit('Access Denied');

class DataCrumbsWidget extends Widget {
	public function render($dataid){
		$Data = D("Data");
		$columnid = I("columnid");
		if(empty($dataid)){
			$dataid = I("dataid");
		}
		$data = $Data->getInfo($columnid, $dataid);
		return implode(" &gt; ", array(W("ColumnCrumbs", $columnid, TRUE), $data["title"]));
	}
}