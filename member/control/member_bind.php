<?php
/**
 * 三方账户登录
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class member_bindControl extends BaseMemberControl {
    public function __construct() {
        parent::__construct();
        /**
         * 读取语言包
         */
        Language::read('member_bind');
    }
    /**
     * QQ绑定
     */
    public function qqbindOp(){
        //获得用户信息
        if (trim($this->member_info['member_qqinfo'])){
            $this->member_info['member_qqinfoarr'] = unserialize($this->member_info['member_qqinfo']);
        }
        Tpl::output('member_info',$this->member_info);
        //信息输出
        self::profile_menu('qq_bind');
        Tpl::showpage('member_bind.qq');
    }
    /**
     * QQ解绑
     */
    public function qqunbindOp(){
        //修改密码
        $model_member   = Model('member');
        $update_arr = array();
        if ($_POST['is_editpw'] == 'yes'){
            /**
             * 填写密码信息验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["new_password"],      "require"=>"true","validator"=>"Length","min"=>6,"max"=>20,"message"=>Language::get('member_qqconnect_password_null')),
                array("input"=>$_POST["confirm_password"],  "require"=>"true","validator"=>"Compare","operator"=>"==","to"=>$_POST["new_password"],"message"=>Language::get('member_qqconnect_input_two_password_again')),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error,'','html','error');
            }
            $update_arr['member_passwd'] = md5(trim($_POST['new_password']));
        }
        $update_arr['member_qqopenid'] = '';
        $update_arr['member_qqinfo'] = '';
        $edit_state     = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_arr);

        if(!$edit_state) {
            showMessage(Language::get('member_qqconnect_password_modify_fail'),'html','error');
        }

        session_unset();
        session_destroy();
        showMessage(Language::get('member_qqconnect_unbind_success'),urlLogin('login', 'index', array('ref_url' => urlMember('member_bind', 'qqbind'))));
    }
    /**
     * 新浪绑定
     */
    public function sinabindOp(){
        //获得用户信息
        if (trim($this->member_info['member_sinainfo'])){
            $this->member_info['member_sinainfoarr'] = unserialize($this->member_info['member_sinainfo']);
        }
        Tpl::output('member_info',$this->member_info);
        //信息输出
        self::profile_menu('sina_bind');
        Tpl::showpage('member_bind.sina');
    }
    /**
     * 新浪解绑
     */
    public function sinaunbindOp(){
        //修改密码
        $model_member   = Model('member');
        $update_arr = array();
        if ($_POST['is_editpw'] == 'yes'){
            /**
             * 填写密码信息验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["new_password"],      "require"=>"true","validator"=>"Length","min"=>6,"max"=>20,"message"=>Language::get('member_sconnect_password_null')),
                array("input"=>$_POST["confirm_password"],  "require"=>"true","validator"=>"Compare","operator"=>"==","to"=>$_POST["new_password"],"message"=>Language::get('member_sconnect_input_two_password_again')),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error,'','html','error');
            }
            $update_arr['member_passwd'] = md5(trim($_POST['new_password']));
        }
        $update_arr['member_sinaopenid'] = '';
        $update_arr['member_sinainfo'] = '';
        $edit_state     = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_arr);

        if(!$edit_state) {
            showMessage(Language::get('member_sconnect_password_modify_fail'),'','html','error');
        }
        session_unset();
        session_destroy();
        showMessage(Language::get('member_sconnect_unbind_success'),urlLogin('login', 'index', array('ref_url' => urlMember('member_bind', 'sinabind'))));
    }
    /**
     * 微信绑定
     */
    public function weixinbindOp(){
        //获得用户信息
        if (trim($this->member_info['weixin_info'])){
            $this->member_info['weixin_infoarr'] = unserialize($this->member_info['weixin_info']);
        }
        Tpl::output('member_info',$this->member_info);
        //信息输出
        self::profile_menu('weixin_bind');
        Tpl::showpage('member_bind.weixin');
    }
    /**
     * 微信解绑
     */
    public function weixinunbindOp(){
        //修改密码
        $model_member = Model('member');
        $update_arr = array();
        if ($_POST['is_editpw'] == 'yes'){
            /**
             * 填写密码信息验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["new_password"],      "require"=>"true","validator"=>"Length","min"=>6,"max"=>20,"message"=>Language::get('member_sconnect_password_null')),
                array("input"=>$_POST["confirm_password"],  "require"=>"true","validator"=>"Compare","operator"=>"==","to"=>$_POST["new_password"],"message"=>Language::get('member_sconnect_input_two_password_again')),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error,'','html','error');
            }
            $update_arr['member_passwd'] = md5(trim($_POST['new_password']));
        }
        $update_arr['weixin_unionid'] = '';
        $update_arr['weixin_info'] = '';
        $edit_state = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_arr);

        if(!$edit_state) {
            showMessage('保存失败','','html','error');
        }
        session_unset();
        session_destroy();
        showMessage('微信解绑成功',urlLogin('login', 'index', array('ref_url' => urlMember('member_bind', 'weixinbind'))));
    }
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_key='',$array=array()) {
        Language::read('member_layout');
        $lang   = Language::getLangContent();
        $menu_array     = array();
        $menu_array = array(
            1=>array('menu_key'=>'qq_bind', 'menu_name'=>'QQ绑定',   'menu_url'=>'index.php?act=member_bind&op=qqbind'),
            2=>array('menu_key'=>'sina_bind',   'menu_name'=>'新浪绑定', 'menu_url'=>'index.php?act=member_bind&op=sinabind'),
            3=>array('menu_key'=>'weixin_bind',   'menu_name'=>'微信绑定', 'menu_url'=>'index.php?act=member_bind&op=weixinbind'),
        );
        if(!empty($array)) {
            $menu_array[] = $array;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
