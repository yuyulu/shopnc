<?php

/**
 * 支付宝移动支付
 *
/* Author:			33hao	    	*/
/* Support Site:	www.33hao.com	*/
/* Retrofit Date:	Aug-08-2016		*/
/* ================================ */

defined('In33hao') or exit('Access Invalid!');

require_once("lib/alipay_core.function.php");
require_once("lib/alipay_rsa.function.php");
class alipay_native {
    public $param = array();
    public $alipay_config = array();
    public $payment_config = array();
    public $parseStr = ""; 
    
    public function __construct() {
        require_once("alipay.config.php");
        $this->alipay_config = $alipay_config;
    }

    /**
     * 初始化信息
     * @param unknown $payment_info
     * @param unknown $order_info
     */
    public function init($payment_info = array(), $order_info = array()) {
        $this->param['service'] = 'mobile.securitypay.pay';
        $this->param['partner'] = $payment_info['alipay_partner'];
        $this->param['_input_charset'] = 'utf-8';
        $this->param['sign_type'] = 'RSA';
        $this->param['notify_url'] = MOBILE_SITE_URL.'/api/payment/alipay_native/notify_url.php';
        $this->param['seller_id'] = $payment_info['alipay_account'];
        $this->param['out_trade_no'] = $order_info['pay_sn'];
        $this->param['subject'] = $order_info['subject'];
        $this->param['payment_type'] = 1;
        $this->param['total_fee'] = $order_info['api_pay_amount'];
        $this->param['body'] = $order_info['order_type'];
        $this->param['sign'] = $this->sign();//urlencode
//         var_dump($this->vsign());exit;
    }

    public function mySign($prestr) {
        return rsaSign($prestr, $this->alipay_config['private_key_path']);
    } 
    
    /**
     * 签 名
     * @return string
     */
    private function sign() {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = paraFilter($this->param);

        //对待签名参数数组排序
        $para_sort = argSort($para_filter);
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = createLinkstring($para_sort);
        $sign = rsaSign($prestr, $this->alipay_config['private_key_path']);
        $this->parseStr = $prestr.'&sign='.$sign;
        return $sign;
    }

    /**
     * 异步通知合法性验证
     * @return Ambigous <验证结果, boolean>
     */
    public function verify_notify() {
        require_once("lib/alipay_notify.class.php");

        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->alipay_config);
        return $alipayNotify->verifyNotify();
    }

    /**
     * 反签名测试
     * @return boolean
     */
    public function vsign() {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = paraFilter($this->param);

        //对待签名参数数组排序
        $para_sort = argSort($para_filter);

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = createLinkstring($para_sort);
        return rsaVerify($prestr, trim($this->alipay_config['ali_public_key_path']), $this->param['sign']);
    }
}
