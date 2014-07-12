<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnLinkWidget extends Widget {
	protected $columns = array();
	
	public function render($columnid){
		$Column = D("Column");
		$this->columns = $Column->getMap();
		if(!empty($this->columns[$columnid]["link"])){
			return $this->columns[$columnid]["link"];
		}else{
			return $this->link($columnid);
		}
	}
	
	protected function link($columnid){
		$table = $this->columns[$columnid]["table"];
		if(!empty($table)){
			if($table["type"] == "content"){
				$action = "view";
			}else{
				$action = "lists";
			}
			return sprintf("__GROUP__/%s/%s/columnid/%d", ucfirst($table["name"]), $action, $columnid);
		}else{
			foreach($this->columns as $id=>$column){
				if($column["parentid"] == $columnid){
					return $this->link($id);
				}
			}
		}
		return "#";
	}
}