<?php
/**
 * 手机端微信公众账号二维码设置
 *
 *
 *
 ** 本系统由好商城 shop w w i.com提供
 */



defined('In33hao') or exit('Access Invalid!');
class mb_wxControl extends SystemControl{
    public function __construct(){
        parent::__construct();
//         Language::read('mobile');
    }

    public function indexOp(){
        $model_setting = Model('setting');
        $mobile_wx = $model_setting->getRowSetting('mobile_wx');
        $mobile_wx = $mobile_wx['value'];
        if (chksubmit()){
            if ($_FILES['mobile_wx']['tmp_name'] != ''){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_MOBILE);

                $result = $upload->upfile('mobile_wx');
                if ($result){
                    $_POST['mobile_wx'] = $upload->file_name;
                }else {
                    showMessage($upload->error);
                }
            }
            $update_array = array();
            $update_array['mobile_wx'] = $_POST['mobile_wx'];
            $result = $model_setting->updateSetting($update_array);
            if ($result){
                if (!empty($mobile_wx)){
                    @unlink(BASE_ROOT_PATH.DS.DIR_UPLOAD.DS.ATTACH_MOBILE.'/'.$mobile_wx);
                }
                showMessage(Language::get('nc_common_save_succ'));
            }else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        Tpl::output('mobile_wx',$mobile_wx);
        Tpl::setDirquna('mobile');
Tpl::showpage('mb_wx.index');
    }
}
