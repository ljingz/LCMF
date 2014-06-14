<?php
return array(
	'APP_STATUS'               => 'debug',
	'APP_GROUP_LIST'           => 'Admin,Home,Common',

	//默认分组
	'DEFAULT_GROUP'            => 'Home',

	//URL设置
	'URL_MODEL'                => 3,

	//数据库配置
	'DB_TYPE'                  => 'mysql',
	'DB_HOST'                  => 'localhost',
	'DB_PORT'                  => 3306,
	'DB_NAME'                  => 'lcmf',
	'DB_USER'                  => 'root',
	'DB_PWD'                   => '1314',
	'DB_PREFIX'                => 'lcmf_',
	'DB_SQL_LOG'               => false,

	//session配置
	'SESSION_AUTO_START'       => false,
	'SESSION_OPTIONS'          => array(
		'expire'               => 43200,
		'name'                 => 'LCMFSESSID'
	),

	//模版 配置
	'LAYOUT_ON'                => false,
	'LAYOUT_NAME'              => '../layout',
	'TAG_NESTED_LEVEL'         => 9,
	'TMPL_STRIP_SPACE'         => false,
	'TMPL_PARSE_STRING'        => array(
		'__STATIC__'           => __ROOT__.'/static',
		'__PAGE__TITLE__'      => 'LCMF'
	),
	
	//上传配置
	'UPLOAD_PATH'              => '/upload',
	'UPLOAD_MAX_SIZE'          => 2,
	'UPLOAD_ALLOW_EXTS'        => array('png', 'gif', 'jpg', 'jpeg', 'tmp', 'doc', 'docx', 'xls', 'xlsx'),
	
	//参数过滤函数
	'VAR_FILTERS'              => 'requestFilterHandler',
	'DEFAULT_FILTER'           => '',
	
	//扩展配置
	'LOAD_EXT_CONFIG'          => MODULE_NAME.'/config,rbac',
	
	//动态载入
	'LOAD_EXT_FILE'            => 'functions'
);
?>