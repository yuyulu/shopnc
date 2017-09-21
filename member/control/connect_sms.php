<?php
/**
 * 手机短信登录
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class connect_smsControl extends BaseLoginControl{
    public function __construct(){
        parent::__construct();
        Language::read("home_login_register,home_login_index");
        Tpl::output('hidden_nctoolbar', 1);
        $model_member = Model('member');
        $model_member->checkloginMember();
    }
    /**
     * 手机注册验证码
     */
    public function indexOp(){
        $this->registerOp();
    }
    /**
     * 手机注册
     */
    public function registerOp(){
        $model_member = Model('member');
        $phone = $_POST['register_phone'];
        $captcha = $_POST['register_captcha'];
        if (chksubmit() && strlen($phone) == 11 && strlen($captcha) == 6){
            if(C('sms_register') != 1) {
                showDialog('系统没有开启手机注册功能','','error');
            }
            $member_name = $_POST['member_name'];
            $member = $model_member->getMemberInfo(array('member_name'=> $member_name));//检查重名
            if(!empty($member)) {
                showDialog('用户名已被注册','','error');
            }
            $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));//检查手机号是否已被注册
            if(!empty($member)) {
                showDialog('手机号已被注册','','error');
            }
            $logic_connect_api = Logic('connect_api');
            $state_data = $logic_connect_api->checkSmsCaptcha($phone, $captcha, 1);
            if($state_data['state'] == false) {
                showDialog('验证码错误或已过期，重新输入','','error');
            }
            
            $member = array();
            $member['member_name'] = $member_name;
            $member['member_passwd'] = $_POST['password'];
            $member['member_email'] = $_POST['email'];
            $member['member_mobile'] = $phone;
            $member['member_mobile_bind'] = 1;
            $result = $model_member->addMember($member);
            if($result) {
                $member = $model_member->getMemberInfo(array('member_name'=> $member_name));
                $model_member->createSession($member,true);//自动登录
                showDialog('注册成功',urlMember('member_information', 'member'),'succ');
            } else {
                showDialog(Language::get('nc_common_save_fail'),'','error');
            }
        } else {
            $phone = $_GET['phone'];
            $num = substr($phone,-4);
            $logic_connect_api = Logic('connect_api');
            $member_name = $logic_connect_api->getMemberName('mb', $num);
            Tpl::output('member_name',$member_name);
            Tpl::output('password',rand(100000, 999999));
            Tpl::showpage('connect_sms.register','null_layout');
        }
    }
    /**
     * 短信验证码
     */
    public function get_captchaOp(){
        $state = '发送失败';
        $phone = $_GET['phone'];
        if (checkSeccode($_GET['nchash'],$_GET['captcha']) && strlen($phone) == 11){
            $log_type = $_GET['type'];//短信类型:1为注册,2为登录,3为找回密码
            $state = 'true';
            $logic_connect_api = Logic('connect_api');
            $state_data = $logic_connect_api->sendCaptcha($phone, $log_type);
            
            if($state_data['state'] == false) {
                $state = $state_data['msg'];
            }
        } else {
            $state = '验证码错误';
        }
        exit($state);
    }
    /**
     * 验证注册验证码
     */
    public function check_captchaOp(){
        $state = '验证失败';
        $phone = $_GET['phone'];
        $captcha = $_GET['sms_captcha'];
        if (strlen($phone) == 11 && strlen($captcha) == 6){
            $state = 'true';
            $logic_connect_api = Logic('connect_api');
            $state_data = $logic_connect_api->checkSmsCaptcha($phone, $captcha, 1);
            if($state_data['state'] == false) {
                $state = '验证码错误或已过期，重新输入';
            }
        }
        exit($state);
    }
    /**
     * 登录
     */
    public function loginOp(){
        if (checkSeccode($_POST['nchash'],$_POST['captcha'])){
            if(C('sms_login') != 1) {
                showDialog('系统没有开启手机登录功能','','error');
            }
            $phone = $_POST['phone'];
            $captcha = $_POST['sms_captcha'];
            $logic_connect_api = Logic('connect_api');
            $state_data = $logic_connect_api->checkSmsCaptcha($phone, $captcha, 2);
            if($state_data['state'] == false) {//半小时内进行验证为有效
                showDialog('验证码错误或已过期，重新输入','','error');
            }
            $model_member = Model('member');
            $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));//检查手机号是否已被注册
            if(!empty($member)) {
                $model_member->createSession($member);//自动登录
                $reload = $_POST['ref_url'];
                if(empty($reload)) {
                    $reload = urlMember('member', 'home');
                }
                showDialog('登录成功',$reload,'succ');
            }
        }
    }
    /**
     * 找回密码
     */
    public function find_passwordOp(){
        if (checkSeccode($_POST['nchash'],$_POST['captcha'])){
            if(C('sms_password') != 1) {
                showDialog('系统没有开启手机找回密码功能','','error');
            }
            $phone = $_POST['phone'];
            $captcha = $_POST['sms_captcha'];
            $logic_connect_api = Logic('connect_api');
            $state_data = $logic_connect_api->checkSmsCaptcha($phone, $captcha, 3);
            if($state_data['state'] == false) {//半小时内进行验证为有效
                showDialog('验证码错误或已过期，重新输入','','error');
            }
            $model_member = Model('member');
            $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));//检查手机号是否已被注册
            if(!empty($member)) {
                $new_password = md5($_POST['password']);
                $model_member->editMember(array('member_id'=> $member['member_id']),array('member_passwd'=> $new_password));
                $model_member->createSession($member);//自动登录
                showDialog('密码修改成功',urlMember('member_information', 'member'),'succ');
            }
        }
    }
}
