<?php
if(!defined('APP_NAME')) exit('Access Denied');

class TableModel extends BaseModel {
	protected $_validate = array(
		array("name", "", "数据表名已存在", Model::MUST_VALIDATE, "unique", Model::MODEL_INSERT),
		array("title", "", "模型名称已存在", Model::MUST_VALIDATE, "unique", Model::MODEL_INSERT)
	);
}