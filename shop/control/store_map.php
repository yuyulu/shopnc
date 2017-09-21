<?php
/**
 * 店铺地址
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class store_mapControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 店铺地址地图显示
     *
     */
    public function indexOp() {
        $model_store_map = Model('store_map');
        $store_id = $_SESSION['store_id'];
        $condition = array();
        $condition['store_id'] = $store_id;
        $map_list = $model_store_map->getStoreMapList($condition, '', '', 'map_id asc');
        Tpl::output('map_list',$map_list);
        self::profile_menu('store_map','index');
        Tpl::showpage('store_map.index');
    }
    /**
     * 店铺地址列表显示
     *
     */
    public function listOp() {
        $model_store_map = Model('store_map');
        $store_id = $_SESSION['store_id'];
        $condition = array();
        $condition['store_id'] = $store_id;
        $map_list = $model_store_map->getStoreMapList($condition, 10, '', 'map_id asc');
        Tpl::output('map_list',$map_list);
        Tpl::output('show_page',$model_store_map->showpage());
        self::profile_menu('store_map','list');
        Tpl::showpage('store_map.list');
    }
    /**
     * 增加店铺地址
     *
     */
    public function add_mapOp() {
        if (chksubmit()) {
            $model_store = Model('store');
            $store_id = $_SESSION['store_id'];
            $store = $model_store->getStoreInfoByID($store_id);

            $map_array = array();
            $map_array['store_id'] = $store['store_id'];
            $map_array['sc_id'] = $store['sc_id'];
            $map_array['store_name'] = $store['store_name'];
            $map_array['name_info'] = $_POST['name_info'];
            $map_array['address_info'] = $_POST['address_info'];
            $map_array['phone_info'] = $_POST['phone_info'];
            $map_array['bus_info'] = $_POST['bus_info'];
            $map_array['baidu_province'] = $_POST['province'];
            $map_array['baidu_city'] = $_POST['city'];
            $map_array['baidu_district'] = $_POST['district'];
            $map_array['baidu_street'] = $_POST['street'];
            $map_array['baidu_lng'] = $_POST['lng'];
            $map_array['baidu_lat'] = $_POST['lat'];
            $map_array['update_time'] = time();

            $model_store_map = Model('store_map');
            $state = $model_store_map->addStoreMap($map_array);
            if ($state) {
                showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
            } else {
                showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
            }
        }
        Tpl::showpage('store_map.add','null_layout');
    }
    /**
     * 编辑店铺地址
     *
     */
    public function edit_mapOp() {
        $model_store_map = Model('store_map');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['map_id'] = intval($_GET['map_id']);
        if (chksubmit()) {
            $map_array = array();
            $map_array['name_info'] = $_POST['name_info'];
            $map_array['address_info'] = $_POST['address_info'];
            $map_array['phone_info'] = $_POST['phone_info'];
            $map_array['bus_info'] = $_POST['bus_info'];
            $map_array['update_time'] = time();
            $state = $model_store_map->editStoreMap($condition, $map_array);
            if ($state) {
                showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
            } else {
                showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
            }
        }
        $map_list = $model_store_map->getStoreMapList($condition);
        $map = $map_list[0];
        Tpl::output('map',$map);
        Tpl::showpage('store_map.edit','null_layout');
    }
    /**
     * 更新地址坐标
     *
     */
    public function update_mapOp() {
        $model_store_map = Model('store_map');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['map_id'] = intval($_POST['map_id']);
        $map_array = array();
        $map_array['baidu_province'] = $_POST['province'];
        $map_array['baidu_city'] = $_POST['city'];
        $map_array['baidu_district'] = $_POST['district'];
        $map_array['baidu_street'] = $_POST['street'];
        $map_array['baidu_lng'] = $_POST['lng'];
        $map_array['baidu_lat'] = $_POST['lat'];
        $map_array['update_time'] = time();
        $state = $model_store_map->editStoreMap($condition, $map_array);
        if ($state) {
            echo '1';exit;
        } else {
            echo '0';exit;
        }
    }
    /**
     * 删除店铺地址
     *
     */
    public function del_mapOp() {
        $model_store_map = Model('store_map');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['map_id'] = intval($_GET['map_id']);
        $state = $model_store_map->delStoreMap($condition);
        if ($state) {
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        } else {
            showDialog(L('nc_common_op_fail'), 'reload', 'error');
        }
    }
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array = array();
        switch ($menu_type) {
            case 'store_map':
                $menu_array = array(
                    array('menu_key'=>'index','menu_name'=>'地图显示 ',  'menu_url'=>'index.php?act=store_map&op=index'),
                    array('menu_key'=>'list','menu_name'=>'列表显示 ',  'menu_url'=>'index.php?act=store_map&op=list')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
