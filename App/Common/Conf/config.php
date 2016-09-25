<?php
return array(

	/* 数据库设置 */
	'DB_TYPE' => 'mysql', // 数据库类型
	'DB_HOST' => 'localhost', // 服务器地址
	'DB_NAME' => 'new_vote', // 数据库名
	'DB_USER' => 'root', // 用户名
	'DB_PWD' => '', // 密码
	'DB_PORT' => '3306', // 端口

	/* 模板替换 */
	'TMPL_PARSE_STRING' => array(
		'__CSS__' => __ROOT__ . '/Public/css',
		'__JS__' => __ROOT__ . '/Public/js',
		'__STATIC__' => __ROOT__ . '/Public/static',
		'__IMG__' => __ROOT__ . '/Public/image',
		'__PUBLIC__' => '/Public/',
	),
	'DEFAULT_MODULE' => 'Admin',

);
