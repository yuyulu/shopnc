<?php
session_start();
//判断是否已经登录
if(!empty($_COOKIE['key'])){
	header("Location:".WAP_SITE_URL);
	exit;	
}
include_once(BASE_PATH.DS.'api'.DS.'sina'.DS.'config.php' );
include_once(BASE_PATH.DS.'api'.DS.'sina'.DS.'saetv2.ex.class.php' );
$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );

$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
if($_GET['state'] && $_GET['display']){
	$code_url = $code_url.'&state=api&display=mobile';
}
@header("location:$code_url");
exit;
?>