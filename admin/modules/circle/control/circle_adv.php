<?php
/**
 * 圈子分类管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class circle_advControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('circle');
    }
    public function indexOp() {
        $this->adv_manageOp();
    }
    /**
     * 圈子幻灯
     */
    public function adv_manageOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $input = array();
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_CIRCLE);
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','1.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['adv_pic1']['name'])){
                $result = $upload->upfile('adv_pic1');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[1]['pic'] = $upload->file_name;
                    $input[1]['url'] = $_POST['adv_url1'];
                }
            }elseif ($_POST['old_adv_pic1'] != ''){
                $input[1]['pic'] = $_POST['old_adv_pic1'];
                $input[1]['url'] = $_POST['adv_url1'];
            }

            $upload->set('default_dir',ATTACH_CIRCLE);
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','2.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['adv_pic2']['name'])){
                $result = $upload->upfile('adv_pic2');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[2]['pic'] = $upload->file_name;
                    $input[2]['url'] = $_POST['adv_url2'];
                }
            }elseif ($_POST['old_adv_pic2'] != ''){
                $input[2]['pic'] = $_POST['old_adv_pic2'];
                $input[2]['url'] = $_POST['adv_url2'];
            }

            $upload->set('default_dir',ATTACH_CIRCLE);
            $upload->set('thumb_ext', '');
            $upload->set('file_name', '3.jpg');
            $upload->set('ifremove', false);
            if (!empty($_FILES['adv_pic3']['name'])){
                $result = $upload->upfile('adv_pic3');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[3]['pic'] = $upload->file_name;
                    $input[3]['url'] = $_POST['adv_url3'];
                }
            }elseif ($_POST['old_adv_pic3'] != ''){
                $input[3]['pic'] = $_POST['old_adv_pic3'];
                $input[3]['url'] = $_POST['adv_url3'];
            }

            $upload->set('default_dir',ATTACH_CIRCLE);
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','4.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['adv_pic4']['name'])){
                $result = $upload->upfile('adv_pic4');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[4]['pic'] = $upload->file_name;
                    $input[4]['url'] = $_POST['adv_url4'];
                }
            }elseif ($_POST['old_adv_pic4'] != ''){
                $input[4]['pic'] = $_POST['old_adv_pic4'];
                $input[4]['url'] = $_POST['adv_url4'];
            }

            $update_array = array();
            if (count($input) > 0){
                $update_array['circle_loginpic'] = serialize($input);
            }

            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('nc_edit,loginSettings'),1);
                showMessage(L('nc_common_save_succ'));
            }else {
                $this->log(L('nc_edit,loginSettings'),0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        if ($list_setting['circle_loginpic'] != ''){
            $list = unserialize($list_setting['circle_loginpic']);
        }
        Tpl::output('list', $list);
         
Tpl::setDirquna('circle');
Tpl::showpage('circle_adv.setting');
    }
}
