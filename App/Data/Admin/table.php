<?php
if(!defined('APP_NAME')) exit('Access Denied');

return array(
	"type"=>array(
		"list"=>array(
			"name"=>"文字列表",
			"module"=>"List"
		),
		"image"=>array(
			"name"=>"图片列表",
			"module"=>"List"
		),
		"content"=>array(
			"name"=>"文字内容",
			"module"=>"Content"
		)
	),
	"action"=>array(
		"add"=>"添加",
		"delete"=>"删除",
		"edit"=>"编辑",
		"enable"=>"锁定",
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
				"type"=>"date"
			),
			"datetime"=>array(
				"name"=>"时间",
				"type"=>"datetime"
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