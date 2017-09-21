<?php
/**
 * cms管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class cms_manageControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('cms');
    }

    public function indexOp() {
        $this->cms_manageOp();
    }

    /**
     * cms设置
     */
    public function cms_manageOp() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting',$setting_list);
        Tpl::setDirquna('cms');
Tpl::showpage('cms_manage');
    }

    /**
     * cms设置保存
     */
    public function cms_manage_saveOp() {
        $model_setting = Model('setting');
        $update_array = array();
        $update_array['cms_isuse'] = intval($_POST['cms_isuse']);
        if(!empty($_FILES['cms_logo']['name'])) {
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_CMS);
            $result = $upload->upfile('cms_logo');
            if(!$result) {
                showMessage($upload->error);
            }
            $update_array['cms_logo'] = $upload->file_name;
            $old_image = BASE_UPLOAD_PATH.DS.ATTACH_CMS.DS.C('microshop_logo');
            if(is_file($old_image)) {
                unlink($old_image);
            }
        }
        $update_array['cms_submit_verify_flag'] = intval($_POST['cms_submit_verify_flag']);
        $update_array['cms_comment_flag'] = intval($_POST['cms_comment_flag']);
        $update_array['cms_attitude_flag'] = intval($_POST['cms_attitude_flag']);
        $update_array['cms_seo_title'] = $_POST['cms_seo_title'];
        $update_array['cms_seo_keywords'] = $_POST['cms_seo_keywords'];
        $update_array['cms_seo_description'] = $_POST['cms_seo_description'];

        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            $this->log(Language::get('cms_log_manage_save'), 0);
            showMessage(Language::get('nc_common_save_succ'));
        }else {
            $this->log(Language::get('cms_log_manage_save'), 0);
            showMessage(Language::get('nc_common_save_fail'));
        }
    }


}
