<?php
if(!defined('APP_NAME')) exit('Access Denied');

class UploadAction extends Action {
	public function index(){
		print_r($_FILES);
		sleep(1);
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}
}