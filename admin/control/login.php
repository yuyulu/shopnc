<?php
/**
 * 登录
 *
 * 包括 登录 验证 退出 操作
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class LoginControl extends SystemControl {

    /**
     * 不进行父类的登录验证，所以增加构造方法重写了父类的构造方法
     */
    public function __construct(){
        Language::read('common,layout,login');
        $result = chksubmit(true,true,'num');
        if ($result){
            if ($result === -11){
                showMessage('非法请求');
            }elseif ($result === -12){
                showMessage(L('login_index_checkcode_wrong'));
            }
            if (process::islock('admin')) {
                showMessage('您的操作过于频繁，请稍后再试');
            }
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["user_name"], "require"=>"true", "message"=>L('login_index_username_null')),
                array("input"=>$_POST["password"],  "require"=>"true", "message"=>L('login_index_password_null')),
                array("input"=>$_POST["captcha"],   "require"=>"true", "message"=>L('login_index_checkcode_null')),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage(L('error').$error);
            } else {
                $model_admin = Model('admin');
                $array  = array();
                $array['admin_name']    = $_POST['user_name'];
                $array['admin_password']= md5(trim($_POST['password']));
                $admin_info = $model_admin->infoAdmin($array);
                if(is_array($admin_info) and !empty($admin_info)) {
                    if ($admin_info['admin_gid'] > 0) {
                        $gamdin_info = Model('gadmin')->getGadminInfoById($admin_info['admin_gid']);
                        $group_name = $gamdin_info['gname'];
                    } else {
                        $group_name = '超级管理员';
                    }
                    $array = array();
                    $array['name']  = $admin_info['admin_name'];
                    $array['id']    = $admin_info['admin_id'];
                    $array['time']  = $admin_info['admin_login_time'];
                    $array['ip']    = getIp();
                    $array['gid']   = $admin_info['admin_gid'];
                    $array['gname'] = $group_name;
                    $array['sp']    = $admin_info['admin_is_super'];
                    $array['qlink'] = $admin_info['admin_quick_link'];
                    $this->systemSetKey($array, $admin_info['admin_avatar'], true);
                    $update_info    = array(
                        'admin_id'=>$admin_info['admin_id'],
                        'admin_login_num'=>($admin_info['admin_login_num']+1),
                        'admin_login_time'=>TIMESTAMP
                    );
                    $model_admin->updateAdmin($update_info);
                    $this->log(L('nc_login'),1);
                    process::clear('admin');
                    @header('Location: index.php');exit;
                } else {
                    process::addprocess('admin');
                    showMessage(L('login_index_username_password_wrong'),'index.php?act=login&op=login');
                }
            }
        }
        Tpl::output('html_title',L('login_index_need_login'));
        Tpl::showpage('login','login_layout');
    }
    public function loginOp(){}
    public function indexOp(){}
}
