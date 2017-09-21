<?php
/**
 * 我的余额
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买正版
 */



defined('In33hao') or exit('Access Invalid!');

class member_fundControl extends mobileMemberControl {
    public function __construct(){
        parent::__construct();
    }
    /**
     * 预存款日志列表
     */
    public function predepositlogOp(){
        $model_predeposit = Model('predeposit');
        $where = array();
        $where['lg_member_id'] = $this->member_info['member_id'];
        $where['lg_av_amount'] = array('neq',0);
        $list = $model_predeposit->getPdLogList($where, $this->page, '*', 'lg_id desc');
        $page_count = $model_predeposit->gettotalpage();
        if ($list) {
            foreach($list as $k=>$v){
                $v['lg_add_time_text'] = @date('Y-m-d H:i:s',$v['lg_add_time']);
                $list[$k] = $v;
            }
        }
        output_data(array('list' => $list), mobile_page($page_count));
    }
    /**
     * 充值卡余额变更日志
     */
    public function rcblogOp()
    {
        $model_rcb_log = Model('rcb_log');
        $where = array();
        $where['member_id'] = $this->member_info['member_id'];
        $where['available_amount'] = array('neq',0);
        $log_list = $model_rcb_log->getRechargeCardBalanceLogList($where, $this->page, '', 'id desc');
        $page_count = $model_rcb_log->gettotalpage();
        if ($log_list) {
            foreach($log_list as $k=>$v){
                $v['add_time_text'] = @date('Y-m-d H:i:s',$v['add_time']);
                $log_list[$k] = $v;
            }
        }
        output_data(array('log_list' => $log_list), mobile_page($page_count));
    }
    /**
     * 充值明细
     */
    public function pdrechargelistOp(){
        $where = array();
        $where['pdr_member_id'] = $this->member_info['member_id'];
        $model_pd = Model('predeposit');
        $list = $model_pd->getPdRechargeList($where, $this->page,'*','pdr_id desc');
        $page_count = $model_pd->gettotalpage();
        if ($list) {
            foreach($list as $k=>$v){
                $v['pdr_add_time_text'] = @date('Y-m-d H:i:s',$v['pdr_add_time']);
                $v['pdr_payment_state_text'] = $v['pdr_payment_state']==1?'已支付':'未支付';
                $list[$k] = $v;
            }
        }
        output_data(array('list' => $list), mobile_page($page_count));
    }
    /**
     * 提现记录
     */
    public function pdcashlistOp(){
        $where = array();
        $where['pdc_member_id'] =  $this->member_info['member_id'];
        $model_pd = Model('predeposit');
        $list = $model_pd->getPdCashList($where, $this->page, '*', 'pdc_id desc');
        $page_count = $model_pd->gettotalpage();
        if ($list) {
            foreach($list as $k=>$v){
                $v['pdc_add_time_text'] = @date('Y-m-d H:i:s',$v['pdc_add_time']);
                $v['pdc_payment_time_text'] = @date('Y-m-d H:i:s',$v['pdc_payment_time']);
                $v['pdc_payment_state_text'] = $v['pdc_payment_state']==1?'已支付':'未支付';
                $list[$k] = $v;
            }
        }
        output_data(array('list' => $list), mobile_page($page_count));
    }
    /**
     * 充值卡充值
     */
    public function rechargecard_addOp()
    {
        $param = $_POST;
        $rc_sn = trim($param["rc_sn"]);
        if (!$rc_sn) {
            output_error('请输入平台充值卡号');
        }
        if (!Model('apiseccode')->checkApiSeccode($param["codekey"],$param['captcha'])) {
            output_error('验证码错误');
        }
        try {
            Model('predeposit')->addRechargeCard($rc_sn, array('member_id'=>$this->member_info['member_id'],'member_name'=>$this->member_info['member_name']));
            output_data('1');
        } catch (Exception $e) {
            output_error($e->getMessage());
        }
    }
    /**
     * 预存款提现记录详细
     */
    public function pdcashinfoOp(){
        $param = $_GET;
        $pdc_id = intval($param["pdc_id"]);
        if ($pdc_id <= 0){
            output_error('参数错误');
        }
        $where = array();
        $where['pdc_member_id'] =  $this->member_info['member_id'];
        $where['pdc_id'] = $pdc_id;
        $info = Model('predeposit')->getPdCashInfo($where);
        if (!$info){
            output_error('参数错误');
        }
        $info['pdc_add_time_text'] = $info['pdc_add_time']?@date('Y-m-d H:i:s',$info['pdc_add_time']):'';
        $info['pdc_payment_time_text'] = $info['pdc_payment_time']?@date('Y-m-d H:i:s',$info['pdc_payment_time']):'';
        $info['pdc_payment_state_text'] = $info['pdc_payment_state']==1?'已支付':'未支付';
        output_data(array('info' => $info));
    }
}