<?php
if(!defined('APP_NAME')) exit('Access Denied');

class ColumnAction extends BaseAction {
	public function index(){
		$Column = D("Column");
		$Table = D("Table");
		$this->assign("tree", $Column->getTree());
		$this->assign("tables", $Table->getMap());
		$this->display();
	}
	
	public function save(){
		$Column = D("Column");
		$columns = $this->_post("column");
		$Column->startTrans();
		try{
			foreach($columns as $columnid=>$column){
				if(array_key_exists($column["parentid"], $tmp)){
					$column["parentid"] = $tmp[$column["parentid"]];
				}
				if($Column->exists($columnid)){
					$Column->update($column);
					$exclude[] = $columnid;
				}else{
					$tmp[$columnid] = $Column->insert($column);
					$exclude[] = $tmp[$columnid];
				}
			}
			$Column->where(array("columnid"=>array("not in", $exclude)))->delete();
			$Column->commit();
			$this->success("保存栏目成功", null, array(
				"navTabId"=>MODULE_NAME
			));
		}catch(Exception $ex){
			$Column->rollback();
			$this->error($ex->getMessage());
		}
	}
}