<?php
/**
 * 我的红包
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_redpacketControl extends mobileMemberControl {
    private $redpacket_state_arr;

    public function __construct() {
        parent::__construct();
        //判断系统是否开启红包功能
        if (C('redpacket_allow') != 1){
            output_error('系统未开启红包功能');
        }
        $model_redpacket = Model('redpacket');
        $this->redpacket_state_arr = $model_redpacket->getRedpacketState();
    }
    /*
     * 我的红包列表
     */
    public function redpacket_listOp(){
        $param = $_POST;
        $model_redpacket = Model('redpacket');
        //更新红包过期状态
        $model_redpacket->updateRedpacketExpire($this->member_info['member_id']);
        //查询红包
        $where = array();
        $where['rpacket_owner_id'] = $this->member_info['member_id'];
        $rp_state_select = trim($param['rp_state']);
        if ($rp_state_select){
            $where['rpacket_state'] = $this->redpacket_state_arr[$rp_state_select]['sign'];
        }
        $redpacket_list = $model_redpacket->getRedpacketList($where, '*', 0, $this->page, 'rpacket_state asc,rpacket_id desc');
        $page_count = $model_redpacket->gettotalpage();
        output_data(array('redpacket_list' => $redpacket_list), mobile_page($page_count));
    }
    /**
     * 卡密领取红包
     */
    public function rp_pwexOp(){
        $param = $_POST;
        $pwd_code = trim($param["pwd_code"]);
        if (!$pwd_code) {
            output_error('请输入红包卡密');
        }
        if (!Model('apiseccode')->checkApiSeccode($param["codekey"],$param['captcha'])) {
            output_error('验证码错误');
        }
        //查询红包
        $model_redpacket = Model('redpacket');
        $redpacket_info = $model_redpacket->getRedpacketInfo(array('rpacket_pwd'=>md5($pwd_code)));
        if(!$redpacket_info){
            output_error('红包卡密错误');
        }
        if($redpacket_info['rpacket_owner_id'] > 0){
            output_error('该红包卡密已被使用');
        }
        $where = array();
        $where['rpacket_id'] = $redpacket_info['rpacket_id'];
        $update_arr = array();
        $update_arr['rpacket_owner_id'] = $this->member_info['member_id'];
        $update_arr['rpacket_owner_name'] = $this->member_info['member_name'];
        $update_arr['rpacket_active_date'] = time();
        $result = $model_redpacket->editRedpacket($where, $update_arr, $this->member_info['member_id']);
        if($result){
            //更新红包模板
            $update_arr = array();
            $update_arr['rpacket_t_giveout'] = array('exp','rpacket_t_giveout+1');
            $model_redpacket->editRptTemplate(array('rpacket_t_id'=>$redpacket_info['rpacket_t_id']),$update_arr);
            output_data('1');
        } else {
            output_error('红包领取失败');
        }
    }
}
