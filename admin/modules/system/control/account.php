<?php
/**
 * 账号同步
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class accountControl extends SystemControl{
    private $links = array(
        array('url'=>'act=account&op=qq','lang'=>'qqSettings'),
        array('url'=>'act=account&op=sina','lang'=>'sinaSettings'),
        array('url'=>'act=account&op=sms','text'=>'手机短信'),
        array('url'=>'act=account&op=wx','text'=>'微信登录'),
		//array('url'=>'act=account&op=uc','text'=>'UC互联')  //临时注释 33HAO
    );
    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexOp() {
        $this->qqOp();
    }

    /**
     * QQ互联
     */
    public function qqOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $obj_validate = new Validate();
            if (trim($_POST['qq_isuse']) == '1'){
                $obj_validate->validateparam = array(
                    array("input"=>$_POST["qq_appid"], "require"=>"true","message"=>Language::get('qq_appid_error')),
                    array("input"=>$_POST["qq_appkey"], "require"=>"true","message"=>Language::get('qq_appkey_error'))
                );
            }
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
                $update_array['qq_isuse']   = $_POST['qq_isuse'];
                $update_array['qq_appcode'] = $_POST['qq_appcode'];
                $update_array['qq_appid']   = $_POST['qq_appid'];
                $update_array['qq_appkey']  = $_POST['qq_appkey'];
                $result = $model_setting->updateSetting($update_array);
                if ($result === true){
                    $this->log(L('nc_edit,qqSettings'),1);
                    showMessage(Language::get('nc_common_save_succ'));
                }else {
                    $this->log(L('nc_edit,qqSettings'),0);
                    showMessage(Language::get('nc_common_save_fail'));
                }
            }
        }

        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'qq'));
		Tpl::setDirquna('system');
        Tpl::showpage('account.qq_setting');
    }

    /**
     * sina微博设置
     */
    public function sinaOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $obj_validate = new Validate();
            if (trim($_POST['sina_isuse']) == '1'){
                $obj_validate->validateparam = array(
                    array("input"=>$_POST["sina_wb_akey"], "require"=>"true","message"=>Language::get('sina_wb_akey_error')),
                    array("input"=>$_POST["sina_wb_skey"], "require"=>"true","message"=>Language::get('sina_wb_skey_error'))
                );
            }
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
                $update_array['sina_isuse']     = $_POST['sina_isuse'];
                $update_array['sina_wb_akey']   = $_POST['sina_wb_akey'];
                $update_array['sina_wb_skey']   = $_POST['sina_wb_skey'];
                $update_array['sina_appcode']   = $_POST['sina_appcode'];
                $result = $model_setting->updateSetting($update_array);
                if ($result === true){
                    $this->log(L('nc_edit,sinaSettings'),1);
                    showMessage(Language::get('nc_common_save_succ'));
                }else {
                    $this->log(L('nc_edit,sinaSettings'),0);
                    showMessage(Language::get('nc_common_save_fail'));
                }
            }
        }
        $is_exist = function_exists('curl_init');
        if ($is_exist){
            $list_setting = $model_setting->getListSetting();
            Tpl::output('list_setting',$list_setting);
        }
        Tpl::output('is_exist',$is_exist);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'sina'));
        Tpl::setDirquna('system');
        Tpl::showpage('account.sina_setting');
    }

    /**
     * 手机短信设置
     */
    public function smsOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['sms_register']   = $_POST['sms_register'];
            $update_array['sms_login']   = $_POST['sms_login'];
            $update_array['sms_password']  = $_POST['sms_password'];
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                $this->log('编辑账号同步，手机短信设置');
                showMessage(Language::get('nc_common_save_succ'));
            }else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'sms'));
        Tpl::setDirquna('system');
        Tpl::showpage('account.sms_setting');
    }

    /**
     * 微信登录设置
     */
    public function wxOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['weixin_isuse']   = $_POST['weixin_isuse'];
            $update_array['weixin_appid']   = $_POST['weixin_appid'];
            $update_array['weixin_secret']  = $_POST['weixin_secret'];
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                $this->log('编辑账号同步，微信登录设置');
                showMessage(Language::get('nc_common_save_succ'));
            }else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'wx'));
        Tpl::setDirquna('system');
        Tpl::showpage('account.wx_setting');
    }
	/**
	 * Ucenter整合设置
	 *
	 * @param
	 * @return
	 */
	public function ucOp() {
		/**
		 * 读取语言包
		 */
		$lang	= Language::getLangContent();

		/**
		 * 实例化模型
		 */
		$model_setting = Model('setting');
		/**
		 * 保存信息
		 */
		if (chksubmit()){
			$update_array = array();
			$update_array['ucenter_status']		= trim($_POST['ucenter_status']);
            $update_array['ucenter_type']		= trim($_POST['ucenter_type']);
			$update_array['ucenter_app_id']		= trim($_POST['ucenter_app_id']);
			$update_array['ucenter_app_key']	= trim($_POST['ucenter_app_key']);
			$update_array['ucenter_ip'] 		= trim($_POST['ucenter_ip']);
			$update_array['ucenter_url'] 		= trim($_POST['ucenter_url']);
			$update_array['ucenter_connect_type'] = trim($_POST['ucenter_connect_type']);
			$update_array['ucenter_mysql_server'] = trim($_POST['ucenter_mysql_server']);
			$update_array['ucenter_mysql_username'] = trim($_POST['ucenter_mysql_username']);
			$update_array['ucenter_mysql_passwd'] = htmlspecialchars_decode(trim($_POST['ucenter_mysql_passwd']));
			$update_array['ucenter_mysql_name'] = trim($_POST['ucenter_mysql_name']);
			$update_array['ucenter_mysql_pre']	= trim($_POST['ucenter_mysql_pre']);

			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				showMessage(Language::get('nc_common_save_succ'));
			}else {
				showMessage(Language::get('nc_common_save_fail'));
			}
		}
		/**
		 * 读取设置内容 $list_setting
		 */
		$list_setting = $model_setting->getListSetting();
		/**
		 * 模板输出
		 */
		Tpl::output('list_setting',$list_setting);
		 //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'uc'));
		Tpl::setDirquna('system');
		Tpl::showpage('account.uc_setting');
	}

}
