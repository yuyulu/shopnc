<?php
/**
 * 关联版式
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit ('Access Invalid!');
class store_chainControl extends BaseSellerControl {
    private $model_chain;
    public function __construct() {
        parent::__construct();

        if (!checkPlatformStore()) {
            showMessage('该功能只有平台自营店铺使用', urlShop('seller_center', 'index'), '', 'error');
        }
        
        $this->model_chain = Model('chain');
    }

    public function indexOp() {
        $this->chain_listOp();
    }

    /**
     * 门店列表
     */
    public function chain_listOp() {
        $chain_list = $this->model_chain->getChainList(array('store_id' => $_SESSION['store_id']), '*', 10);
        Tpl::output('show_page', $this->model_chain->showpage());
        Tpl::output('chain_list', $chain_list);
        $this->profile_menu('chain_list', 'chain_list');
        Tpl::showpage('store_chain.list');
    }
    
    /**
     * 添加门店
     */
    public function chain_addOp() {
        /**
         * 新增保存
         */
        if (chksubmit()){
            /**
             * 上传图片
             */
            $upload = new UploadFile();
            if (!empty($_FILES['chain_img']['name'])){
                $upload->set('default_dir', ATTACH_CHAIN.DS.$_SESSION['store_id']);
                $upload->set('thumb_ext',   '');
                $upload->set('file_name','');
                $upload->set('ifremove',false);
                $result = $upload->upfile('chain_img');
                if ($result){
                    $_POST['chain_img'] = $upload->file_name;
                }else {
                    showDialog($upload->error);
                }
            }
            
            $insert = array();
            $insert['store_id']     = $_SESSION['store_id'];
            $insert['chain_user']   = $_POST['chain_user'];
            $insert['chain_pwd']    = md5($_POST['chain_pwd']);
            $insert['chain_name']   = $_POST['chain_name'];
            $insert['chain_img']    = $_POST['chain_img'];
            $insert['area_id_1']    = intval($_POST['area_id_1']);
            $insert['area_id_2']    = intval($_POST['area_id_2']);
            $insert['area_id_3']    = intval($_POST['area_id_3']);
            $insert['area_id_4']    = intval($_POST['area_id_4']);
            $insert['area_id']      = intval($_POST['area_id']);
            $insert['area_info']    = $_POST['area_info'];
            $insert['chain_address']= $_POST['chain_address'];
            $insert['chain_phone']  = $_POST['chain_phone'];
            $insert['chain_opening_hours']  = $_POST['chain_opening_hours'];
            $insert['chain_traffic_line']   = $_POST['chain_traffic_line'];
            $result = $this->model_chain->addChain($insert);
            if ($result) {
                showDialog('操作成功', urlShop('store_chain', 'index'), 'succ');
            } else {
                showDialog('操作失败', 'reload');
            }
        }
        $this->profile_menu('chain_add', 'chain_add');
        Tpl::showpage('store_chain.add');
    }
    
    /**
     * 编辑门店
     */
    public function chain_editOp() {
        $chain_id = $_GET['chain_id'];
        $chain_info = $this->model_chain->getChainInfo(array('chain_id' => $chain_id, 'store_id' => $_SESSION['store_id']));
        
        if (chksubmit()){
            /**
             * 上传图片
             */
            $upload = new UploadFile();
            if (!empty($_FILES['chain_img']['name'])){
                $upload->set('default_dir', ATTACH_CHAIN.DS.$_SESSION['store_id']);
                $upload->set('thumb_ext',   '');
                $upload->set('file_name','');
                $upload->set('ifremove',false);
                $result = $upload->upfile('chain_img');
                if ($result){
                    $_POST['chain_img'] = $upload->file_name;
                }else {
                    showDialog($upload->error);
                }
            }
            
            //删除旧图片
            if (!empty($_POST['chain_img']) && !empty($chain_info['chain_img'])){
                @unlink(BASE_UPLOAD_PATH.DS.ATTACH_CHAIN.DS.$_SESSION['store_id'].DS.$chain_info['chain_img']);
            }
        
            $update = array();
            $update['chain_user']   = $_POST['chain_user'];
            if ($_POST['chain_pwd'] != '') {
                $update['chain_pwd']    = md5($_POST['chain_pwd']);
            }
            $update['chain_name']   = $_POST['chain_name'];
            if (!empty($_POST['chain_img'])) {
                $update['chain_img']    = $_POST['chain_img'];
            }
            $update['area_id_1']    = $_POST['area_id_1'];
            $update['area_id_2']    = $_POST['area_id_2'];
            $update['area_id_3']    = $_POST['area_id_3'];
            $update['area_id_4']    = $_POST['area_id_4'];
            $update['area_id']      = $_POST['area_id'];
            $update['area_info']    = $_POST['area_info'];
            $update['chain_address']= $_POST['chain_address'];
            $update['chain_phone']  = $_POST['chain_phone'];
            $update['chain_opening_hours']  = $_POST['chain_opening_hours'];
            $update['chain_traffic_line']   = $_POST['chain_traffic_line'];
            $result = $this->model_chain->editChain($update, array('chain_id' => $chain_id, 'store_id' => $_SESSION['store_id']));
            if ($result) {
                showDialog('编辑成功', urlShop('store_chain', 'index'), 'succ');
            } else {
                showDialog('编辑失败', 'reload');
            }
        }
        
        Tpl::output('chain_info', $chain_info);
        $this->profile_menu('chain_edit', 'chain_edit');
        Tpl::showpage('store_chain.add');
    }
    /**
     * 删除门店
     */
    public function chain_delOp() {
        $chain_id = $_GET['chain_id'];
        if (!preg_match('/^[\d,]+$/i', $chain_id)) {
            showDialog(L('wrong_argument'), '', 'error');
        }
        $chain_id = explode(',', $chain_id);
        $result = $this->model_chain->delChain(array('chain_id' => array('in', $chain_id), 'store_id' => $_SESSION['store_id']));
        if ($result) {
            showDialog('删除成功', urlShop('store_chain', 'index'), 'succ');
        } else {
            showDialog('删除失败', 'reload');
        }
    }
    
    /**
     * ajax验证用户名是否存在
     */
    public function check_userOp() {
        $where = array();
        if ($_GET['chain_user'] != '') {
            $where['chain_user'] = $_GET['chain_user'];
        }
        if ($_GET['no_id'] != '') {
            $where['chain_id'] = array('neq', $_GET['no_id']);
        }
        $chain_info = $this->model_chain->getChainInfo($where);
        if (empty($chain_info)) {
            echo 'true';die;
        } else {
            echo 'false';die;
        }
    }
    
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_type,$menu_key,$array=array()) {
        $menu_array = array();
        switch ($menu_type) {
            case 'chain_list':
                $menu_array = array(
                    array('menu_key' => 'chain_list', 'menu_name' => '门店列表', 'menu_url' => urlShop('store_chain', 'chain_list'))
                );
                break;
            case 'chain_add':
                $menu_array = array(
                    array('menu_key' => 'chain_list', 'menu_name' => '门店列表', 'menu_url' => urlShop('store_chain', 'chain_list')),
                    array('menu_key' => 'chain_add', 'menu_name' => '添加门店', 'menu_url' => urlShop('store_chain', 'chain_add'))
                );
                break;
            case 'chain_edit':
                $menu_array = array(
                    array('menu_key' => 'chain_list', 'menu_name' => '门店列表', 'menu_url' => urlShop('store_chain', 'chain_list')),
                    array('menu_key' => 'chain_add', 'menu_name' => '添加门店', 'menu_url' => urlShop('store_chain', 'chain_add')),
                    array('menu_key' => 'chain_edit', 'menu_name' => '编辑门店', 'menu_url' => urlShop('store_chain', 'chain_edit'))
                );
                break;
        }
        if(!empty($array)) {
            $menu_array[] = $array;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
