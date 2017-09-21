<?php
/**
 * 第三方账号登录
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class connectControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 登录开关状态
     */
    public function get_stateOp() {
        $logic_connect_api = Logic('connect_api');
        $state_array = $logic_connect_api->getStateInfo();
        
        $key = $_GET['t'];
        if(trim($key) != '' && array_key_exists($key,$state_array)){
            output_data($state_array[$key]);
        } else {
            output_data($state_array);
        }
    }
    /**
     * WAP页面微信登录回调
     */
    public function indexOp(){
        $logic_connect_api = Logic('connect_api');
        if(!empty($_GET['code'])) {
            $code = $_GET['code'];
            $client = 'wap';
            $user_info = $logic_connect_api->getWxUserInfo($code,'wap');
            if(!empty($user_info['unionid'])){
                $unionid = $user_info['unionid'];
                $model_member = Model('member');
                $member = $model_member->getMemberInfo(array('weixin_unionid'=> $unionid));
                $state_data = array();
                $token = 0;
                if(!empty($member)) {//会员信息存在时自动登录
                    $token = $logic_connect_api->getUserToken($member, $client);
                } else {//自动注册会员并登录
                    $info_data = $logic_connect_api->wxRegister($user_info, $client);
                    $token = $info_data['token'];
                    $member = $info_data['member'];
                    $state_data['password'] = $member['member_passwd'];
                }
                if($token) {
                    $state_data['key'] = $token;
                    $state_data['username'] = $member['member_name'];
                    $state_data['userid'] = $member['member_id'];
                    redirect(WAP_SITE_URL.'/tmpl/member/member.html?username='.$state_data['username'].'&key='.$state_data['key']);
                } else {
                    output_error('会员登录失败');
                }
            } else {
                output_error('微信登录失败');
            }
        } else {
            $_url = $logic_connect_api->getWxOAuth2Url();
            @header("location: ".$_url);
        }
    }
    /**
     * QQ互联获取应用唯一标识
     */
    public function get_qq_appidOp(){
        output_data(C('app_qq_akey'));
    }
    /**
     * 请求QQ互联授权
     */
    public function get_qq_oauth2Op(){
        $logic_connect_api = Logic('connect_api');
        $qq_url = $logic_connect_api->getQqOAuth2Url('api');
        @header("location: ".$qq_url);
    }
    /**
     * QQ互联获取回调信息
     */
    public function get_qq_infoOp(){
        $code = $_GET['code'];
        $token = $_GET['token'];
        $client = $_GET['client'];
        $logic_connect_api = Logic('connect_api');
        $user_info = $logic_connect_api->getQqUserInfo($code,$client,$token);
        if(!empty($user_info['openid'])){
            $qqopenid = $user_info['openid'];
            $model_member = Model('member');
            $member = $model_member->getMemberInfo(array('member_qqopenid'=> $qqopenid));
            $state_data = array();
            $token = 0;
            if(!empty($member)) {//会员信息存在时自动登录
                $token = $logic_connect_api->getUserToken($member, $client);
            } else {//自动注册会员并登录
                $info_data = $logic_connect_api->qqRegister($user_info, $client);
                $token = $info_data['token'];
                $member = $info_data['member'];
                $state_data['password'] = $member['member_passwd'];
            }
            if($token) {
                $state_data['key'] = $token;
                $state_data['username'] = $member['member_name'];
                $state_data['userid'] = $member['member_id'];
                if($client == 'wap'){
                    redirect(WAP_SITE_URL.'/tmpl/member/member.html?username='.$state_data['username'].'&key='.$state_data['key']);
                }
                output_data($state_data);
            } else {
                output_error('会员登录失败');
            }
        } else {
            output_error('QQ互联登录失败');
        }
    }
    /**
     * 新浪微博获取应用唯一标识
     */
    public function get_sina_appidOp(){
        output_data(C('app_sina_akey'));
    }
    /**
     * 请求新浪微博授权
     */
    public function get_sina_oauth2Op(){
        $logic_connect_api = Logic('connect_api');
        $sina_url = $logic_connect_api->getSinaOAuth2Url('api');
        @header("location: ".$sina_url);
    }
    /**
     * 新浪微博获取回调信息
     */
    public function get_sina_infoOp(){
        $code = $_GET['code'];
        $client = $_GET['client'];
        $sina_token['access_token'] = $_GET['accessToken'];
        $sina_token['uid'] = $_GET['userID'];
        $logic_connect_api = Logic('connect_api');
        $user_info = $logic_connect_api->getSinaUserInfo($code,$client,$sina_token);
        if(!empty($user_info['id'])){
            $sinaopenid = $user_info['id'];
            $model_member = Model('member');
            $member = $model_member->getMemberInfo(array('member_sinaopenid'=> $sinaopenid));
            $state_data = array();
            $token = 0;
            if(!empty($member)) {//会员信息存在时自动登录
                $token = $logic_connect_api->getUserToken($member, $client);
            } else {//自动注册会员并登录
                $info_data = $logic_connect_api->sinaRegister($user_info, $client);
                $token = $info_data['token'];
                $member = $info_data['member'];
                $state_data['password'] = $member['member_passwd'];
            }
            if($token) {
                $state_data['key'] = $token;
                $state_data['username'] = $member['member_name'];
                $state_data['userid'] = $member['member_id'];
                if($client == 'wap'){
                    redirect(WAP_SITE_URL.'/tmpl/member/member.html?username='.$state_data['username'].'&key='.$state_data['key']);
                }
                output_data($state_data);
            } else {
                output_error('会员登录失败');
            }
        } else {
            output_error('新浪微博登录失败');
        }
    }
    /**
     * 微信获取应用唯一标识
     */
    public function get_wx_appidOp(){
        output_data(C('app_weixin_appid'));
    }
    /**
     * 微信获取回调信息
     */
    public function get_wx_infoOp(){
        $code = $_GET['code'];
        $access_token = $_GET['access_token'];
        $openid = $_GET['openid'];
        $client = $_GET['client'];
        $logic_connect_api = Logic('connect_api');
        if(!empty($code)) {
            $user_info = $logic_connect_api->getWxUserInfo($code,'api');
        } else {
            $user_info = $logic_connect_api->getWxUserInfoUmeng($access_token, $openid);
        }
        if(!empty($user_info['unionid'])){
            $unionid = $user_info['unionid'];
            $model_member = Model('member');
            $member = $model_member->getMemberInfo(array('weixin_unionid'=> $unionid));
            $state_data = array();
            $token = 0;
            if(!empty($member)) {//会员信息存在时自动登录
                $token = $logic_connect_api->getUserToken($member, $client);
            } else {//自动注册会员并登录
                $info_data = $logic_connect_api->wxRegister($user_info, $client);
                $token = $info_data['token'];
                $member = $info_data['member'];
                $state_data['password'] = $member['member_passwd'];
            }
            if($token) {
                $state_data['key'] = $token;
                $state_data['username'] = $member['member_name'];
                $state_data['userid'] = $member['member_id'];
                output_data($state_data);
            } else {
                output_error('会员登录失败');
            }
        } else {
            output_error('微信登录失败');
        }
    }
    /**
     * 获取手机短信验证码
     */
    public function get_sms_captchaOp(){
        $sec_key = $_GET['sec_key'];
        $sec_val = $_GET['sec_val'];
        
        $phone = $_GET['phone'];
        $log_type = $_GET['type'];//短信类型:1为注册,2为登录,3为找回密码
        $state_data = array(
            'state' => false,
            'msg' => '验证码或手机号码不正确'
            );
        
        $result = Model('apiseccode')->checkApiSeccode($sec_key,$sec_val);
        if ($result && strlen($phone) == 11){
            $logic_connect_api = Logic('connect_api');
            $state_data = $logic_connect_api->sendCaptcha($phone, $log_type);
        }
        $this->connect_output_data($state_data);
    }
    /**
     * 验证手机验证码
     */
    public function check_sms_captchaOp(){
        $phone = $_GET['phone'];
        $captcha = $_GET['captcha'];
        $log_type = $_GET['type'];
        $logic_connect_api = Logic('connect_api');
        $state_data = $logic_connect_api->checkSmsCaptcha($phone, $captcha, $log_type);
        $this->connect_output_data($state_data, 1);
    }
    /**
     * 手机注册
     */
    public function sms_registerOp(){
        $phone = $_POST['phone'];
        $captcha = $_POST['captcha'];
        $password = $_POST['password'];
        $client = $_POST['client'];
        $logic_connect_api = Logic('connect_api');
        $state_data = $logic_connect_api->smsRegister($phone, $captcha, $password, $client);
        $this->connect_output_data($state_data);
    }
    /**
     * 手机找回密码
     */
    public function find_passwordOp(){
        $phone = $_POST['phone'];
        $captcha = $_POST['captcha'];
        $password = $_POST['password'];
        $client = $_POST['client'];
        $logic_connect_api = Logic('connect_api');
        $state_data = $logic_connect_api->smsPassword($phone, $captcha, $password, $client);
        $this->connect_output_data($state_data);
    }
    /**
     * 格式化输出数据
     */
    public function connect_output_data($state_data, $type = 0){
        if($state_data['state']){
            unset($state_data['state']);
            unset($state_data['msg']);
            if ($type == 1){
                $state_data = 1;
            }
            output_data($state_data);
        } else {
            output_error($state_data['msg']);
        }
    }
}
