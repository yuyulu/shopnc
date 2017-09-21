<?php
/**
 * 账户安全
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_securityControl extends BaseMemberControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 安全列表
     */
    public function indexOp() {
        self::profile_menu('index','index');
        $member_info = $this->member_info;
        $member_info['security_level'] = Model('member')->getMemberSecurityLevel($member_info);
        Tpl::output('member_info',$member_info);
        Tpl::showpage('member_security.index');
    }

      /**
     * 绑定邮箱 - 发送邮件
     */
    public function send_bind_emailOp() {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_POST["email"], "require"=>"true", 'validator'=>'email',"message"=>'请正确填写邮箱')
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showValidateError($error);
        }

        $model_member = Model('member');

        //发送频率验证
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$_SESSION['member_id']));
        
        if (!empty($member_common_info['send_email_time']) && TIMESTAMP - $member_common_info['send_email_time'] < 58) {
            showDialog('请60秒以后再次发送邮件');
        }
        $condition = array();
        $condition['member_email'] = $_POST['email'];
        $condition['member_id'] = array('neq',$_SESSION['member_id']);
        $member_info = $model_member->getMemberInfo($condition,'member_id');
        if ($member_info) {
            showDialog('该邮箱已被使用');
        }
        $data = array();
        $data['member_email'] = $_POST['email'];
        $data['member_email_bind'] = 0;
        $update = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$data);
        if (!$update) {
            showDialog('系统发生错误，如有疑问请与管理员联系');
        }

        $seed = random(6);
        $data = array();
        $data['auth_code'] = $seed;
        $data['send_acode_time'] = TIMESTAMP;
        $data['send_email_time'] = TIMESTAMP;
        $data['send_acode_times'] = array('exp','send_acode_times+1');
        $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
        if (!$update) {
            showDialog('系统发生错误，如有疑问请与管理员联系');
        }
        $uid = base64_encode(encrypt($_SESSION['member_id'].' '.$_POST["email"]));
        $verify_url = urlMember('login', 'bind_email', array('uid' => $uid, 'hash' => md5($seed)));

        $model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>'bind_email'));
        $param = array();
        $param['site_name']	= C('site_name');
        $param['user_name'] = $_SESSION['member_name'];
        $param['verify_url'] = $verify_url;
        $subject	= ncReplaceText($tpl_info['title'],$param);
        $message	= ncReplaceText($tpl_info['content'],$param);

        $email	= new Email();
	    $result	= $email->send_sys_email($_POST["email"],$subject,$message);
	    showDialog('验证邮件已经发送至您的邮箱，请于24小时内登录邮箱并完成验证！如果您始终未收到邮件，请于60秒后重新发送','index.php?act=member_security&op=index','succ','',10);

    }


    /**
     * 统一身份验证入口
     */
    public function authOp() {

        $model_member = Model('member');

        if (chksubmit(false,true)) {
            if (!in_array($_POST['type'],array('modify_pwd','modify_mobile','modify_email','modify_paypwd','pd_cash'))) {
                redirect('index.php?act=member_security&op=index');
            }
            $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$_SESSION['member_id']));
            if (empty($member_common_info) || !is_array($member_common_info)) {
                showMessage('验证失败','','html','error');
            }
            if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {
                showMessage('验证码已失效，请重新获取验证码','','html','error');
            }
            if ($member_common_info['auth_code'] != $_POST['auth_code']) {
                showMessage('验证失败','','html','error');
            }
            $data = array();
            $data['auth_code'] = '';
            $data['send_acode_time'] = 0;
            $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
            if (!$update) {
                showMessage('系统发生错误，如有疑问请与管理员联系',SHOP_SITE_URL,'html','error');
            }
            setNcCookie('seccode'.$_POST['nchash'], '',-3600);
            $_SESSION['auth_'.$_POST['type']] = TIMESTAMP;

            self::profile_menu($_POST['type'],$_POST['type']);
            if ($_POST['type'] == 'pd_cash') {
                Tpl::showpage('member_pd_cash.add');
            } else {
                Tpl::showpage('member_security.'.$_POST['type']);
            }

        } else {
            if (!in_array($_GET['type'],array('modify_pwd','modify_mobile','modify_email','modify_paypwd','pd_cash'))) {
                redirect('index.php?act=member_security&op=index');
            }

            //继承父类的member_info
            $member_info = $this->member_info;
            if (!$member_info){
                $member_info = $model_member->getMemberInfo(array('member_id'=>$_SESSION['member_id']),'member_email,member_email_bind,member_mobile,member_mobile_bind');
            }

            self::profile_menu($_GET['type'],$_GET['type']);

            //第一次绑定邮箱，不用发验证码，直接进下一步
            //第一次绑定手机，不用发验证码，直接进下一步
            if (($_GET['type'] == 'modify_email' && $member_info['member_email_bind'] == '0') ||
            ($_GET['type'] == 'modify_mobile' && $member_info['member_mobile_bind'] == '0')) {
                $_SESSION['auth_'.$_GET['type']] = TIMESTAMP;
                Tpl::showpage('member_security.'.$_GET['type']);
                exit;
            }

            //修改密码、设置支付密码时，必须绑定邮箱或手机
            if (in_array($_GET['type'],array('modify_pwd','modify_paypwd')) && $member_info['member_email_bind'] == '0' &&
            $member_info['member_mobile_bind'] == '0') {
                showMessage('请先绑定邮箱或手机','index.php?act=member_security&op=index','html','error');
            }

            Tpl::output('member_info',$member_info);

            Tpl::showpage('member_security.auth');
        }

    }

    /**
     * 统一发送身份验证码
     */
    public function send_auth_codeOp() {
        if (!in_array($_GET['type'],array('email','mobile'))) exit();

        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($_SESSION['member_id'],'member_email,member_mobile');

        //发送频率验证
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$_SESSION['member_id']));
        if (!empty($member_common_info['send_acode_time'])) {
            if (date('Ymd',$member_common_info['send_acode_time']) != date('Ymd',TIMESTAMP)) {
                $data = array();
                $data['send_acode_times'] = 0;
                $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
            } else {
                if (TIMESTAMP - $member_common_info['send_acode_time'] < 58) {
                    exit(json_encode(array('state'=>'false','msg'=>'请60秒以后再次发送短信')));
                } else {
                    if ($member_common_info['send_acode_times'] >= 15) {
                        exit(json_encode(array('state'=>'false','msg'=>'您今天发送验证信息已超过15条，今天将无法再次发送')));
                    }
                }
            }
        }

        $verify_code = rand(100,999).rand(100,999);
        $model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>'authenticate'));

        $param = array();
        $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
        $param['verify_code'] = $verify_code;
        $param['site_name'] = C('site_name');
        $subject = ncReplaceText($tpl_info['title'],$param);
        $message = ncReplaceText($tpl_info['content'],$param);
       if ($_GET['type'] == 'email') {
            $email	= new Email();
            $result	= $email->send_sys_email($member_info["member_email"],$subject,$message);
        } elseif ($_GET['type'] == 'mobile') {
            $sms = new Sms();
            $result = $sms->send($member_info["member_mobile"],$message);
        }
        if ($result) {
            $data = array();
            $update_data['auth_code'] = $verify_code;
            $update_data['send_acode_time'] = TIMESTAMP;
            $update_data['send_acode_times'] = array('exp','send_acode_times+1');
            $update = $model_member->editMemberCommon($update_data,array('member_id'=>$_SESSION['member_id']));
            if (!$update) {
                exit(json_encode(array('state'=>'false','msg'=>'系统发生错误，如有疑问请与管理员联系')));
            }
            exit(json_encode(array('state'=>'true','msg'=>'验证码已发出，请注意查收')));
        } else {
            exit(json_encode(array('state'=>'false','msg'=>'验证码发送失败')));
        }
    }

    /**
     * 修改密码
     */
    public function modify_pwdOp() {
        $model_member = Model('member');

        //身份验证后，需要在30分钟内完成修改密码操作
        if (TIMESTAMP - $_SESSION['auth_modify_pwd'] > 1800) {
            showDialog('操作超时，请重新获得验证码','index.php?act=member_security&op=auth&type=modify_pwd','html','error');
        }

        if(!chksubmit()) exit();

        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_POST["password"],      "require"=>"true",      "message"=>'请正确输入密码'),
            array("input"=>$_POST["confirm_password"],  "require"=>"true",      "validator"=>"Compare","operator"=>"==","to"=>$_POST["password"],"message"=>'两次密码输入不一致'),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showValidateError($error);
        }
        $update = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),array('member_passwd'=>md5($_POST['password'])));
        $message = $update ? '密码修改成功' : '密码修改失败';
        unset($_SESSION['auth_modify_pwd']);
        showDialog($message,'index.php?act=member_security&op=index',$update ? 'succ' : 'error');

    }

    /**
     * 设置支付密码
     */
    public function modify_paypwdOp() {
        $model_member = Model('member');

        //身份验证后，需要在30分钟内完成修改密码操作
        if (TIMESTAMP - $_SESSION['auth_modify_paypwd'] > 1800) {
            showMessage('操作超时，请重新获得验证码','index.php?act=member_security&op=auth&type=modify_paypwd','html','error');
        }

        if(!chksubmit()) exit();

        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
                array("input"=>$_POST["password"],      "require"=>"true",      "message"=>'请正确输入密码'),
                array("input"=>$_POST["confirm_password"],  "require"=>"true",      "validator"=>"Compare","operator"=>"==","to"=>$_POST["password"],"message"=>'两次密码输入不一致'),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showValidateError($error);
        }
        $update = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),array('member_paypwd'=>md5($_POST['password'])));
        $message = $update ? '密码设置成功' : '密码设置失败';
        unset($_SESSION['auth_modify_paypwd']);
        showDialog($message,'index.php?act=member_security&op=index',$update ? 'succ' : 'error');

    }

    /**
     * 绑定手机
     */
    public function modify_mobileOp() {
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($_SESSION['member_id'],'member_mobile_bind');
        if (chksubmit()) {
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["mobile"], "require"=>"true", 'validator'=>'mobile',"message"=>'请正确填写手机号'),
                array("input"=>$_POST["vcode"], "require"=>"true", 'validator'=>'number',"message"=>'请正确填写手机验证码'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showValidateError($error);
            }

            $condition = array();
            $condition['member_id'] = $_SESSION['member_id'];
            $condition['auth_code'] = intval($_POST['vcode']);
            $member_common_info = $model_member->getMemberCommonInfo($condition,'send_acode_time');
            if (!$member_common_info) {
                showDialog('手机验证码错误，请重新输入');
            }
            if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {
                showDialog('手机验证码已过期，请重新获取验证码');
            }
            $data = array();
            $data['auth_code'] = '';
            $data['send_acode_time'] = 0;
            $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
            if (!$update) {
                showDialog('系统发生错误，如有疑问请与管理员联系');
            }
            $update = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),array('member_mobile_bind'=>1));
            if (!$update) {
                showDialog('系统发生错误，如有疑问请与管理员联系');
            }
            showDialog('手机号绑定成功','index.php?act=member_security&op=index','succ');
        }
    }

    /**
     * 修改手机号 - 发送验证码
     */
    public function send_modify_mobileOp() {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_GET["mobile"], "require"=>"true", 'validator'=>'mobile',"message"=>'请正确填写手机号码'),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            exit(json_encode(array('state'=>'false','msg'=>$error)));
        }

        $model_member = Model('member');

        //发送频率验证
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$_SESSION['member_id']));
        if (!empty($member_common_info['send_mb_time'])) {
            if (date('Ymd',$member_common_info['send_mb_time']) != date('Ymd',TIMESTAMP)) {
                $data = array();
                $data['send_mb_times'] = 0;
                $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));               
            } else {
                if (TIMESTAMP - $member_common_info['send_mb_time'] < 58) {
                    exit(json_encode(array('state'=>'false','msg'=>'请60秒以后再次发送短信')));
                } else {
                    if ($member_common_info['send_mb_times'] >= 15) {
                        exit(json_encode(array('state'=>'false','msg'=>'您今天发送短信已超过15条，今天将无法再次发送')));
                    }
                }                
            }
        }

        $condition = array();
        $condition['member_mobile'] = $_GET['mobile'];
        $condition['member_id'] = array('neq',$_SESSION['member_id']);
        $member_info = $model_member->getMemberInfo($condition,'member_id');
        if ($member_info) {
            exit(json_encode(array('state'=>'false','msg'=>'该手机号已被使用，请更换其它手机号')));
        }
        $data = array();
        $data['member_mobile'] = $_GET['mobile'];
        $data['member_mobile_bind'] = 0;
        $update = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$data);
        if (!$update) {
            exit(json_encode(array('state'=>'false','msg'=>'系统发生错误，如有疑问请与管理员联系')));
        }

        $verify_code = rand(100,999).rand(100,999);

        $model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>'modify_mobile'));
        $param = array();
        $param['site_name'] = C('site_name');
        $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
        $param['verify_code'] = $verify_code;
        $message    = ncReplaceText($tpl_info['content'],$param);
        $sms = new Sms();
        $result = $sms->send($_GET["mobile"],$message);
        if ($result) {
            $data = array();
            $data['auth_code'] = $verify_code;
            $data['send_acode_time'] = TIMESTAMP;
            $data['send_mb_time'] = TIMESTAMP;
            $data['send_mb_times'] = array('exp','send_mb_times+1');
            $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
            if (!$update) {
                exit(json_encode(array('state'=>'false','msg'=>'系统发生错误，如有疑问请与管理员联系')));
            }
            exit(json_encode(array('state'=>'true','msg'=>'发送成功')));
        } else {
            exit(json_encode(array('state'=>'false','msg'=>'发送失败')));
        }
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array     = array();
        switch ($menu_type) {
            case 'index':
                $menu_array = array(
                array('menu_key'=>'index', 'menu_name'=>'账户安全','menu_url'=>'index.php?act=member_security&op=index'),
                );
                break;
            case 'modify_pwd':
                $menu_array = array(
                array('menu_key'=>'index', 'menu_name'=>'账户安全','menu_url'=>'index.php?act=member_security&op=index'),
                array('menu_key'=>'modify_pwd','menu_name'=>'修改登录密码','menu_url'=>'index.php?act=member_security&op=auth&type=modify_pwd'),
                );
                break;
            case 'modify_email':
                $menu_array = array(
                array('menu_key'=>'index', 'menu_name'=>'账户安全','menu_url'=>'index.php?act=member_security&op=index'),
                array('menu_key'=>'modify_email', 'menu_name'=>'邮箱验证','menu_url'=>'index.php?act=member_security&op=auth&type=modify_email'),
                );
                break;
            case 'modify_mobile':
                $menu_array = array(
                array('menu_key'=>'index', 'menu_name'=>'账户安全','menu_url'=>'index.php?act=member_security&op=index'),
                array('menu_key'=>'modify_mobile','menu_name'=>'手机验证','menu_url'=>'index.php?act=member_security&op=auth&type=modify_mobile'),
                );
                break;
            case 'modify_paypwd':
                $menu_array = array(
                array('menu_key'=>'index', 'menu_name'=>'账户安全','menu_url'=>'index.php?act=member_security&op=index'),
                array('menu_key'=>'modify_paypwd','menu_name'=>'设置支付密码','menu_url'=>'index.php?act=member_security&op=auth&type=modify_paypwd'),
                );
                break;
            case 'pd_cash':
                $menu_array = array(
                array('menu_key'=>'loglist','menu_name'=>'账户余额',    'menu_url'=>'index.php?act=predeposit&op=pd_log_list'),
                array('menu_key'=>'recharge_list','menu_name'=>'充值明细', 'menu_url'=>'index.php?act=predeposit&op=index'),
                array('menu_key'=>'cashlist','menu_name'=>'余额提现', 'menu_url'=>'index.php?act=predeposit&op=pd_cash_list'),
                array('menu_key'=>'pd_cash','menu_name'=>'提现申请','menu_url'=>'index.php?act=member_security&op=auth&type=pd_cash'),
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
