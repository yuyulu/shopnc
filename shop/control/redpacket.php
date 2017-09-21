<?php
/**
 * 领取免费红包
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class redpacketControl extends BaseHomeControl{
    public function __construct() {
        parent::__construct();
        //判断系统是否开启红包功能
        if (C('redpacket_allow') != 1){
            showDialog('系统未开启红包功能','index.php','error');
        }
        parent::checkLogin();
    }
    /**
     * 免费红包页面
     */
    public function getredpacketOp() {
        $t_id = intval($_GET['tid']);
        $error_url = getReferer();
        if (!$error_url){
            $error_url = 'index.php';
        }
        if($t_id <= 0){
            showDialog('红包信息错误',$error_url,'error');
        }
        $model_redpacket = Model('redpacket');
        //获取领取方式
        $gettype_array = $model_redpacket->getGettypeArr();
        //获取红包状态
        $templatestate_arr = $model_redpacket->getTemplateState();
        //查询红包模板详情
        $where = array();
        $where['rpacket_t_id'] = $t_id;
        $where['rpacket_t_gettype'] = $gettype_array['free']['sign'];
        $where['rpacket_t_state'] = $templatestate_arr['usable']['sign'];
        //$where['rpacket_t_start_date'] = array('elt',time());
        $where['rpacket_t_end_date'] = array('egt',time());
        $template_info = $model_redpacket->getRptTemplateInfo($where);
        if (empty($template_info)){
            showDialog('红包信息错误',$error_url,'error');
        }
        if ($template_info['rpacket_t_total']<=$template_info['rpacket_t_giveout']){//红包不存在或者已兑换完
            showDialog('红包已兑换完',$error_url,'error');
        }
        TPL::output('template_info',$template_info);
        Tpl::showpage('redpacket.getredpacket');
    }
    /**
     * 领取免费红包
     */
    public function getredpacketsaveOp() {
        $t_id = intval($_GET['tid']);
        if($t_id <= 0){
            showDialog('红包信息错误','','error');
        }
        $model_redpacket = Model('redpacket');
        //验证是否可领取红包
        $data = $model_redpacket->getCanChangeTemplateInfo($t_id, intval($_SESSION['member_id']));
        if ($data['state'] == false){
            showDialog($data['msg'], '', 'error');
        }
        try {
            $model_redpacket->beginTransaction();
            //添加红包信息
            $data = $model_redpacket->exchangeRedpacket($data['info'], $_SESSION['member_id'], $_SESSION['member_name']);
            if ($data['state'] == false) {
                throw new Exception($data['msg']);
            }
            $model_redpacket->commit();
            showDialog('红包领取成功', MEMBER_SITE_URL.'/index.php?act=member_redpacket&op=index', 'succ');
        } catch (Exception $e) {
            $model_redpacket->rollback();
            showDialog($e->getMessage(), '', 'error');
        }
        
    }
}
