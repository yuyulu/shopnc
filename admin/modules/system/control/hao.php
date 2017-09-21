<?php
/**
 * 好商城控件管理
 *  
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');
class haoControl extends SystemControl{
	 private $links = array(
	    array('url'=>'act=hao&op=base','lang'=>'hao_set'),
        array('url'=>'act=hao&op=banner','lang'=>'top_set'),

        array('url'=>'act=hao&op=lc','lang'=>'lc_set'),
		array('url'=>'act=hao&op=sms','lang'=>'sms_set'),
		//临时注释
		//array('url'=>'act=hao&op=rc','lang'=>'rc_set'),
		array('url'=>'act=hao&op=webchat','lang'=>'webchat_set'),
        
    );
	public function __construct(){
		parent::__construct();
		Language::read('hao,setting');
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
            $update_array['hao_mail'] = $_POST['hao_mail'];
            $update_array['hao_phone'] = $_POST['hao_phone'];
            $update_array['hao_time'] = $_POST['hao_time'];
			$update_array['points_invite'] = intval($_POST['points_invite'])?$_POST['points_invite']:0;
			$update_array['points_rebate'] = intval($_POST['points_rebate'])?$_POST['points_rebate']:0;
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('nc_edit,hao_set'),1);
                showMessage(L('nc_common_save_succ'));
            }else {
                $this->log(L('nc_edit,hao_set'),0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();

        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'base'));
		
		Tpl::setDirquna('system');
        Tpl::showpage('hao.base');
    }
	 /**
     * 顶部广告信息
     */
    public function bannerOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
			 if (!empty($_FILES['hao_top_banner_pic']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_COMMON);
                $result = $upload->upfile('hao_top_banner_pic');
                if ($result){
                    $_POST['hao_top_banner_pic'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }
            $list_setting = $model_setting->getListSetting();
            $update_array = array();
            $update_array['hao_top_banner_name'] = $_POST['top_banner_name'];
            $update_array['hao_top_banner_url'] = $_POST['top_banner_url'];
            $update_array['hao_top_banner_color'] = $_POST['top_banner_color'];
            $update_array['hao_top_banner_status'] = $_POST['top_banner_status'];
			if (!empty($_POST['hao_top_banner_pic'])){
                $update_array['hao_top_banner_pic'] = $_POST['hao_top_banner_pic'];
            }
            $result = $model_setting->updateSetting($update_array);
			if ($result === true){
                //判断有没有之前的图片，如果有则删除
                if (!empty($list_setting['hao_top_banner_pic']) && !empty($_POST['hao_top_banner_pic'])){
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.$list_setting['hao_top_banner_pic']);
                }
                $this->log(L('nc_edit,top_set'),1);
                showMessage(L('nc_common_save_succ'));
            }else {
                $this->log(L('nc_edit,top_set'),0);
                showMessage(L('nc_common_save_fail'));
            }
        }
         
        $list_setting = $model_setting->getListSetting();

        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'banner'));
		
		Tpl::setDirquna('system');
        Tpl::showpage('hao.banner');
    }
	
	 /**
     * 楼层快速直达列表
     */
    public function lcOp() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('hao_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!$lc_list && !is_array($lc_list)) {
            $lc_list = array();
        }
        Tpl::output('lc_list',$lc_list);
        Tpl::output('top_link',$this->sublink($this->links,'lc'));
		Tpl::setDirquna('system');
        Tpl::showpage('hao.lc');
    }

    /**
     * 楼层快速直达添加
     */
    public function lc_addOp() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('hao_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!$lc_list && !is_array($lc_list)) {
            $lc_list = array();
        }
        if (chksubmit()) {
            if (count($lc_list) >= 8) {
                showMessage('最多可设置8个楼层','index.php?act=hao&op=lc');
            }
            if ($_POST['lc_name'] != '' && $_POST['lc_value'] != '') {
                $data = array('name'=>stripslashes($_POST['lc_name']),'value'=>stripslashes($_POST['lc_value']));
                array_unshift($lc_list, $data);
            }
            $result = $model_setting->updateSetting(array('hao_lc'=>serialize($lc_list)));
            if ($result){
                showMessage('保存成功','index.php?act=hao&op=lc');
            }else {
                showMessage('保存失败');
            }
        }
		Tpl::setDirquna('system');

        Tpl::showpage('hao.lc_add');
    }

    /**
     * 删除
     */
    public function lc_delOp() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('hao_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!empty($lc_list) && is_array($lc_list) && intval($_GET['id']) >= 0) {
            unset($lc_list[intval($_GET['id'])]);
        }
        if (!is_array($lc_list)) {
            $lc_list = array();
        }
        $result = $model_setting->updateSetting(array('hao_lc'=>serialize(array_values($lc_list))));
        if ($result){
            showMessage('删除成功');
        }
        showMessage('删除失败');
    }

    /**
     * 编辑
     */
    public function lc_editOp() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('hao_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!is_array($lc_list)) {
            $lc_list = array();
        }
        if (!chksubmit()) {
            if (!empty($lc_list) && is_array($lc_list) && intval($_GET['id']) >= 0) {
                $current_info = $lc_list[intval($_GET['id'])];
            }
            Tpl::output('current_info',is_array($current_info) ? $current_info : array());
			Tpl::setDirquna('system');
            Tpl::showpage('hao.lc_add');
        } else {
            if ($_POST['lc_name'] != '' && $_POST['lc_value'] != '' && $_POST['id'] != '' && intval($_POST['id']) >= 0) {
                $lc_list[intval($_POST['id'])] = array('name'=>stripslashes($_POST['lc_name']),'value'=>stripslashes($_POST['lc_value']));
            }
            $result = $model_setting->updateSetting(array('hao_lc'=>serialize($lc_list)));
            if ($result){
                showMessage('编辑成功','index.php?act=hao&op=lc');
            }
            showMessage('编辑失败');
        }


    }
	
		 /**
     * 首页热门关键词链接
     */
    public function rcOp() {
        $model_setting = Model('setting');
        $rc_info = $model_setting->getRowSetting('hao_rc');
        if ($rc_info !== false) {
            $rc_list = @unserialize($rc_info['value']);
        }
        if (!$rc_list && !is_array($rc_list)) {
            $rc_list = array();
        }
        Tpl::output('rc_list',$rc_list);
        Tpl::output('top_link',$this->sublink($this->links,'rc'));
		Tpl::setDirquna('system');
        Tpl::showpage('hao.rc');
    }

    /**
     * 楼层快速直达添加
     */
    public function rc_addOp() {
        $model_setting = Model('setting');
        $rc_info = $model_setting->getRowSetting('hao_rc');
        if ($rc_info !== false) {
            $rc_list = @unserialize($rc_info['value']);
        }
        if (!$rc_list && !is_array($rc_list)) {
            $rc_list = array();
        }
        if (chksubmit()) {
            if (count($rc_list) >= 8) {
                showMessage('最多可设置8个楼层','index.php?act=hao&op=rc');
            }
            if ($_POST['rc_name'] != '' && $_POST['rc_value'] != '' && $_POST['rc_blod'] != '') {
                $data = array('name'=>stripslashes($_POST['rc_name']),'value'=>stripslashes($_POST['rc_value']),'is_blod'=>stripslashes($_POST['rc_blod']));
                array_unshift($rc_list, $data);
            }
            $result = $model_setting->updateSetting(array('hao_rc'=>serialize($rc_list)));
            if ($result){
                showMessage('保存成功','index.php?act=hao&op=rc');
            }else {
                showMessage('保存失败');
            }
        }
		Tpl::setDirquna('system');

        Tpl::showpage('hao.rc_add');
    }

    /**
     * 删除
     */
    public function rc_delOp() {
        $model_setting = Model('setting');
        $rc_info = $model_setting->getRowSetting('hao_rc');
        if ($rc_info !== false) {
            $rc_list = @unserialize($rc_info['value']);
        }
        if (!empty($rc_list) && is_array($rc_list) && intval($_GET['id']) >= 0) {
            unset($rc_list[intval($_GET['id'])]);
        }
        if (!is_array($rc_list)) {
            $rc_list = array();
        }
        $result = $model_setting->updateSetting(array('hao_rc'=>serialize(array_values($rc_list))));
        if ($result){
            showMessage('删除成功');
        }
        showMessage('删除失败');
    }

    /**
     * 编辑
     */
    public function rc_editOp() {
        $model_setting = Model('setting');
        $rc_info = $model_setting->getRowSetting('hao_rc');
        if ($rc_info !== false) {
            $rc_list = @unserialize($rc_info['value']);
        }
        if (!is_array($rc_list)) {
            $rc_list = array();
        }
        if (!chksubmit()) {
            if (!empty($rc_list) && is_array($rc_list) && intval($_GET['id']) >= 0) {
                $current_info = $rc_list[intval($_GET['id'])];
            }
            Tpl::output('current_info',is_array($current_info) ? $current_info : array());
			Tpl::setDirquna('system');
            Tpl::showpage('hao.rc_add');
        } else {
            if ($_POST['rc_name'] != '' && $_POST['rc_value'] != '' && $_POST['rc_blod'] != '' && $_POST['id'] != '' && intval($_POST['id']) >= 0) {
                $rc_list[intval($_POST['id'])] = array('name'=>stripslashes($_POST['rc_name']),'value'=>stripslashes($_POST['rc_value']),'is_blod'=>stripslashes($_POST['rc_blod']));
            }
            $result = $model_setting->updateSetting(array('hao_rc'=>serialize($rc_list)));
            if ($result){
                showMessage('编辑成功','index.php?act=hao&op=rc');
            }
            showMessage('编辑失败');
        }


    }
		/**
	 * 短信平台设置 
	 */
	public function smsOp(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$update_array = array();
			$update_array['hao_sms_type'] 	= $_POST['hao_sms_type'];
			$update_array['hao_sms_tgs'] 	= $_POST['hao_sms_tgs'];
			$update_array['hao_sms_zh'] 	= $_POST['hao_sms_zh'];
			$update_array['hao_sms_pw'] 	= $_POST['hao_sms_pw'];
			$update_array['hao_sms_key'] 	= $_POST['hao_sms_key'];
			$update_array['hao_sms_signature'] 		= $_POST['hao_sms_signature'];
			$update_array['hao_sms_bz'] 	= $_POST['hao_sms_bz'];
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('nc_edit,sms_set'),1);
				showMessage(L('nc_common_save_succ'));
			}else {
				$this->log(L('nc_edit,sms_set'),0);
				showMessage(L('nc_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);
		
        Tpl::output('top_link',$this->sublink($this->links,'sms'));
		Tpl::setDirquna('system');
        Tpl::showpage('hao.sms');
	}
			/**
	 * 默认微信公众号设置 
	 */
	public function webchatOp(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$update_array = array();
			$update_array['hao_webchat_appid'] 	= $_POST['hao_webchat_appid'];
			$update_array['hao_webchat_appsecret'] 	= $_POST['hao_webchat_appsecret'];
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('nc_edit,sms_set'),1);
				showMessage(L('nc_common_save_succ'));
			}else {
				$this->log(L('nc_edit,sms_set'),0);
				showMessage(L('nc_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);
		
        Tpl::output('top_link',$this->sublink($this->links,'webchat'));
		Tpl::setDirquna('system');
        Tpl::showpage('hao.webchat');
	}
}