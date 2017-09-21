<?php
/**
 * 支付回调
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class paymentControl extends mobileHomeControl{

    private $payment_code;

    public function __construct() {
        parent::__construct();

        $this->payment_code = $_GET['payment_code'];
    }

    /**
     * 支付回调
     */
    public function returnOp() {
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getReturnInfo($payment_config);

        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
                Tpl::output('result', 'success');
                Tpl::output('message', '支付成功');
            } else {
                Tpl::output('result', 'fail');
                Tpl::output('message', '支付失败');
            }
        } else {
            //验证失败
            Tpl::output('result', 'fail');
            Tpl::output('message', '支付失败');
        }

        Tpl::showpage('payment_message');
    }

    /**
     * 支付提醒
     */
    public function notifyOp() {

        // wxpay_jsapi
        if ($this->payment_code == 'wxpay_jsapi') {
            $api = $this->_get_payment_api();
            $params = $this->_get_payment_config();
            $api->setConfigs($params);

            list($result, $output) = $api->notify();

            if ($result) {
                $internalSn = $result['out_trade_no'] . '_' . $result['attach'];
                $externalSn = $result['transaction_id'];
                $updateSuccess = $this->_update_order($internalSn, $externalSn);

                if (!$updateSuccess) {
                    // @todo
                    // 直接退出 等待下次通知
                    exit;
                }
            }

            echo $output;
            exit;
        }

        // 恢复框架编码的post值
        $_POST['notify_data'] = html_entity_decode($_POST['notify_data']);

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getNotifyInfo($payment_config);

        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
                echo 'success';die;
            }
        }

        //验证失败
        echo "fail";die;
    }

    /**
     * 支付宝移动支付
     */
    public function notify_alipay_nativeOp() {
        $this->payment_code = 'alipay_native';
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }
        $payment_config = $this->_get_payment_config();
        $payment_api = new $this->payment_code();
        $payment_api->payment_config = $payment_config;
        $payment_api->alipay_config['partner'] = $payment_config['alipay_partner'];
        
        if ($payment_api->verify_notify()) {
            
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];

            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                $result = $this->_update_order($out_trade_no, $trade_no);
                if(!$result['state']) {
                    logResult("订单状态更新失败".$out_trade_no);
                }
            }
            exit("success");
        } else {
            logResult("verifyNotify验证失败".$out_trade_no);
            exit("fail");
        }
    }

    /**
     * 获取支付接口实例
     */
    private function _get_payment_api() {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }

        $payment_api = new $this->payment_code();

        return $payment_api;
    }

    /**
     * 获取支付接口信息
     */
    private function _get_payment_config() {
        $model_mb_payment = Model('mb_payment');

        //读取接口配置信息
        $condition = array();
        if($this->payment_code == 'wxpay3') {
            $condition['payment_code'] = 'wxpay';
        } else {
            $condition['payment_code'] = $this->payment_code;
        }
        $payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);

        return $payment_info['payment_config'];
    }

    /**
     * 更新订单状态
     */
    private function _update_order($out_trade_no, $trade_no) {
        $model_order = Model('order');
        $logic_payment = Logic('payment');

        $tmp = explode('_', $out_trade_no);
        $out_trade_no = $tmp[0];
        if (!empty($tmp[1])) {
            $order_type = $tmp[1];
        } else {
            $order_pay_info = Model('order')->getOrderPayInfo(array('pay_sn'=> $out_trade_no));
            if(empty($order_pay_info)){
                $order_type = 'v';
            } else {
                $order_type = 'r';
            }
        }

        // wxpay_jsapi
        $paymentCode = $this->payment_code;
        if ($paymentCode == 'wxpay_jsapi') {
            $paymentCode = 'wx_jsapi';
        } elseif ($paymentCode == 'wxpay3') {
            $paymentCode = 'wxpay';
        } elseif ($paymentCode == 'alipay_native') {
            $paymentCode = 'ali_native';
        }

        if ($order_type == 'r') {
            $result = $logic_payment->getRealOrderInfo($out_trade_no);
            if (intval($result['data']['api_pay_state'])) {
                return array('state'=>true);
            }
            $order_list = $result['data']['order_list'];
            $result = $logic_payment->updateRealOrder($out_trade_no, $paymentCode, $order_list, $trade_no);

            $api_pay_amount = 0;
            if (!empty($order_list)) {
                foreach ($order_list as $order_info) {
                    $api_pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                }
            }
            $log_buyer_id = $order_list[0]['buyer_id'];
            $log_buyer_name = $order_list[0]['buyer_name'];
            $log_desc = '实物订单使用'.orderPaymentName($paymentCode).'成功支付，支付单号：'.$out_trade_no;

        } elseif ($order_type == 'v') {
            $result = $logic_payment->getVrOrderInfo($out_trade_no);
            $order_info = $result['data'];
            if (!in_array($result['data']['order_state'],array(ORDER_STATE_NEW,ORDER_STATE_CANCEL))) {
                return array('state'=>true);
            }
            $result = $logic_payment->updateVrOrder($out_trade_no, $paymentCode, $result['data'], $trade_no);

            $api_pay_amount = $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
            $log_buyer_id = $order_info['buyer_id'];
            $log_buyer_name = $order_info['buyer_name'];
            $log_desc = '虚拟订单使用'.orderPaymentName($paymentCode).'成功支付，支付单号：'.$out_trade_no;
        }
        if ($result['state']) {
            //记录消费日志
            QueueClient::push('addConsume', array('member_id'=>$log_buyer_id,'member_name'=>$log_buyer_name,
            'consume_amount'=>ncPriceFormat($api_pay_amount),'consume_time'=>TIMESTAMP,'consume_remark'=>$log_desc));
        }

        return $result;
    }

}
