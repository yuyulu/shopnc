<?php
/**
 * 会员聊天
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class member_chatControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * node连接参数
     */
    public function get_node_infoOp() {
        $output_data = array('node_chat' => C('node_chat'),'node_site_url' => NODE_SITE_URL,'resource_site_url' => RESOURCE_SITE_URL);
        $model_chat = Model('web_chat');
        $member_id = $this->member_info['member_id'];
        $member_info = $model_chat->getMember($member_id);
        $output_data['member_info'] = $member_info;
        $u_id = intval($_GET['u_id']);
        if ($u_id > 0) {
            $member_info = $model_chat->getMember($u_id);
            $output_data['user_info'] = $member_info;
        }
        $goods_id = intval($_GET['chat_goods_id']);
        if ($goods_id > 0) {
            $goods = $model_chat->getGoodsInfo($goods_id);
            $output_data['chat_goods'] = $goods;
        }
        output_data($output_data);
    }

    /**
     * 最近联系人
     */
    public function get_user_listOp() {
        $member_list = array();
        $model_chat = Model('web_chat');

        $member_id = $this->member_info['member_id'];
        $member_name = $this->member_info['member_name'];
        $n = intval($_POST['n']);
        if ($n < 1) $n = 50;
        if(intval($_POST['recent']) != 1) {
            $member_list = $model_chat->getFriendList(array('friend_frommid'=> $member_id),$n,$member_list);
        }
        $add_time = date("Y-m-d");
        $add_time30 = strtotime($add_time)-60*60*24*30;
        $member_list = $model_chat->getRecentList(array('f_id'=> $member_id,'add_time'=>array('egt',$add_time30)),10,$member_list);
        $member_list = $model_chat->getRecentFromList(array('t_id'=> $member_id,'add_time'=>array('egt',$add_time30)),10,$member_list);
        $member_info = array();
        $member_info = $model_chat->getMember($member_id);

        $node_info = array();
        $node_info['node_chat'] = C('node_chat');
        $node_info['node_site_url'] = NODE_SITE_URL;
        output_data(array('node_info' => $node_info,'member_info' => $member_info,'list' => $member_list));
    }

    /**
     * 会员信息
     *
     */
    public function get_infoOp(){
        $val = '';
        $member = array();
        $model_chat = Model('web_chat');
        $types = array('member_id','member_name','store_id','member');
        $key = $_POST['t'];
        $member_id = intval($_POST['u_id']);
        if($member_id > 0 && trim($key) != '' && in_array($key,$types)){
            $member_info = $model_chat->getMember($member_id);
            output_data(array('member_info' => $member_info));
        } else {
            output_error('参数错误');
        }
    }

    /**
     * 发消息
     *
     */
    public function send_msgOp(){
        $member = array();
        $model_chat = Model('web_chat');
        $member_id = $this->member_info['member_id'];
        $member_name = $this->member_info['member_name'];
        $t_id = intval($_POST['t_id']);
        $t_name = trim($_POST['t_name']);
        $member = $model_chat->getMember($t_id);
        if ($t_name != $member['member_name']) output_error('接收消息会员账号错误');

        $msg = array();
        $msg['f_id'] = $member_id;
        $msg['f_name'] = $member_name;
        $msg['t_id'] = $t_id;
        $msg['t_name'] = $t_name;
        $msg['t_msg'] = trim($_POST['t_msg']);
        if ($msg['t_msg'] != '') $chat_msg = $model_chat->addMsg($msg);
        if ($chat_msg['m_id']) {
            $goods_id = intval($_POST['chat_goods_id']);
            if ($goods_id > 0) {
                $goods = $model_chat->getGoodsInfo($goods_id);
                $chat_msg['chat_goods'] = $goods;
            }
            output_data(array('msg' => $chat_msg));
        } else {
            output_error('发送失败，请稍后重新发送');
        }
    }

    /**
     * 删除最近联系人消息
     *
     */
    public function del_msgOp(){
        $model_chat = Model('web_chat');
        $member_id = $this->member_info['member_id'];
        $t_id = intval($_POST['t_id']);
        $condition = array();
        $condition['f_id'] = $member_id;
        $condition['t_id'] = $t_id;
        $model_chat->delChatMsg($condition);
        $condition = array();
        $condition['t_id'] = $member_id;
        $condition['f_id'] = $t_id;
        $model_chat->delChatMsg($condition);
        output_data(1);
    }

    /**
     * 商品图片和名称
     *
     */
    public function get_goods_infoOp(){
        $model_chat = Model('web_chat');
        $goods_id = intval($_POST['goods_id']);
        $goods = $model_chat->getGoodsInfo($goods_id);
        output_data(array('goods' => $goods));
    }

    /**
     * 未读消息查询
     *
     */
    public function get_msg_countOp(){
        $model_chat = Model('web_chat');
        $member_id = $this->member_info['member_id'];
        $condition = array();
        $condition['t_id'] = $member_id;
        $condition['r_state'] = 2;
        $n = $model_chat->getChatMsgCount($condition);
        output_data($n);
    }

    /**
     * 聊天记录查询
     *
     */
    public function get_chat_logOp(){
        $member_id = $this->member_info['member_id'];
        $t_id = intval($_POST['t_id']);
        $add_time_to = date("Y-m-d");
        $time_from = array();
        $time_from['7'] = strtotime($add_time_to)-60*60*24*7;
        $time_from['15'] = strtotime($add_time_to)-60*60*24*15;
        $time_from['30'] = strtotime($add_time_to)-60*60*24*30;

        $key = $_POST['t'];
        if(trim($key) != '' && array_key_exists($key,$time_from)){
            $model_chat = Model('web_chat');
            $list = array();
            $condition_sql = " add_time >= '".$time_from[$key]."' ";
            $condition_sql .= " and ((f_id = '".$member_id."' and t_id = '".$t_id."') or (f_id = '".$t_id."' and t_id = '".$member_id."'))";
            $list = $model_chat->getLogList($condition_sql,$this->page);

            $total_page = $model_chat->gettotalpage();
            output_data(array('list' => $list), mobile_page($total_page));
        }
    }
}
