<?php
if(!defined('APP_NAME')) exit('Access Denied');

return array(
	"type"=>array(
		"list"=>"文字列表",
		"image"=>"图片列表",
		"content"=>"文字内容"
	),
	"action"=>array(
		"add"=>"添加",
		"delete"=>"删除",
		"edit"=>"编辑",
		"enable"=>"启用",
		"recommend"=>"推荐",
		"headline"=>"头条"
	),
	"field"=>array(
		"element"=>array(
			"text"=>array(
				"name"=>"单行文本框",
				"type"=>"varchar(255)"
			),
			"textarea"=>array(
				"name"=>"多行文本框",
				"type"=>"text"
			),
			"date"=>array(
				"name"=>"日期",
				"type"=>"int(11)"
			),
			"file"=>array(
				"name"=>"文件",
				"type"=>"varchar(255)"
			),
			"editor"=>array(
				"name"=>"编辑器",
				"type"=>"longtext"
			)
		),
		"validate"=>array(
			"required",
			"email",
			"phone",
			"lettersonly",
			"alphanumeric",
			"digits",
			"number",
			"creditcard",
			"url"
		)
	)
);