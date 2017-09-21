<?php
/**
 * 手机专题
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class mb_specialControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->index_editOp();
    }

    /**
     * 专题列表
     */
    public function special_listOp() {
        $this->show_menu('special_list');
	Tpl::setDirquna('mobile');
        Tpl::showpage('mb_special.list');
    }

    /**
     * 输出专题列表XML数据
     */
    public function get_special_xmlOp() {
        $model_mb_special = Model('mb_special');
        $page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }
        $list = $model_mb_special->getMbSpecialList($condition,$page);
        $out_list = array();
        if (!empty($list) && is_array($list)){
            $fields_array = array('special_id','special_desc');
            foreach ($list as $k => $v){
                $out_array = getFlexigridArray(array(),$fields_array,$v);
                $out_array['special_desc'] = '<span nc_type="edit_special_desc" column_id="'.$v['special_id'].
                '" title="可编辑" class="editable tooltip w270">'.$v['special_desc'].'</span>';
                $operation = '';
                $operation .= '<a class="btn red" href="javascript:fg_operation_del('.$v['special_id'].');"><i class="fa fa-trash-o"></i>删除</a>';
                $operation .= '<a class="btn blue" href="'.urlAdminMobile('mb_special', 'special_edit', array('special_id' => $v['special_id'])).'"><i class="fa fa-pencil-square-o"></i>编辑</a>';
                $out_array['operation'] = $operation;
                $out_list[$v['special_id']] = $out_array;
            }
        }

        $data = array();
        $data['now_page'] = $model_mb_special->shownowpage();
        $data['total_num'] = $model_mb_special->gettotalnum();
        $data['list'] = $out_list;
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 保存专题
     */
    public function special_saveOp() {
        $model_mb_special = Model('mb_special');

        $param = array();
        $param['special_desc'] = $_POST['special_desc'];
        $result = $model_mb_special->addMbSpecial($param);

        if($result) {
            $this->log('添加手机专题' . '[ID:' . $result. ']', 1);
            showMessage(L('nc_common_save_succ'), urlAdminMobile('mb_special', 'special_list'));
        } else {
            $this->log('添加手机专题' . '[ID:' . $result. ']', 0);
            showMessage(L('nc_common_save_fail'), urlAdminMobile('mb_special', 'special_list'));
        }
    }

    /**
     * 编辑专题描述
     */
    public function update_special_descOp() {
        $model_mb_special = Model('mb_special');

        $param = array();
        $param['special_desc'] = $_GET['value'];
        $result = $model_mb_special->editMbSpecial($param, $_GET['id']);

        $data = array();
        if($result) {
            $this->log('保存手机专题' . '[ID:' . $result. ']', 1);
            $data['result'] = true;
        } else {
            $this->log('保存手机专题' . '[ID:' . $result. ']', 0);
            $data['result'] = false;
            $data['message'] = '保存失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 删除专题
     */
    public function special_delOp() {
        $model_mb_special = Model('mb_special');

        $result = $model_mb_special->delMbSpecialByID($_POST['special_id']);

        if($result) {
            $this->log('删除手机专题' . '[ID:' . $_POST['special_id'] . ']', 1);
            showMessage(L('nc_common_del_succ'), urlAdminMobile('mb_special', 'special_list'));
        } else {
            $this->log('删除手机专题' . '[ID:' . $_POST['special_id'] . ']', 0);
            showMessage(L('nc_common_del_fail'), urlAdminMobile('mb_special', 'special_list'));
        }
    }

    /**
     * 编辑首页
     */
    public function index_editOp() {
        $model_mb_special = Model('mb_special');

        $special_item_list = $model_mb_special->getMbSpecialItemListByID($model_mb_special::INDEX_SPECIAL_ID);
        Tpl::output('list', $special_item_list);
        Tpl::output('page', $model_mb_special->showpage(2));

        Tpl::output('module_list', $model_mb_special->getMbSpecialModuleList());
        Tpl::output('special_id', $model_mb_special::INDEX_SPECIAL_ID);

        $this->show_menu('index_edit');
        Tpl::setDirquna('mobile');
Tpl::showpage('mb_special_item.list');
    }

    /**
     * 编辑专题
     */
    public function special_editOp() {
        $model_mb_special = Model('mb_special');

        $special_item_list = $model_mb_special->getMbSpecialItemListByID($_GET['special_id']);
        Tpl::output('list', $special_item_list);
        Tpl::output('page', $model_mb_special->showpage(2));

        Tpl::output('module_list', $model_mb_special->getMbSpecialModuleList());
        Tpl::output('special_id', $_GET['special_id']);

        Tpl::setDirquna('mobile');
Tpl::showpage('mb_special_item.list');
    }

    /**
     * 专题项目添加
     */
    public function special_item_addOp() {
        $model_mb_special = Model('mb_special');

        $param = array();
        $param['special_id'] = $_POST['special_id'];
        $param['item_type'] = $_POST['item_type'];

        //广告只能添加一个
        if($param['item_type'] == 'adv_list') {
            $result = $model_mb_special->isMbSpecialItemExist($param);
            if($result) {
                echo json_encode(array('error' => '广告条板块只能添加一个'));die;
            }
        }
		//限时折扣只能添加一个
        if($param['item_type'] == 'goods1') {
            $result = $model_mb_special->isMbSpecialItemExist($param);
            if($result) {
                echo json_encode(array('error' => '限时折扣板块只能添加一个'));die;
            }
        }
		//抢购板块只能添加一个
        if($param['item_type'] == 'goods2') {
            $result = $model_mb_special->isMbSpecialItemExist($param);
            if($result) {
                echo json_encode(array('error' => '抢购板块只能添加一个'));die;
            }
        }

        $item_info = $model_mb_special->addMbSpecialItem($param);
        if($item_info) {
            echo json_encode($item_info);die;
        } else {
            echo json_encode(array('error' => '添加失败'));die;
        }
    }

    /**
     * 专题项目删除
     */
    public function special_item_delOp() {
        $model_mb_special = Model('mb_special');

        $condition = array();
        $condition['item_id'] = $_POST['item_id'];

        $result = $model_mb_special->delMbSpecialItem($condition, $_POST['special_id']);
        if($result) {
            echo json_encode(array('message' => '删除成功'));die;
        } else {
            echo json_encode(array('error' => '删除失败'));die;
        }
    }

    /**
     * 专题项目编辑
     */
    public function special_item_editOp() {
        $model_mb_special = Model('mb_special');

        $item_info = $model_mb_special->getMbSpecialItemInfoByID($_GET['item_id']);
        Tpl::output('item_info', $item_info);

        if($item_info['special_id'] == 0) {
            $this->show_menu('index_edit');
        } else {
            $this->show_menu('special_item_list');
        }
        Tpl::setDirquna('mobile');
        Tpl::showpage('mb_special_item.edit');
    }

    /**
     * 专题项目保存
     */
    public function special_item_saveOp() {
        $model_mb_special = Model('mb_special');

        $result = $model_mb_special->editMbSpecialItemByID(array('item_data' => $_POST['item_data']), $_POST['item_id'], $_POST['special_id']);

        if($result) {
            if($_POST['special_id'] == $model_mb_special::INDEX_SPECIAL_ID) {
                showMessage(L('nc_common_save_succ'), urlAdminMobile('mb_special', 'index_edit'));
            } else {
                showMessage(L('nc_common_save_succ'), urlAdminMobile('mb_special', 'special_edit', array('special_id' => $_POST['special_id'])));
            }
        } else {
            showMessage(L('nc_common_save_succ'), '');
        }
    }

    /**
     * 图片上传
     */
    public function special_image_uploadOp() {
        $data = array();
        if(!empty($_FILES['special_image']['name'])) {
            $prefix = 's' . $_POST['special_id'];
            $upload = new UploadFile();
            $upload->set('default_dir', ATTACH_MOBILE . DS . 'special' . DS . $prefix);
            $upload->set('fprefix', $prefix);
            $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));

            $result = $upload->upfile('special_image');
            if(!$result) {
                $data['error'] = $upload->error;
            }
            $data['image_name'] = $upload->file_name;
            $data['image_url'] = getMbSpecialImageUrl($data['image_name']);
        }
        echo json_encode($data);
    }

    /**
     * 商品列表
     */
    public function goods_listOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['goods_name'] = array('like', '%' . $_GET['keyword'] . '%');
        $goods_list = $model_goods->getGoodsOnlineList($condition, 'goods_id,goods_name,goods_promotion_price,goods_image', 10);
        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::setDirquna('mobile');
        Tpl::showpage('mb_special_widget.goods', 'null_layout');
    }
	/**
     * 限时折扣商品列表
     */
    public function goods_xianshi_listOp() {
        $model_goods = Model('goods');
        $condition = array();
	    $model_xianshi_goods = Model('p_xianshi_goods');		
        $condition['goods_name'] = array('like', '%' . $_GET['keyword'] . '%');
	    $goods_id_list=$model_xianshi_goods->getXianshiGoodsExtendIds($condition);		
				
		$goods_list = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_list);
        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::setDirquna('mobile');
        Tpl::showpage('mb_special_widget.goods', 'null_layout');
    }
    /**
     * 抢购商品列表
     */
    public function goods_groupbuy_listOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['goods_name'] = array('like', '%' . $_GET['keyword'] . '%');
		$model_groupbuy_goods = Model('groupbuy');
		$goods_list_arr=$model_groupbuy_goods->getGroupbuyGoodsExtendIds($condition);			
		$goods_list=$model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_list_arr);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::setDirquna('mobile');
        Tpl::showpage('mb_special_widget.goods', 'null_layout');
    }
    /**
     * 更新项目排序
     */
    public function update_item_sortOp() {
        $item_id_string = $_POST['item_id_string'];
        $special_id = $_POST['special_id'];
        if(!empty($item_id_string)) {
            $model_mb_special = Model('mb_special');
            $item_id_array = explode(',', $item_id_string);
            $index = 0;
            foreach ($item_id_array as $item_id) {
                $result = $model_mb_special->editMbSpecialItemByID(array('item_sort' => $index), $item_id, $special_id);
                $index++;
            }
        }
        $data = array();
        $data['message'] = '操作成功';
        echo json_encode($data);
    }

    /**
     * 更新项目启用状态
     */
    public function update_item_usableOp() {
        $model_mb_special = Model('mb_special');
        $result = $model_mb_special->editMbSpecialItemUsableByID($_POST['usable'], $_POST['item_id'], $_POST['special_id']);
        $data = array();
        if($result) {
            $data['message'] = '操作成功';
        } else {
            $data['error'] = '操作失败';
        }
        echo json_encode($data);
    }

    /**
     * 页面内导航菜单
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function show_menu($menu_key='') {
        $menu_array = array();
        if($menu_key == 'index_edit') {
            $menu_array[] = array('menu_key'=>'index_edit', 'menu_name'=>'首页', 'menu_url'=>'javascript:;');
            $menu_array[] = array('menu_key'=>'special_list','menu_name'=>'专题', 'menu_url'=>urlAdminMobile('mb_special', 'special_list'));
        } else {
            $menu_array[] = array('menu_key'=>'index_edit', 'menu_name'=>'首页', 'menu_url'=>urlAdminMobile('mb_special', 'index'));
            $menu_array[] = array('menu_key'=>'special_list','menu_name'=>'专题', 'menu_url'=>'javascript:;');
        }
        if($menu_key == 'index_edit') {
            tpl::output('item_title', '首页编辑');
        } else {
            tpl::output('item_title', '专题设置');
        }
        Tpl::output('menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }
}
