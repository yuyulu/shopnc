<?php
/**
 * 积分管理
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class pointsControl extends SystemControl{
    const EXPORT_SIZE = 5000;
    public function __construct(){
        parent::__construct();
        Language::read('points');
        //判断系统是否开启积分功能
        if (C('points_isuse') != 1){
            showMessage(Language::get('admin_points_unavailable'),'index.php?act=setting','','error');
        }
    }

    public function indexOp() {
        $this->pointslogOp();
    }

    /**
     * 积分添加
     */
    public function addpointsOp(){
        if (chksubmit()){

            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["member_id"], "require"=>"true", "message"=>Language::get('admin_points_member_error_again')),
                array("input"=>$_POST["pointsnum"], "require"=>"true",'validator'=>'Compare','operator'=>' >= ','to'=>1,"message"=>Language::get('admin_points_points_min_error'))
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error,'','','error');
            }
            //查询会员信息
            $obj_member = Model('member');
            $member_id = intval($_POST['member_id']);
            $member_info = $obj_member->getMemberInfo(array('member_id'=>$member_id));

            if (!is_array($member_info) || count($member_info)<=0){
                showMessage(Language::get('admin_points_userrecord_error'),'index.php?act=points&op=addpoints','','error');
            }

            $pointsnum = intval($_POST['pointsnum']);
            if ($_POST['operatetype'] == 2 && $pointsnum > intval($member_info['member_points'])){
                showMessage(Language::get('admin_points_points_short_error').$member_info['member_points'],'index.php?act=points&op=addpoints','','error');
            }

            $obj_points = Model('points');
            $insert_arr['pl_memberid'] = $member_info['member_id'];
            $insert_arr['pl_membername'] = $member_info['member_name'];
            $admininfo = $this->getAdminInfo();
            $insert_arr['pl_adminid'] = $admininfo['id'];
            $insert_arr['pl_adminname'] = $admininfo['name'];
            if ($_POST['operatetype'] == 2){
                $insert_arr['pl_points'] = -$_POST['pointsnum'];
            }else {
                $insert_arr['pl_points'] = $_POST['pointsnum'];
            }
            if ($_POST['pointsdesc']){
                $insert_arr['pl_desc'] = trim($_POST['pointsdesc']);
            } else {
                $insert_arr['pl_desc'] = Language::get('admin_points_system_desc');
            }
            $result = $obj_points->savePointsLog('system',$insert_arr,true);
            if ($result){
                $this->log(L('admin_points_mod_tip').$member_info['member_name'].'['.(($_POST['operatetype'] == 2)?'':'+').strval($insert_arr['pl_points']).']',null);
                showMessage(Language::get('nc_common_save_succ'),'index.php?act=points&op=addpoints');
            }else {
                showMessage(Language::get('nc_common_save_fail'),'index.php?act=points&op=addpoints','','error');
            }
        }else {
			Tpl::setDirquna('shop');
            Tpl::showpage('points.add');
        }
    }
    public function checkmemberOp(){
        $name = trim($_GET['name']);
        if (!$name){
            echo ''; die;
        }
        /**
         * 转码
         */
        if(strtoupper(CHARSET) == 'GBK'){
            $name = Language::getGBK($name);
        }
        $obj_member = Model('member');
        $member_info = $obj_member->getMemberInfo(array('member_name'=>$name));
        if (is_array($member_info) && count($member_info)>0){
            if(strtoupper(CHARSET) == 'GBK'){
                $member_info['member_name'] = Language::getUTF8($member_info['member_name']);
            }
            echo json_encode(array('id'=>$member_info['member_id'],'name'=>$member_info['member_name'],'points'=>$member_info['member_points']));
        }else {
            echo ''; die;
        }
    }
    /**
     * 积分日志列表
     */
    public function pointslogOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('points.log');
    }

    /**
     * 规则设置
     */
    public function settingOp() {
        Language::read('setting');
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['points_reg'] = intval($_POST['points_reg'])?$_POST['points_reg']:0;
            $update_array['points_login'] = intval($_POST['points_login'])?$_POST['points_login']:0;
            $update_array['points_comments'] = intval($_POST['points_comments'])?$_POST['points_comments']:0;
            $update_array['points_orderrate'] = intval($_POST['points_orderrate'])?$_POST['points_orderrate']:0;
            $update_array['points_ordermax'] = intval($_POST['points_ordermax'])?$_POST['points_ordermax']:0;
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log('积分设置',1);
                showMessage(L('nc_common_save_succ'));
            }else {
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
		Tpl::setDirquna('shop');
        Tpl::showpage('points.setting');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $where = array();
        if ($_POST['query'] != '') {
            switch($_POST['qtype']){
                case 'pl_memberid':
                    $where['pl_memberid'] = $_POST['query'];
                    break;
                case 'pl_membername_like':
                    $where['pl_membername'] = array('like',"%{$_POST['query']}%");
                    break;
                case 'pl_adminname_like':
                    $where['pl_adminname'] = array('like',"%{$_POST['query']}%");
                    break;
            }
        }
        $order = '';
        $param = array('pl_id','pl_memberid','pl_membername','pl_points','pl_addtime');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = !empty($_POST['rp']) ? intval($_POST['rp']) : 15;
        $points_model = Model('points');
        $list_log = $points_model->getPointsLogList($where, '*', 0, $page, $order);
        if (empty($list_log)) $list_log = array();
        $data = array();
        $data['now_page'] = $points_model->shownowpage();
        $data['total_num'] = $points_model->gettotalnum();
        foreach ($list_log as $value) {
            $param = array();
            $param['operation'] = "--";
            $param['pl_id'] = $value['pl_id'];
            $param['pl_memberid'] = $value['pl_memberid'];
            $param['pl_membername'] = $value['pl_membername'];
            $param['pl_points'] = $value['pl_points'];
            $param['pl_stage'] = $value['stagetext'];
            $param['pl_addtime'] = $value['addtimetext'];
            $param['pl_desc'] = $value['pl_desc'];
            $param['pl_adminname'] = $value['pl_adminname'];
            $data['list'][$value['pl_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
}
