<?php
/**
 * 网站设置
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class settingControl extends SystemControl{
    private $links = array(
        array('url'=>'act=setting&op=base','lang'=>'web_set'),
        array('url'=>'act=setting&op=dump','lang'=>'dis_dump'),
        array('url'=>'act=setting&op=login','lang'=>'loginSettings'),
    );
    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexOp() {
        $this->baseOp();
    }

    /**
     * 基本信息
     */
    public function baseOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $list_setting = $model_setting->getListSetting();
            $update_array = array();
            $update_array['time_zone'] = $this->setTimeZone($_POST['time_zone']);
            $update_array['site_name'] = $_POST['site_name'];
            $update_array['statistics_code'] = $_POST['statistics_code'];
            $update_array['icp_number'] = $_POST['icp_number'];
            $update_array['site_status'] = $_POST['site_status'];
            $update_array['closed_reason'] = $_POST['closed_reason'];
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('nc_edit,web_set'),1);
                showMessage(L('nc_common_save_succ'));
            }else {
                $this->log(L('nc_edit,web_set'),0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        foreach ($this->getTimeZone() as $k=>$v) {
            if ($v == $list_setting['time_zone']){
                $list_setting['time_zone'] = $k;break;
            }
        }
        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'base'));
		
		Tpl::setDirquna('system');
        Tpl::showpage('setting.base');
    }

    /**
     * 防灌水设置
     */
    public function dumpOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['captcha_status_login'] = $_POST['captcha_status_login'];
            $update_array['captcha_status_register'] = $_POST['captcha_status_register'];
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('nc_edit,dis_dump'),1);
                showMessage(L('nc_common_save_succ'));
            }else {
                $this->log(L('nc_edit,dis_dump'),0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
        Tpl::output('top_link',$this->sublink($this->links,'dump'));
		Tpl::setDirquna('system');
        Tpl::showpage('setting.dump');
    }

    /**
     * 设置时区
     *
     * @param int $time_zone 时区键值
     */
    private function setTimeZone($time_zone){
        $zonelist = $this->getTimeZone();
        return empty($zonelist[$time_zone]) ? 'Asia/Shanghai' : $zonelist[$time_zone];
    }

    private function getTimeZone(){
        return array(
        '-12' => 'Pacific/Kwajalein',
        '-11' => 'Pacific/Samoa',
        '-10' => 'US/Hawaii',
        '-9' => 'US/Alaska',
        '-8' => 'America/Tijuana',
        '-7' => 'US/Arizona',
        '-6' => 'America/Mexico_City',
        '-5' => 'America/Bogota',
        '-4' => 'America/Caracas',
        '-3.5' => 'Canada/Newfoundland',
        '-3' => 'America/Buenos_Aires',
        '-2' => 'Atlantic/St_Helena',
        '-1' => 'Atlantic/Azores',
        '0' => 'Europe/Dublin',
        '1' => 'Europe/Amsterdam',
        '2' => 'Africa/Cairo',
        '3' => 'Asia/Baghdad',
        '3.5' => 'Asia/Tehran',
        '4' => 'Asia/Baku',
        '4.5' => 'Asia/Kabul',
        '5' => 'Asia/Karachi',
        '5.5' => 'Asia/Calcutta',
        '5.75' => 'Asia/Katmandu',
        '6' => 'Asia/Almaty',
        '6.5' => 'Asia/Rangoon',
        '7' => 'Asia/Bangkok',
        '8' => 'Asia/Shanghai',
        '9' => 'Asia/Tokyo',
        '9.5' => 'Australia/Adelaide',
        '10' => 'Australia/Canberra',
        '11' => 'Asia/Magadan',
        '12' => 'Pacific/Auckland'
        );
    }

    /**
     * 登录主题图片
     */
    public function loginOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $input = array();
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','1.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['login_pic1']['name'])){
                $result = $upload->upfile('login_pic1');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[] = $upload->file_name;
                }
            }elseif ($_POST['old_login_pic1'] != ''){
                $input[] = '1.jpg';
            }

            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','2.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['login_pic2']['name'])){
                $result = $upload->upfile('login_pic2');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[] = $upload->file_name;
                }
            }elseif ($_POST['old_login_pic2'] != ''){
                $input[] = '2.jpg';
            }

            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','3.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['login_pic3']['name'])){
                $result = $upload->upfile('login_pic3');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[] = $upload->file_name;
                }
            }elseif ($_POST['old_login_pic3'] != ''){
                $input[] = '3.jpg';
            }

            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','4.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['login_pic4']['name'])){
                $result = $upload->upfile('login_pic4');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[] = $upload->file_name;
                }
            }elseif ($_POST['old_login_pic4'] != ''){
                $input[] = '4.jpg';
            }

            $update_array = array();
            if (count($input) > 0){
                $update_array['login_pic'] = serialize($input);
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
        if ($list_setting['login_pic'] != ''){
            $list = unserialize($list_setting['login_pic']);
        }
        Tpl::output('list',$list);
        Tpl::output('top_link',$this->sublink($this->links,'login'));
        Tpl::setDirquna('system');
        Tpl::showpage('setting.login');
    }
    
}
