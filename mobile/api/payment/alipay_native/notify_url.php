<?php
/* *
 * 功能：支付宝原生支付服务器异步通知页面
 */


$_GET['act'] = 'payment';
$_GET['op']	= 'notify_alipay_native';
// $_POST['out_trade_no'] = '440491076012269007';
// $_POST['trade_status'] = 'TRADE_FINISHED';
require_once(dirname(__FILE__).'/../../../index.php');
?>
