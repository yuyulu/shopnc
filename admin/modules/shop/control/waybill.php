<?php
/**
 * 运单模板
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class waybillControl extends SystemControl{

    private $url_waybill_list;

    public function __construct(){
        parent::__construct();
        $this->url_waybill_list = urlAdminShop('waybill', 'waybill_list');
    }

    public function indexOp() {
        $this->waybill_listOp();
    }

    /**
     * 运单模板列表
     */
    public function waybill_listOp() {
		Tpl::setDirquna('shop');
        Tpl::showpage('waybill.list');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_waybill = Model('waybill');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('waybill_id', 'waybill_name', 'express_name', 'express_id', 'waybill_image', 'waybill_width', 'waybill_height'
                , 'waybill_usable', 'waybill_top', 'waybill_left'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $waybill_list = $model_waybill->getWaybillAdminList($condition, $page, $order);


        $data = array();
        $data['now_page'] = $model_waybill->shownowpage();
        $data['total_num'] = $model_waybill->gettotalnum();
        foreach ($waybill_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del('".$value['waybill_id']."')\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='index.php?act=waybill&op=waybill_design&waybill_id=".$value['waybill_id']."'>设计运单模板</a></li>";
            $operation .= "<li><a href='index.php?act=waybill&op=waybill_test&waybill_id=".$value['waybill_id']."' target=\"_blank\">测试运单模板</a></li>";
            $operation .= "<li><a href='index.php?act=waybill&op=waybill_edit&waybill_id=".$value['waybill_id']."'>编辑运单模板</a></li>";
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['waybill_name'] = $value['waybill_name'];
            $param['express_name'] = $value['express_name'];
            $param['waybill_image'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$value['waybill_image_url'].">\")'><i class='fa fa-picture-o'></i></a>";
            $param['waybill_width'] = $value['waybill_width'];
            $param['waybill_height'] = $value['waybill_height'];
            $param['waybill_usable'] = $value['waybill_usable'] == 1 ? '是' : '否';
            $param['waybill_top'] = $value['waybill_top'];
            $param['waybill_left'] = $value['waybill_left'];
            $data['list'][$value['waybill_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 添加运单模板
     */
    public function waybill_addOp() {
        $model_express = Model('express');

        Tpl::output('express_list', $model_express->getExpressList());
        $this->show_menu('waybill_add');
		Tpl::setDirquna('shop');
        Tpl::showpage('waybill.add');
    }

    /**
     * 保存运单模板
     */
    public function waybill_saveOp() {
        $model_waybill = Model('waybill');
        $result = $model_waybill->saveWaybill($_POST);

        if($result) {
            $this->log('保存运单模板' . '[ID:' . $result. ']', 1);
            showMessage(L('nc_common_save_succ'), $this->url_waybill_list);
        } else {
            $this->log('保存运单模板' . '[ID:' . $result. ']', 0);
            showMessage(L('nc_common_save_fail'), $this->url_waybill_list);
        }
    }

    /**
     * 编辑运单模板
     */
    public function waybill_editOp() {
        $model_express = Model('express');
        $model_waybill = Model('waybill');

        $waybill_info = $model_waybill->getWaybillInfoByID($_GET['waybill_id']);
        if(!$waybill_info) {
            showMessage('运单模板不存在');
        }
        Tpl::output('waybill_info', $waybill_info);

        $express_list = $model_express->getExpressList();
        foreach ($express_list as $key => $value) {
            if($value['id'] == $waybill_info['express_id']) {
                $express_list[$key]['selected'] = true;
            }
        }
        Tpl::output('express_list', $express_list);

        $this->show_menu('waybill_edit');
		Tpl::setDirquna('shop');
        Tpl::showpage('waybill.add');
    }

    /**
     * 设计运单模板
     */
    public function waybill_designOp() {
        $model_waybill = Model('waybill');

        $result = $model_waybill->getWaybillDesignInfo($_GET['waybill_id']);
        if(isset($result['error'])) {
            showMessage($result['error'], '', '', 'error');
        }

        Tpl::output('waybill_info', $result['waybill_info']);
        Tpl::output('waybill_info_data', $result['waybill_info_data']);
        Tpl::output('waybill_item_list', $result['waybill_item_list']);
        $this->show_menu('waybill_design');
		Tpl::setDirquna('shop');
        Tpl::showpage('waybill.design');
    }

    /**
     * 设计运单模板保存
     */
    public function waybill_design_saveOp() {
        $model_waybill = Model('waybill');

        $result = $model_waybill->editWaybillDataByID($_POST['waybill_data'], $_POST['waybill_id']);

        if($result) {
            $this->log('保存运单模板设计' . '[ID:' . $_POST['waybill_id'] . ']', 1);
            showMessage(L('nc_common_save_succ'), $this->url_waybill_list);
        } else {
            $this->log('保存运单模板设计' . '[ID:' . $_POST['waybill_id'] . ']', 0);
            showMessage(L('nc_common_save_fail'), $this->url_waybill_list);
        }
    }

    /**
     * 删除运单模板
     */
    public function waybill_delOp() {
        $waybill_id = intval($_GET['id']);
        if($waybill_id <= 0) {
            exit(json_encode(array('state'=>false,'msg'=>L('param_error'))));
        }

        $model_waybill = Model('waybill');

        $result = $model_waybill->delWaybill(array('waybill_id' => $waybill_id));
        if($result) {
            $this->log('删除运单模板' . '[ID:' . $waybill_id . ']', 1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            $this->log('删除运单模板' . '[ID:' . $waybill_id . ']', 0);
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 打印测试
     */
    public function waybill_testOp() {
        $model_waybill = Model('waybill');

        $waybill_info = $model_waybill->getWaybillInfoByID($_GET['waybill_id']);
        if(!$waybill_info) {
            showMessage('运单模板不存在');
        }
        Tpl::output('waybill_info', $waybill_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('waybill.test', 'null_layout');
    }

    /**
     * ajax操作
     */
    public function ajaxOp() {
        switch ($_GET['branch']) {
        case 'usable':
            $model_waybill = Model('waybill');
            $where = array('waybill_id' => intval($_GET['id']));
            $update = array('waybill_usable' => intval($_GET['value']));
            $model_waybill->editWaybill($update, $where);
            echo 'true';exit;
            break;
        }
    }

    /**
     * 页面内导航菜单
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function show_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'waybill_list','menu_name'=>'列表', 'menu_url'=>urlAdminShop('waybill', 'waybill_list')),
        );
        if($menu_key == 'waybill_edit') {
            $menu_array[] = array('menu_key'=>'waybill_edit', 'menu_name'=>'编辑', 'menu_url'=>'javascript:;');
        }
        if($menu_key == 'waybill_design') {
            $menu_array[] = array('menu_key'=>'waybill_design', 'menu_name'=>'设计', 'menu_url'=>'javascript:;');
        }
        Tpl::output('menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

}
