<?php
/**
 * 上传设置
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class uploadControl extends SystemControl{
    private $links = array(
        array('url'=>'act=upload&op=param','lang'=>'upload_param'),
        array('url'=>'act=upload&op=default_thumb','lang'=>'default_thumb'),
    );
    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexOp() {
        $this->paramOp();
    }

    /**
     * 上传参数设置
     *
     */
    public function paramOp(){
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["image_max_filesize"], "require"=>"true", "validator"=>"Number", "message"=>L('upload_image_filesize_is_number')),
                array("input"=>trim($_POST["image_allow_ext"]), "require"=>"true", "message"=>L('image_allow_ext_not_null'))
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $model_setting = Model('setting');
                $result = $model_setting->updateSetting(array(
                    'image_max_filesize'=>intval($_POST['image_max_filesize']),
                    'image_allow_ext'=>$_POST['image_allow_ext'])
                );
                if ($result){
                    $this->log(L('nc_edit,upload_param'),1);
                    showMessage(L('nc_common_save_succ'));
                }else {
                    $this->log(L('nc_edit,upload_param'),0);
                    showMessage(L('nc_common_save_fail'));
                }
            }
        }

        //获取默认图片设置属性
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'param'));
		
		Tpl::setDirquna('system');
        Tpl::showpage('upload.param');
    }

    /**
     * 默认图设置
     */
    public function default_thumbOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_COMMON);
            //默认会员头像
            if (!empty($_FILES['default_user_portrait']['tmp_name'])){
                $thumb_width    = '32';
                $thumb_height   = '32';

                $upload->set('thumb_width', $thumb_width);
                $upload->set('thumb_height',$thumb_height);
                $upload->set('thumb_ext',   '_small');
                $upload->set('file_name', '');
                $result = $upload->upfile('default_user_portrait');
                if ($result){
                    $_POST['default_user_portrait'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }
            $list_setting = $model_setting->getListSetting();
            $update_array = array();
            if (!empty($_POST['default_user_portrait'])){
                $update_array['default_user_portrait'] = $_POST['default_user_portrait'];
            }
            if (!empty($update_array)){
                $result = $model_setting->updateSetting($update_array);
            }else{
                $result = true;
            }
            if ($result === true){
                //判断有没有之前的图片，如果有则删除
                if (!empty($list_setting['default_user_portrait']) && !empty($_POST['default_user_portrait'])){
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.$list_setting['default_user_portrait']);
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.str_ireplace(',', '_small.', $list_setting['default_user_portrait']));
                }
                $this->log(L('nc_edit,default_thumb'),1);
                showMessage(L('nc_common_save_succ'));
            }else {
                $this->log(L('nc_edit,default_thumb'),0);
                showMessage(L('nc_common_save_fail'));
            }
        }

        $list_setting = $model_setting->getListSetting();

        //模板输出
        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'default_thumb'));
		
		Tpl::setDirquna('system');
        Tpl::showpage('upload.thumb');
    }
}
