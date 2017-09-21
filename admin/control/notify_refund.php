<?php
/**
 * 在线退款异步通知
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class notify_refundControl{
    /**
     * 支付宝
     *
     */
    public function alipayOp(){
        $api_file = BASE_PATH.DS.'api'.DS.'refund'.DS.'alipay'.DS.'alipay.class.php';
        include $api_file;
        $result = "fail";
        $condition = array();
        $condition['payment_code'] = 'alipay';
        $model_payment = Model('payment');
        $payment_info = $model_payment->getPaymentInfo($condition);//接口参数
        $payment_config = unserialize($payment_info['payment_config']);
        
        $alipay_config = array();
        $alipay_config['seller_email'] = $payment_config['alipay_account'];
        $alipay_config['partner'] = $payment_config['alipay_partner'];
        $alipay_config['key'] = $payment_config['alipay_key'];
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {//验证成功
            $batch_no = $_POST['batch_no'];//批次号
            $success_num = $_POST['success_num'];//退交易成功的笔数
            $result_details = $_POST['result_details'];//退款结果明细:交易号^退款金额^处理结果
            $details = explode('^', $result_details);
            if($success_num > 0 && substr($details[2], 0, 7) == 'SUCCESS') {
                $detail_array = array();
                $detail_array['pay_amount'] = ncPriceFormat($details[1]);
                $detail_array['pay_time'] = time();
                
                $model_refund = Model('refund_return');
                $refund = array();
                $detail_info = array();
                $consume_array = array();
                
                if ($_GET['refund'] == "vr") {
                    $model_refund = Model('vr_refund');//虚拟订单退款
                    $detail_info = $model_refund->getDetailInfo(array('batch_no'=> $batch_no));//退款详细
                    $refund_id = $detail_info['refund_id'];
                    $refund = $model_refund->getRefundInfo(array('refund_id'=> $refund_id));
                    $consume_array['consume_remark'] = '支付宝在线退款成功（到账有延迟），虚拟退款单号：'.$refund['refund_sn'];
                } else {
                    $detail_info = $model_refund->getDetailInfo(array('batch_no'=> $batch_no));//退款详细
                    $refund_id = $detail_info['refund_id'];
                    $refund = $model_refund->getRefundReturnInfo(array('refund_id'=> $refund_id));
                    $consume_array['consume_remark'] = '支付宝在线退款成功（到账有延迟），退款退货单号：'.$refund['refund_sn'];
                }
                $model_refund->editDetail(array('batch_no'=> $batch_no), $detail_array);
                $result = "success";
                
                $consume_array['member_id'] = $refund['buyer_id'];
                $consume_array['member_name'] = $refund['buyer_name'];
                $consume_array['consume_amount'] = $detail_array['pay_amount'];
                $consume_array['consume_time'] = time();
                
                if ($detail_info['pay_time'] == 0) {
                    QueueClient::push('addConsume', $consume_array);
                }
            }
        }
        echo $result;exit;
    }
}
