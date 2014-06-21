<?php
return array(
	//上传配置
	'UPLOAD_PATH'              => 'static/attachment/default/',
	'UPLOAD_MAX_SIZE'          => 20,
	'UPLOAD_ALLOW_TYPE'        => array(
		'IMAGE'=>array(
			'mimeType'=>array('image/*'),
			'extension'=>array(
				'png', 'gif', 'jpg', 'jpeg', 'bmp'
			)
		),
		'FILE'=>array(
			'mimeType'=>array('application/*'),
			'extension'=>array(
				'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'txt', 'md', 'xml',
				'png', 'gif', 'jpg', 'jpeg', 'bmp',
		        'flv', 'swf', 'mkv', 'avi', 'rm', 'rmvb', 'mpeg', 'mpg',
		        'ogg', 'ogv', 'mov', 'wmv', 'mp4', 'webm', 'mp3', 'wav', 'mid',
		        'rar', 'zip', 'tar', 'gz', '7z', 'bz2', 'cab', 'iso'
			)
		)
	)
);