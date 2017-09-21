<?php
defined('In33hao') or exit('Access Invalid!');
/*
 * 配置文件
 */
$options = array();
$options['apikey'] = C('hao_sms_key'); //apikey
$options['signature'] =  C('hao_sms_signature'); //签名
return $options;
?>