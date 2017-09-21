<?php
/**
 * 聊天记录查询
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class chat_logControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        $add_time_to = date("Y-m-d");
        $time_from = array();
        $time_from['7'] = strtotime($add_time_to)-60*60*24*7;
        $time_from['90'] = strtotime($add_time_to)-60*60*24*90;
        $add_time_from = date("Y-m-d",$time_from['90']);
        Tpl::output('minDate', $add_time_from);//只能查看3个月内数据
        Tpl::output('maxDate', $add_time_to);
        if (empty($_GET['add_time_from']) || $_GET['add_time_from'] < $add_time_from) {//默认显示7天内数据
            $_GET['add_time_from'] = date("Y-m-d",$time_from['7']);
        }
        if (empty($_GET['add_time_to']) || $_GET['add_time_to'] > $add_time_to) {
            $_GET['add_time_to'] = $add_time_to;
        }
    }

    public function indexOp() {
        $this->chat_logOp();
    }

    /**
     * 聊天记录查询
     */
    public function chat_logOp() {
						
		Tpl::setDirquna('shop');
        Tpl::showpage('chat_log.list');
    }


    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_chat = Model('web_chat');
        $f_member = array();//发消息人
        $t_member = array();//收消息人
        $log_list = array();
        $f_name = trim($_GET['f_name']);
        if (!empty($f_name)) {
            $condition = array();
            $condition['member_name'] = $f_name;
            $f_member = $model_chat->getMemberInfo($condition);
        }
        $t_name = trim($_GET['t_name']);
        if (!empty($t_name)) {
            $condition = array();
            $condition['member_name'] = $t_name;
            $t_member = $model_chat->getMemberInfo($condition);
        }
        if ($f_member['member_id'] > 0 && $t_member['member_id'] > 0) {//验证账号
            $condition = array();
            $condition['add_time_from'] = trim($_GET['add_time_from']);
            $condition['add_time_to'] = trim($_GET['add_time_to']);
            $condition['f_id'] = intval($f_member['member_id']);
            $condition['t_id'] = intval($t_member['member_id']);

            $param = array('add_time', 'f_id', 't_id');
            $order = 'add_time desc';
            if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
                $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
            }

            $page = $_POST['rp'];
            $log_list = $model_chat->getLogFromList($condition,$page, $order);
        }

        $data = array();
        $data['now_page'] = empty($log_list) ? 0 : $model_chat->shownowpage();
        $data['total_num'] = empty($log_list) ? 0 : $model_chat->gettotalnum();
        foreach ($log_list as $value) {
            $param = array();
            $param['operation'] = "<a class='btn green' href='javascript:void(0);' onclick='ajax_form(\"login\",\"消息内容\",\"". urlAdminShop('chat_log', 'chat_info', array('id' => $value['m_id'])) ."\",640)' class='url'><i class='fa fa-list-alt'></i>查看</a>";
            $param['t_msg'] = $value['t_msg'];
            $param['add_time'] = date('Y-m-d H:i:s', $value['add_time']);
            $param['f_name'] = $value['f_name'];
            $param['f_id'] = $value['f_id'];
            $param['f_store_name'] = '--';
            $param['f_store_id'] = '--';
            $param['f_seller_name'] = '--';
            if ($value['f_id'] == $f_member['member_id'] && $f_member['store_id'] > 0) {
                $param['f_store_name'] = $f_member['store_name'];
                $param['f_store_id'] = $f_member['store_id'];
                $param['f_seller_name'] = $f_member['seller_name'];
            }
            if ($value['f_id'] == $t_member['member_id'] && $t_member['store_id'] > 0) {
                $param['f_store_name'] = $t_member['store_name'];
                $param['f_store_id'] = $t_member['store_id'];
                $param['f_seller_name'] = $t_member['seller_name'];
            }
            $param['f_ip'] = $value['f_ip'];
            $param['t_name'] = $value['t_name'];
            $param['t_id'] = $value['t_id'];
            $param['t_store_name'] = '--';
            $param['t_store_id'] = '--';
            $param['t_seller_name'] = '--';
            if ($value['t_id'] == $t_member['member_id'] && $t_member['store_id'] > 0) {
                $param['t_store_name'] = $t_member['store_name'];
                $param['t_store_id'] = $t_member['store_id'];
                $param['t_seller_name'] = $t_member['seller_name'];
            }
            if ($value['t_id'] == $f_member['member_id'] && $f_member['store_id'] > 0) {
                $param['t_store_name'] = $f_member['store_name'];
                $param['t_store_id'] = $f_member['store_id'];
                $param['t_seller_name'] = $f_member['seller_name'];
            }
            $data['list'][$value['m_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 聊天内容查询
     */
    public function msg_logOp() {
        if (!empty($_GET['msg'])) {
            $model_chat = Model('web_chat');
            $condition = array();
            $add_time_from = strtotime($_GET['add_time_from']);
            $add_time_to = strtotime($_GET['add_time_to']);
            $condition['add_time'] = array('time',array($add_time_from,$add_time_to));
            $condition['t_msg'] = array('like','%'.trim($_GET['msg']).'%');
            $log_list = $model_chat->getLogList($condition,15);
            $log_list = array_reverse($log_list);
            Tpl::output('log_list',$log_list);
            Tpl::output('show_page',$model_chat->showpage());
        }
						
		Tpl::setDirquna('shop');
        Tpl::showpage('chat_msg_log.list');
    }

    /**
     * 输出XML数据
     */
    public function get_msg_xmlOp() {
        $model_chat = Model('web_chat');
        $log_list = array();
        if (!empty($_GET['msg'])) {
            $add_time_from = strtotime($_GET['add_time_from']);
            $add_time_to = strtotime($_GET['add_time_to']);
            $condition['add_time'] = array('time',array($add_time_from,$add_time_to));
            $condition['t_msg'] = array('like','%'.trim($_GET['msg']).'%');

            $param = array('add_time', 'f_id', 't_id');
            $order = 'add_time desc';
            if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
                $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
            }

            $page = $_POST['rp'];
            $log_list = $model_chat->getLogList($condition,$page, $order);
        }

        $data = array();
        $data['now_page'] = empty($log_list) ? 0 : $model_chat->shownowpage();
        $data['total_num'] = empty($log_list) ? 0 : $model_chat->gettotalnum();
        foreach ($log_list as $value) {
            $param = array();
            $param['operation'] = "<a class='btn green' href='index.php?act=chat_log&op=chat_log&f_name=".$value['f_name']."&t_name=".$value['t_name']."&add_time_from=".date("Y-m-d",$value['add_time'])."&add_time_to=".date("Y-m-d",$value['add_time'])."' class='url'><i class='fa fa-list-alt'></i>详情</a>";
            $param['t_msg'] = $value['t_msg'];
            $param['add_time'] = date('Y-m-d H:i:s', $value['add_time']);
            $param['f_name'] = $value['f_name'];
            $param['f_id'] = $value['f_id'];
            $param['f_ip'] = $value['f_ip'];
            $param['t_name'] = $value['t_name'];
            $param['t_id'] = $value['t_id'];
            $data['list'][$value['m_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    public function chat_infoOp() {
        $id = $_GET['id'];
        $chat_info = Model('web_chat')->getLogInfo(array('m_id' => $id));
        Tpl::output('chat_info', $chat_info);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('chat_log.info', 'null_layout');
    }
}
