<?php
/**
 * 淘宝接口
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class taobao_apiControl extends SystemControl{

    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->taobao_api_settingOp();
    }

    public function taobao_api_settingOp() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting',$setting_list);
				
		Tpl::setDirquna('system');
        Tpl::showpage('taobao_api');
    }

    public function taobao_api_saveOp() {
        $model_setting = Model('setting');

        $update_array['taobao_api_isuse'] = intval($_POST['taobao_api_isuse']);
        $update_array['taobao_app_key'] = $_POST['taobao_app_key'];
        $update_array['taobao_secret_key'] = $_POST['taobao_secret_key'];

        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            $this->log('淘宝接口保存', 1);
            showMessage(Language::get('nc_common_save_succ'));
        }else {
            $this->log('淘宝接口保存', 0);
            showMessage(Language::get('nc_common_save_fail'));
        }
    }
}
