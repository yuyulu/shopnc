<?php
/**
 * 手机端微信公众账号二维码设置
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class mb_settingControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->settingOp();
    }

    /**
     * 基本设置
     */
    public function settingOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['signin_isuse'] = intval($_POST['signin_isuse'])==1?1:0;
            $update_array['points_signin'] = intval($_POST['points_signin'])?$_POST['points_signin']:0;
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log('编辑手机端设置',1);
                showDialog(L('nc_common_save_succ'));
            } else {
                showDialog(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
	Tpl::setDirquna('mobile');
        Tpl::showpage('mb_setting');
    }
}
