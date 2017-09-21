<?php
/**
 * 微信支付接口类
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

/**
 * @todo TEST 传递的URL参数是否冲突
 * @todo 后续接收通知
 * @todo 后续页面显示 以及异步结果提示
 */
class wxpay
{

    /**
     * 获取notify信息
     */
    public function getNotifyInfo($payment_config) {
        $result = $this->_verify($payment_config);

        if ($result) {
            return array(
                //商户订单号
                'out_trade_no' => $result['out_trade_no'],
                //微信支付交易号
                'trade_no' => $result['transaction_id'],
            );
        }

        return false;
    }

    /**
     * 验证返回信息
     */
    private function _verify($payment_config) {
        if (empty($payment_config)) {
            return false;
        }

        // 将系统的控制参数置空，防止因为加密验证出错
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);

        ksort($_GET);
        $hash_temp = '';
        foreach ($_GET as $key => $value) {
            if ($key != 'sign') {
                $hash_temp .= $key . '=' . $value . '&';
            }
        }

        $hash_temp .= 'key' . '=' . $payment_config['wxpay_partnerkey'];

        $hash = strtoupper(md5($hash_temp));

        if($hash == $_GET['sign']) {
            return array(
                'out_trade_no' => $_GET['out_trade_no'],
                'transaction_id' => $_GET['transaction_id'],
            );
        } else {
            return false;
        }
    }
}
