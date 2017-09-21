<?php
/**
 * 我的代金券
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_voucherControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
        // 判断系统是否开启代金券功能
        if (intval(C('voucher_allow')) !== 1) {
            output_error('系统未开启代金券功能');
        }
    }
    /**
     * 我的代金券列表
     */
    public function voucher_listOp() {
        $param = $_POST;

        $model_voucher = Model('voucher');
        $voucher_list = $model_voucher->getMemberVoucherList($this->member_info['member_id'], $param['voucher_state'], $this->page, 'voucher_state asc,voucher_id desc');
        $page_count = $model_voucher->gettotalpage();
        output_data(array('voucher_list' => $voucher_list), mobile_page($page_count));
    }
    /**
     * 卡密领取代金券
     */
    public function voucher_pwexOp()
    {
        $param = $_POST;

        $pwd_code = trim($param["pwd_code"]);
        if (!$pwd_code){
            output_error('请输入代金券卡密');
        }
        if (!Model('apiseccode')->checkApiSeccode($param["codekey"],$param['captcha'])) {
            output_error('验证码错误');
        }
        // 查询代金券
        $model_voucher = Model('voucher');
        $voucher_info = $model_voucher->getVoucherInfo(array('voucher_pwd'=>md5($pwd_code)));
        if (!$voucher_info) {
            output_error('代金券卡密错误');
        }
        if ($this->member_info['store_id'] == $voucher_info['voucher_store_id']) {
            output_error('不能领取自己店铺的代金券');
        }
        if ($voucher_info['voucher_owner_id'] > 0) {
            output_error('该代金券卡密已被使用');
        }
        $where = array();
        $where['voucher_id'] = $voucher_info['voucher_id'];
        $update_arr = array();
        $update_arr['voucher_owner_id'] = $this->member_info['member_id'];
        $update_arr['voucher_owner_name'] = $this->member_info['member_name'];
        $update_arr['voucher_active_date'] = time();
        $result = $model_voucher->editVoucher($update_arr, $where, $this->member_info['member_id']);
        if ($result) {
            // 更新代金券模板
            $update_arr = array();
            $update_arr['voucher_t_giveout'] = array('exp', 'voucher_t_giveout+1');
            $model_voucher->editVoucherTemplate(array('voucher_t_id'=>$voucher_info['voucher_t_id']), $update_arr);
            output_data('1');
        } else {
            output_error('代金券领取失败');
        }
    }
    /**
     * 免费领取代金券
     */
    public function voucher_freeexOp() {
        $param = $_POST;

        $t_id = intval($param['tid']);
        if($t_id <= 0){
            output_error('代金券信息错误');
        }
        $model_voucher = Model('voucher');
        //验证是否可领取代金券
        $data = $model_voucher->getCanChangeTemplateInfo($t_id, $this->member_info['member_id'], $this->member_info['store_id']);
        if ($data['state'] == false){
            output_error($data['msg']);
        }
        try {
            $model_voucher->beginTransaction();
            //添加代金券信息
            $data = $model_voucher->exchangeVoucher($data['info'], $this->member_info['member_id'], $this->member_info['member_name']);
            if ($data['state'] == false) {
                throw new Exception($data['msg']);
            }
            $model_voucher->commit();
            output_data('1');
        } catch (Exception $e) {
            $model_voucher->rollback();
            output_error($e->getMessage());
        }
    }
}
