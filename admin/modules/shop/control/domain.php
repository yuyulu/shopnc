<?php
/**
 * 二级域名
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class domainControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('store');
    }

    function indexOp() {
        $this->store_domain_settingOp();
    }

    /**
     * 二级域名设置
     *
     * @param
     * @return
     */
    public function store_domain_settingOp(){
        /**
         * 读取语言包
         */
        $lang   = Language::getLangContent();

        /**
         * 实例化模型
         */
        $model_setting = Model('setting');
        /**
         * 保存信息
         */
        if (chksubmit()){
            $update_array = array();
            $update_array['enabled_subdomain'] = intval($_POST['enabled_subdomain']);
            $update_array['subdomain_reserved'] = trim($_POST['subdomain_reserved']);
            $update_array['subdomain_length'] = trim($_POST['subdomain_length']);
            $update_array['subdomain_edit'] = intval($_POST['subdomain_edit']);
            $update_array['subdomain_times'] = intval($_POST['subdomain_times']);
            $subdomain_length = explode('-',$update_array['subdomain_length']);
            $subdomain_length[0] = intval($subdomain_length[0]);
            $subdomain_length[1] = intval($subdomain_length[1]);
            if ($subdomain_length[0] < 1 || $subdomain_length[0] >= $subdomain_length[1]){//域名长度
                $update_array['subdomain_length'] = '3-12';
            }
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('nc_edit,nc_domain_manage'),1);
                showMessage($lang['nc_common_save_succ']);
            }else {
                showMessage($lang['nc_common_save_fail']);
            }
        }

        $list_setting = $model_setting->getListSetting();

        Tpl::output('list_setting',$list_setting);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('store_domain.setting');
    }

    /**
     * 店铺二级域名列表
     */
    public function store_domain_listOp(){
						
		Tpl::setDirquna('shop');
        Tpl::showpage('store_domain.index');
    }

    public function get_xmlOp() {
        $model_store = Model('store');
        $condition = array();
        $condition['store_state']   = array('neq', 2);
        if (C('dbdriver') == 'mysql') {
            $condition['store_domain'] = array('neq', '');
        } else {
            $condition['store_domain'] = array('exp', 'store_domain IS NOT NULL');
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('store_domain', 'store_domain_times', 'store_id', 'store_name', 'seller_name');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $store_list = $model_store->getStoreList($condition, $page, $order);

        $data = array();
        $data['now_page'] = $model_store->shownowpage();
        $data['total_num'] = $model_store->gettotalnum();
        foreach ($store_list as $value) {
            $param = array();
            $param['operation'] = "<a class='btn blue' href='index.php?act=domain&op=store_domain_edit&store_id=" . $value['store_id'] . "'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $param['store_domain'] = $value['store_domain'];
            $param['store_domain_times'] = $value['store_domain_times'];
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $value['store_name'];
            $param['seller_name'] = $value['seller_name'];
            $data['list'][$value['store_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 二级域名编辑
     */
    public function store_domain_editOp(){

        /**
         * 取店铺信息
         */
        $model_store = Model('store');
        $store_array = $model_store->getStoreInfoByID(intval($_GET['store_id']));

        // $setting_config = $GLOBALS['setting_config'];
        $config_subdomain_times = C('subdomain_times');
        $config_subdomain_length = C('subdomain_length');
        $config_subdomain_reserved = C('subdomain_reserved');

        $subdomain_times = intval($config_subdomain_times);//系统设置二级域名可修改次数
        $subdomain_length = explode('-',$config_subdomain_length);
        $subdomain_length[0] = intval($subdomain_length[0]);
        $subdomain_length[1] = intval($subdomain_length[1]);
        if ($subdomain_length[0] < 1 || $subdomain_length[0] >= $subdomain_length[1]){//域名长度
            $subdomain_length[0] = 3;
            $subdomain_length[1] = 12;
        }
        Tpl::output('subdomain_length',$subdomain_length);

        if (chksubmit()){
            $store_domain_times = intval($_POST['store_domain_times']);//店铺已修改次数
            $store_domain = trim($_POST['store_domain']);
            $store_id = intval($_POST['store_id']);
            $store_domain = strtolower($store_domain);
            $param = array();
            $param['store_domain_times'] = $store_domain_times;
            $param['store_domain'] = '';
            if (!empty($store_domain)){
                $store_domain_count = strlen($store_domain);
                if ($store_domain_count < $subdomain_length[0] || $store_domain_count > $subdomain_length[1]){
                    showMessage(Language::get('store_domain_length_error').': '.$config_subdomain_length);
                }
                if (!preg_match('/^[\w-]+$/i',$store_domain)){//判断域名是否正确
                    showMessage(Language::get('store_domain_invalid'));
                }
                $store_info = $model_store->getStoreInfo(array(
                    'store_domain'=>$store_domain
                ));
                //二级域名存在,则提示错误
                if (!empty($store_info) && ($store_id != $store_info['store_id'])){
                    showMessage(Language::get('store_domain_exists'));
                }
                //判断二级域名是否为系统禁止
                $subdomain_reserved = @explode(',',$config_subdomain_reserved);
                if(!empty($subdomain_reserved) && is_array($subdomain_reserved)){
                        if (in_array($store_domain,$subdomain_reserved)){
                            showMessage(Language::get('store_domain_sys'));
                        }
                }
                $param['store_domain'] = $store_domain;//所有验证通过后更新
            }
            $model_store->editStore($param, array('store_id'=> $store_id));
            $this->log(L('nc_edit,nc_domain_manage').'['.$store_domain.']',1);
            showMessage(Language::get('nc_common_save_succ'),'index.php?act=domain&op=store_domain_list');//保存成功
        }
        Tpl::output('store_array',$store_array);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('store_domain.edit');
    }
}
