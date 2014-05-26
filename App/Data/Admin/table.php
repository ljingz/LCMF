<?php
if(!defined('APP_NAME')) exit('Access Denied');

return array(
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
);