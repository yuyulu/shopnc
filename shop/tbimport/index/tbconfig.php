<?php
//引用全局文件
if (!@include('../../../33hao.php')) exit('global.php isn\'t exists!');
if (!@include(BASE_DATA_PATH.'/config/config.ini.php')) exit('config.ini.php isn\'t exists!');
if (file_exists(BASE_PATH.'/config/config.ini.php')){
	include(BASE_PATH.'/config/config.ini.php');
}
global $config;
$tbconfig =array();
$tbconfig['web_root'] = '../../../data/upload/shop/store/goods/';//上传路经，如果你修改上传路经，这里也要跟着修改
$tbconfig['datahost']     = $config['db']['1']['dbhost'].':'.$config['db']['1']['dbport'];//数据库服务器地址和端口
$tbconfig['datausername'] = $config['db']['1']['dbuser'];//数据库用户名
$tbconfig['datauserpass'] = $config['db']['1']['dbpwd'];//数据库用户密码
$tbconfig['databasename'] = $config['db']['1']['dbname'];//使用的数据库名
$tbconfig['datatablepre'] = $config['tablepre']; //数据表前缀