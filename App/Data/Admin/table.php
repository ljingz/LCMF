<?php
if(!defined('APP_NAME')) exit('Access Denied');

return array(
	"element"=>array(
		"text"=>"单行文本框",
		"textarea"=>"多行文本框"
	),
	"validate"=>array(
		"required",
		"email",
		"phone"
	)
);