<?php
/**
 * 快递公司
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class expressControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('express');
    }

    public function indexOp(){
						
		Tpl::setDirquna('shop');
        Tpl::showpage('express.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model = Model();
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('id', 'e_name', 'e_code', 'e_letter', 'e_url', 'e_order', 'e_state', 'e_zt_state');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $list = $model->table('express')->where($condition)->page($page)->order($order)->select();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($list as $value) {
            $param = array();
            $operation = "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('express','ajax',array('id'=> $value['id'], 'column' => 'e_state', 'value' => ($value['e_state'] == 1 ? 0 : 1))) . "\")'>".($value['e_state'] == 1 ? '禁用快递公司' : '启用快递公司')."</a></li>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('express','ajax',array('id'=> $value['id'], 'column' => 'e_order', 'value' => ($value['e_order'] == 1 ? 0 : 1))) . "\")'>".($value['e_order'] == 1 ? '取消常用快递' : '设为常用快递')."</a></li>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('express','ajax',array('id'=> $value['id'], 'column' => 'e_zt_state', 'value' => ($value['e_zt_state'] == 1 ? 0 : 1))) . "\")'>".($value['e_zt_state'] == 1 ? '取消自提配送' : '设为自提配送')."</a></li>";
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['e_name'] = $value['e_name'];
            $param['e_code'] = $value['e_code'];
            $param['e_letter'] = $value['e_letter'];
            $param['e_url'] = $value['e_url'];
            $param['e_state'] = $value['e_state'] == 1 ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['e_order'] = $value['e_order'] == 1 ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['e_zt_state'] = $value['e_zt_state'] == 1 ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * ajax操作
     */
    public function ajaxOp(){
        switch ($_GET['column']){
            case 'e_state':
                $model_brand = Model('express');
                $update_array = array();
                $update_array['e_state'] = trim($_GET['value']);
                $model_brand->where(array('id'=>intval($_GET['id'])))->update($update_array);
                dkcache('express');
                $this->log(L('nc_edit,express_name,express_state').'[ID:'.intval($_GET['id']).']',1);
                showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();');
                break;
            case 'e_order':
                $_GET['value'] = $_GET['value'] == 0? 2:1;
                $model_brand = Model('express');
                $update_array = array();
                $update_array['e_order'] = trim($_GET['value']);
                $model_brand->where(array('id'=>intval($_GET['id'])))->update($update_array);
                dkcache('express');
                $this->log(L('nc_edit,express_name,express_state').'[ID:'.intval($_GET['id']).']',1);
                showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();');
                break;
            case 'e_zt_state':
                $model_brand = Model('express');
                $update_array = array();
                $update_array['e_zt_state'] = trim($_GET['value']);
                $model_brand->where(array('id'=>intval($_GET['id'])))->update($update_array);
                dkcache('express');
                $this->log(L('nc_edit,express_name,express_state').'[ID:'.intval($_GET['id']).']',1);
                showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();');
                break;
        }
        dkcache('express');
    }

}
