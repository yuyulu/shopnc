<?php
/**
 * 红包
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class member_redpacketControl extends BaseMemberControl{
    private $redpacket_state_arr;
    
    public function __construct() {
        parent::__construct();
        Language::read('member_layout');
        //判断系统是否开启红包功能
        if (C('redpacket_allow') != 1){
            showDialog('系统未开启红包功能',urlShop('member', 'home'),'error');
        }
        $model_redpacket = Model('redpacket');
        $this->redpacket_state_arr = $model_redpacket->getRedpacketState();
    }
    /*
     * 默认显示红包模版列表
     */
    public function indexOp() {
        $this->rp_listOp() ;
    }

    /*
     * 获取红包模版详细信息
     */
    public function rp_listOp(){
        $model_redpacket = Model('redpacket');
        //更新红包过期状态
        $model_redpacket->updateRedpacketExpire($_SESSION['member_id']);
        //查询红包
        $where = array();
        $where['rpacket_owner_id'] = $_SESSION['member_id'];
        $rp_state_select = trim($_GET['rp_state_select']);
        if ($rp_state_select){
            $where['rpacket_state'] = $this->redpacket_state_arr[$rp_state_select]['sign'];
        }
        $list = $model_redpacket->getRedpacketList($where, '*', 0, 10, 'rpacket_active_date desc');
        Tpl::output('list', $list);
        Tpl::output('redpacketstate_arr', $model_redpacket->getRedpacketState());
        Tpl::output('show_page',$model_redpacket->showpage(2)) ;
        $this->profile_menu('rp_list');
        Tpl::showpage('member_redpacket.list');
    }

    /**
     * 通过卡密绑定红包
     */
    public function rp_bindingOp(){
        if(chksubmit(false,true)){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["pwd_code"],"require" => "true","message" => '请输入红包卡密'),
            );
            $error = $obj_validate->validate();
            if ($error != '')
            {
                showDialog($error,'','error','submiting = false');
            }
            //查询红包
            $model_redpacket = Model('redpacket');
            $where = array();
            $where['rpacket_pwd'] = md5($_POST["pwd_code"]);
            $redpacket_info = $model_redpacket->getRedpacketInfo($where);
            if(!$redpacket_info){
                showDialog('红包卡密错误','','error','submiting = false');
            }
            if($redpacket_info['rpacket_owner_id'] > 0){
                showDialog('该红包卡密已被使用，不可重复领取','','error','submiting = false');
            }
            $where = array();
            $where['rpacket_id'] = $redpacket_info['rpacket_id'];
            $update_arr = array();
            $update_arr['rpacket_owner_id'] = $_SESSION['member_id'];
            $update_arr['rpacket_owner_name'] = $_SESSION['member_name'];
            $update_arr['rpacket_active_date'] = time();
            $result = $model_redpacket->editRedpacket($where, $update_arr, $_SESSION['member_id']);
            if($result){
                //更新红包模板
                $update_arr = array();
                $update_arr['rpacket_t_giveout'] = array('exp','rpacket_t_giveout+1');
                $model_redpacket->editRptTemplate(array('rpacket_t_id'=>$redpacket_info['rpacket_t_id']),$update_arr);
                showDialog('红包领取成功', 'index.php?act=member_redpacket&op=rp_list','succ');
            } else {
                showDialog('红包领取失败','','error','submiting = false');
            }
        }
        $this->profile_menu('rp_binding');
        Tpl::showpage('member_redpacket.binding');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'rp_list','menu_name'=>'我的红包','menu_url'=>'index.php?act=member_redpacket&op=rp_list'),
            2=>array('menu_key'=>'rp_binding','menu_name'=>'领取红包','menu_url'=>'index.php?act=member_redpacket&op=rp_binding'),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}