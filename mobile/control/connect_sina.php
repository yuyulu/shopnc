<?php
/**
 * 新浪微博登录 v3-b13
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */


defined('In33hao') or exit('Access Invalid!');

class connect_sinaControl extends mobileHomeControl{
    public function __construct(){
        parent::__construct();
        //Language::read("home_login_register,home_login_index,home_sconnect");
        /**
         * 判断新浪微博登录功能是否开启
         */
        if (C('sina_isuse') != 1){
             output_error('系统未开启新浪微博登录功能');//'系统未开启QQ互联功能'
        }
        if (!$_SESSION['slast_key']){
             output_error('系统错误');//'系统错误'
        }
    }
    /**
     * 首页
     */
    public function indexOp(){
        /**
         * 检查登录状态
         */		 
        if(!empty($_COOKIE['key'])){
            $this->bindsinaOp();
        }else {
            $this->autologin();
            $this->registerOp();
        }
    }
    /**
     * 新浪微博账号绑定新用户
     */
    public function registerOp(){
        //实例化模型
        $model_member   = Model('member');
        if (chksubmit()){
            $update_info    = array();
            $update_info['member_passwd']= md5(trim($_POST["password"]));
            if(!empty($_POST["email"])) {
                $update_info['member_email']= $_POST["email"];
                $_SESSION['member_email']= $_POST["email"];
            }
            $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_info);
            showMessage(Language::get('nc_common_save_succ'),SHOP_SITE_URL);
        }else{
            //检查登录状态
            //$model_member->checkloginMember();
            //获取新浪微博账号信息
            require_once (BASE_PATH.DS.'api'.DS.'sina'.DS.'saetv2.ex.class.php');
            $c = new SaeTClientV2( C('sina_wb_akey'), C('sina_wb_skey') , $_SESSION['slast_key']['access_token']);
            $sinauser_info = $c->show_user_by_id($_SESSION['slast_key']['uid']);//根据ID获取用户等基本信息
            
            $sina_str = serialize($sinauser_info);
            $user_array = array();
            $user_passwd = rand(100000, 999999);
            /**
             * 会员添加
             */
            $user_array['member_name']      = $sinauser_info['screen_name'];
            $user_array['member_passwd']    = $user_passwd;
            $user_array['member_email']     = '';
            $user_array['member_sinaopenid']    = $_SESSION['slast_key']['uid'];//sina openid
            $user_array['member_sinainfo']  = $sina_str;//sina 信息
            $rand = rand(100, 899);
            if(strlen($user_array['member_name']) < 3) $user_array['member_name']       = $sinauser_info['screen_name'].$rand;
            $check_member_name  = $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
            $result = 0;
            if(empty($check_member_name)) {
                $result = $model_member->addMember($user_array);
            }else {
                for ($i = 1;$i < 999;$i++) {
                    $rand += $i;
                    $user_array['member_name'] = trim($sinauser_info['screen_name']).$rand;
                    $check_member_name  = $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
                    if(empty($check_member_name)) {
                        $result = $model_member->addMember($user_array);
                        break;
                    }
                }
            }
            if($result) {
                //$avatar = @copy($sinauser_info['avatar_large '],BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_$result.jpg");
                $update_info    = array();
                if($avatar) {
                    $update_info['member_avatar']   = "avatar_$result.jpg";
                    $model_member->editMember(array('member_id'=>$result),$update_info);
                }
                $member_info = $model_member->getMemberInfo(array('member_name'=>$user_array['member_name']));
				$token = $this->_get_token($member_info['member_id'], $member_info['member_name'], 'wap');
                $state_data['key'] = $token;
                $state_data['username'] = $member_info['member_name'];
                $state_data['userid'] = $member_info['member_id'];
					$url = WAP_SITE_URL.'/tmpl/member/member.html?username='.$state_data['username'].'&key='.$state_data['key'];
					@header("location:$url");	
            } else {
                output_error('会员注册失败');//"会员注册失败"
            }
        }
    }
	/**
     * 已有用户绑定新浪微博账号
     */
    public function bindsinaOp(){
        $model_member   = Model('member');
        //验证新浪账号用户是否已经存在
        $array  = array();
        $array['member_sinaopenid'] = $_SESSION['slast_key']['uid'];
        $member_info = $model_member->getMemberInfo($array);
        if (is_array($member_info) && count($member_info)>0){
            unset($_SESSION['slast_key']['uid']);
			
            output_error('您的账号已经被管理员禁用，请联系平台客服进行核实');
        }
        //处理sina账号信息
        require_once (BASE_PATH.DS.'api'.DS.'sina'.DS.'saetv2.ex.class.php');
        $c = new SaeTClientV2( C('sina_wb_akey'), C('sina_wb_skey') , $_SESSION['slast_key']['access_token']);
        $sinauser_info = $c->show_user_by_id($_SESSION['slast_key']['uid']);//根据ID获取用户等基本信息
        $sina_str = serialize($sinauser_info);
        $edit_state     = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),array('member_sinaopenid'=>$_SESSION['slast_key']['uid'], 'member_sinainfo'=>$sina_str));
        if ($edit_state){
            output_error('绑定QQ成功');
        }else {
	    output_error('绑定新浪失败');//'绑定QQ失败'
        }
    }
    /**
     * 绑定新浪微博账号后自动登录
     */
    public function autologin(){
        //查询是否已经绑定该新浪微博账号,已经绑定则直接跳转
        $model_member   = Model('member');
        $array  = array();
        $array['member_sinaopenid'] = $_SESSION['slast_key']['uid'];
        $member_info = $model_member->getMemberInfo($array);
        if (is_array($member_info) && count($member_info)>0){
            if(!$member_info['member_state']){//1为启用 0 为禁用
                output_error('您的账号已经被管理员禁用,请联系平台客服进行核实');
            }

			$token = $this->_get_token($member_info['member_id'], $member_info['member_name'], 'wap');
			$url = WAP_SITE_URL.'/tmpl/member/member.html?username='.$member_info['username'].'&key='.$token;
			@header("location:$url");exit;
        }
    }
    
	/**
     * 登录生成token
     */
    public function _get_token($member_id, $member_name, $client) {
        $model_mb_user_token = Model('mb_user_token');
        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $client;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }

    }
}
