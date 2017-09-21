<?php
header("Content-type: text/html; charset=UTF-8");
define('In33hao',true);
$config_file = '../../../config/config.ini.php';
$config = require($config_file);
$code = $_GET['code'];
$state  = $_GET['state'];
if(!empty($code)) {
	$sina_url = $config['member_site_url'].'/index.php?act=connect_sina&op=index&code=';
	if($state == 'api'){
		$sina_url = $config['mobile_site_url'].'/index.php?act=connect&op=get_sina_info&client=wap&code=';
		}
	@header("location: ".$sina_url.$code);
} else {
	exit('参数错误');
}