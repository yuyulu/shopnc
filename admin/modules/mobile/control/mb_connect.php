<?php
/**
 * 手机端微信公众账号二维码设置
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class mb_connectControl extends SystemControl{
    private $links = array(
        array('url'=>'act=mb_connect&op=wx','text'=>'微信登录'),
        array('url'=>'act=mb_connect&op=wap_wx','text'=>'WAP微信登录'),
        array('url'=>'act=mb_connect&op=qq','text'=>'QQ互联'),
        array('url'=>'act=mb_connect&op=sina','text'=>'新浪微博')
    );
    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexOp() {
        $this->wxOp();
    }

    /**
     * 微信登录
     */
    public function wxOp() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['app_weixin_isuse']   = $_POST['app_weixin_isuse'];
            $update_array['app_weixin_appid']   = $_POST['app_weixin_appid'];
            $update_array['app_weixin_secret']  = $_POST['app_weixin_secret'];
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                $this->log('第三方账号登录，微信登录设置');
                showMessage(Language::get('nc_common_save_succ'));
            }else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'wx'));
	Tpl::setDirquna('mobile');
        Tpl::showpage('mb_connect_wx.edit');
    }

    /**
     * WAP微信登录
     */
    public function wap_wxOp() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['wap_weixin_isuse']   = $_POST['wap_weixin_isuse'];
            $update_array['wap_weixin_appid']   = $_POST['wap_weixin_appid'];
            $update_array['wap_weixin_secret']  = $_POST['wap_weixin_secret'];
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                $this->log('第三方账号登录，WAP微信登录设置');
                showMessage(Language::get('nc_common_save_succ'));
            }else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'wap_wx'));
	Tpl::setDirquna('mobile');
        Tpl::showpage('mb_connect_wap_wx.edit');
    }

    /**
     * QQ互联登录
     */
    public function qqOp() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['app_qq_isuse']   = $_POST['app_qq_isuse'];
            $update_array['app_qq_akey']   = $_POST['app_qq_akey'];
            $update_array['app_qq_skey']  = $_POST['app_qq_skey'];
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                $this->log('第三方账号登录，QQ互联登录设置');
                showMessage(Language::get('nc_common_save_succ'));
            }else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'qq'));
	Tpl::setDirquna('mobile');
        Tpl::showpage('mb_connect_qq.edit');
	
    }

    /**
     * 新浪微博登录
     */
    public function sinaOp() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['app_sina_isuse']   = $_POST['app_sina_isuse'];
            $update_array['app_sina_akey']   = $_POST['app_sina_akey'];
            $update_array['app_sina_skey']  = $_POST['app_sina_skey'];
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                $this->log('第三方账号登录，新浪微博登录设置');
                showMessage(Language::get('nc_common_save_succ'));
            }else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'sina'));
	Tpl::setDirquna('mobile');
        Tpl::showpage('mb_connect_sn.edit');
    }
}
