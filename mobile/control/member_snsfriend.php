<?php
/**
 * 我的好友
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class member_snsfriendControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 查询会员
     */
    public function member_listOp() {
        $member_list = array();
        $model_member = Model('member');
        $condition = array();
        $condition['member_state'] = '1';
        $condition['member_id'] = array('neq',$this->member_info['member_id']);
        $condition['member_name'] = array('like','%'.trim($_POST['m_name']).'%');//会员名称
        $list = $model_member->getMemberList($condition, 'member_id,member_name,member_truename,member_avatar', $this->page);
        if(!empty($list) && is_array($list)) {
            foreach($list as $k => $v) {
                $member = array();
                $member['u_id'] = $v['member_id'];
                $member['u_name'] = $v['member_name'];
                $member['truename'] = $v['member_truename'];
                $member['avatar'] = getMemberAvatar($v['member_avatar']);
                $member_list[] = $member;
            }
        }
        $page_count = $model_member->gettotalpage();
        output_data(array('member_list' => $member_list), mobile_page($page_count));
    }

    /**
     * 好友列表
     */
    public function friend_listOp() {
        $model_chat = Model('web_chat');
        $member_id = $this->member_info['member_id'];
        $friend_list = $model_chat->getFriendList(array('friend_frommid'=> $member_id),$this->page);
        $page_count = $model_chat->gettotalpage();
        output_data(array('friend_list' => $friend_list), mobile_page($page_count));
    }

    /**
     * 添加好友
     */
    public function friend_addOp() {
        $member_info = array();
        $self_info = $this->member_info;
        $m_id = intval($_POST['m_id']);
        if ($m_id < 1 || $m_id == $self_info['member_id']){
            output_error('参数错误');
        }
        //验证会员信息
        $model_member = Model('member');
        $condition = array();
        $condition['member_state'] = '1';
        $condition['member_id'] = $m_id;
        $member_info = $model_member->getMemberInfo($condition);
        if(empty($member_info)){//验证会员信息
            output_error('会员信息错误');
        }
        $model_snsfriend = Model('sns_friend');
        $count = $model_snsfriend->countFriend(array('friend_tomid'=> $m_id,'friend_frommid'=> $self_info['member_id']));
        if($count > 0 ) {//判断是否已经存在好友记录
            output_error('已经是好友了');
        }
        $insert_arr = array();
        $insert_arr['friend_frommid'] = $self_info['member_id'];
        $insert_arr['friend_frommname'] = $self_info['member_name'];
        $insert_arr['friend_frommavatar'] = $self_info['member_avatar'];
        $insert_arr['friend_tomid'] = $member_info['member_id'];
        $insert_arr['friend_tomname'] = $member_info['member_name'];
        $insert_arr['friend_tomavatar'] = $member_info['member_avatar'];
        $insert_arr['friend_addtime'] = time();
        $friend_info = $model_snsfriend->getFriendRow(array('friend_frommid'=> $m_id,'friend_tomid'=> $self_info['member_id']));
        if(empty($friend_info)){
            $insert_arr['friend_followstate'] = '1';//单方关注
        }else{
            $insert_arr['friend_followstate'] = '2';//双方关注
        }
        $result = $model_snsfriend->addFriend($insert_arr);
        if ($result){
            if(!empty($friend_info)){//更新对方关注状态
                $model_snsfriend->editFriend(array('friend_followstate'=>'2'),array('friend_id'=> $friend_info['friend_id']));
            }
            output_data('1');
        }else{
            output_error('添加好友失败');
        }
    }

    /**
     * 删除好友
     */
    public function friend_delOp() {
        $m_id = intval($_POST['m_id']);
        if ($m_id <= 0){
            output_error('参数错误');
        }
        $model_snsfriend = Model('sns_friend');
        $condition = array();
        $condition['friend_tomid'] = $m_id;
        $condition['friend_frommid'] = $this->member_info['member_id'];
        $result = $model_snsfriend->delFriend($condition);
        if($result){
            //更新对方的关注状态
            $model_snsfriend->editFriend(array('friend_followstate'=>'1'),array('friend_frommid'=> $m_id,'friend_tomid'=> $this->member_info['member_id']));
            output_data('1');
        }else{
            output_error('删除好友失败');
        }
    }

}
