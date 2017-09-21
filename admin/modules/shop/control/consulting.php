<?php
/**
 * 咨询管理
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class consultingControl extends SystemControl{
    private $links = array(
            array('url'=>'act=consulting&op=consulting','text'=>'咨询管理'),
            array('url'=>'act=consulting&op=type_list','text'=>'类型设置'),
            array('url'=>'act=consulting&op=setting','text'=>'头部文字设置')
    );
    public function __construct(){
        parent::__construct();
        Language::read('consulting');
        if ($_GET['op'] == 'index') $_GET['op'] = 'consulting';
        Tpl::output('top_link',$this->sublink($this->links,$_GET['op']));
    }

    public function indexOp() {
        $this->consultingOp();
    }

    /**
     * 咨询管理
     */
    public function consultingOp(){
        //咨询类型
        $consult_type = rkcache('consult_type', true);
        Tpl::output('consult_type', $consult_type);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('consulting.index');
    }

    public function get_consulting_xmlOp(){
        $model_consult  = Model('consult');
        $condition  = array();

        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('consult_content','member_name'))) {
            $condition[$_POST['qtype']] = array('like',"%{$_POST['query']}%");
        }

        if ($_GET['keyword'] != '' && in_array($_GET['keyword_type'],array('consult_content','member_name'))) {
            if ($_GET['jq_query']) {
                $condition[$_GET['keyword_type']] = $_GET['keyword'];
            } else {
                $condition[$_GET['keyword_type']] = array('like',"%{$_GET['keyword']}%");
            }
        }
        if (!in_array($_GET['qtype_time'],array('consult_addtime','consult_reply_time'))) {
            $_GET['qtype_time'] = null;
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']): null;
        if ($_GET['qtype_time'] && ($start_unixtime || $end_unixtime)) {
            $condition[$_GET['qtype_time']] = array('time',array($start_unixtime,$end_unixtime));
        }

        if (!empty($_GET['consult_type']) && $_GET['consult_type'] != '') {
            $condition['ct_id'] = intval($_GET['consult_type']);
        }
        $sort_fields = array('member_id','goods_id','consult_id','consult_reply_time','store_id');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $consult_list = $model_consult->getConsultList($condition,'*',0,$_POST['rp'],$order);
        $data = array();
        $data['now_page'] = $model_consult->shownowpage();
        $data['total_num'] = $model_consult->gettotalnum();
        foreach ($consult_list as $k => $consult_info) {
            $list = array();$operation_detail = '';
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$consult_info['consult_id']})\"><i class=\"fa fa-trash-o\"></i>删除</a>";
            $list['member_name'] = $consult_info['member_name'];
            $list['consult_content'] = "<span title='{$consult_info['consult_content']}'>{$consult_info['consult_content']}</span>";
            $list['consult_reply'] = "<span title='{$consult_info['consult_reply']}'>{$consult_info['consult_reply']}</span>";
            $list['goods_name'] = "<a class='open' title='{$consult_info['goods_name']}' href='". urlShop('goods', 'index', array('goods_id' => $consult_info['goods_id'])) ."' target='blank'>{$consult_info['goods_name']}</a>";
            $list['consult_addtime'] = date('Y-m-d H:i:s',$consult_info['consult_addtime']);
            $list['consult_reply_time'] = !empty($consult_info['consult_reply_time']) ? date('Y-m-d H:i:s',$consult_info['consult_reply_time']) : '';
            $list['store_name'] = "<a class='open' title='{$consult_info['store_name']}' href='". urlShop('show_store','index', array('store_id'=>$consult_info['store_id'])) ."' target='blank'>{$consult_info['store_name']}</a>";
            $list['member_id'] = empty($consult_info['member_id']) ? '' : $consult_info['member_id'];
            $list['store_id'] = $consult_info['store_id'];
            $data['list'][$consult_info['consult_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    public function deleteOp(){
        $model_consult = Model('consult');
        if (preg_match('/^[\d,]+$/', $_GET['consult_id'])) {
            $_GET['consult_id'] = explode(',',trim($_GET['consult_id'],','));
            $del = $model_consult->delConsult(array('consult_id' => array('in', $_GET['consult_id'])));
            if ($del) {
                $this->log(L('nc_delete,consulting').'[ID:'.implode(',',$_GET['consult_id']).']',null);
                exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
            } else {
                exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            }
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 咨询设置
     */
    public function settingOp() {
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['consult_prompt'] = $_POST['consult_prompt'];
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log('编辑咨询文字提示',1);
                showMessage(L('nc_common_save_succ'));
            }else {
                $this->log('编辑咨询文字提示',0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting_list', $setting_list);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('consulting.setting');
    }

    /**
     * 咨询类型列表
     */
    public function type_listOp() {
        $model_ct = Model('consult_type');
        if (chksubmit()) {
            $ctid_array = $_POST['del_id'];
            if (!is_array($ctid_array)) {
                showMessage(L('param_error'));
            }
            foreach ($ctid_array as $val){
                if (!is_numeric($val)) {
                    showMessage(L('param_error'));
                }
            }

            $result = $model_ct->delConsultType(array('ct_id' => array('in', $ctid_array)));

            if ($result) {
                $this->log('删除咨询类型 ID:'.implode(',', $ctid_array), 1);
                showMessage(L('nc_common_del_succ'));
            } else {
                $this->log('删除咨询类型 ID:'.implode(',', $ctid_array), 0);
                showMessage(L('nc_common_del_fail'));
            }
        }
        $type_list = $model_ct->getConsultTypeList(array(), 'ct_id,ct_name,ct_sort');
        Tpl::output('type_list', $type_list);
        Tpl::output('top_link',$this->sublink($this->links,'type_list'));
						
		Tpl::setDirquna('shop');
        Tpl::showpage('consulting.type_list');
    }

    /**
     * 新增咨询类型
     */
    public function type_addOp() {
        if (chksubmit()) {
            // 验证
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["ct_name"], "require"=>"true", "message"=>'请填写咨询类型名称'),
                array("input"=>$_POST["ct_sort"], "require"=>"true", 'validator'=>'Number', "message"=>'请正确填写咨询类型排序'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage(Language::get('error').$error,'','','error');
            }
            $insert = array();
            $insert['ct_name'] = trim($_POST['ct_name']);
            $insert['ct_sort'] = intval($_POST['ct_sort']);
            $insert['ct_introduce'] = $_POST['ct_introduce'];
            $result = Model('consult_type')->addConsultType($insert);
            if ($result){
                $this->log('新增咨询类型',1);
                showMessage(L('nc_common_save_succ'), urlAdminShop('consulting', 'type_list'));
            }else {
                $this->log('新增咨询类型',0);
                showMessage(L('nc_common_save_fail'));
            }
        }
						
		Tpl::setDirquna('shop');
        Tpl::showpage('consulting.type_add');
    }

    /**
     * 编辑咨询类型
     */
    public function type_editOp() {
        $model_ct = Model('consult_type');
        if (chksubmit()) {
            // 验证
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST["ct_name"], "require"=>"true", "message"=>'请填写咨询类型名称'),
                    array("input"=>$_POST["ct_sort"], "require"=>"true", 'validator'=>'Number', "message"=>'请正确填写咨询类型排序'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage(Language::get('error').$error,'','','error');
            }
            $where = array();
            $where['ct_id'] = intval($_POST['ct_id']);
            $update = array();
            $update['ct_name'] = trim($_POST['ct_name']);
            $update['ct_sort'] = intval($_POST['ct_sort']);
            $update['ct_introduce'] = $_POST['ct_introduce'];
            $result = $model_ct->editConsultType($where, $update);
            if ($result){
                $this->log('编辑咨询类型 ID:'.$where['ct_id'],1);
                showMessage(L('nc_common_op_succ'), urlAdminShop('consulting', 'type_list'));
            }else {
                $this->log('编辑咨询类型 ID:'.$where['ct_id'],0);
                showMessage(L('nc_common_op_fail'));
            }
        }

        $ct_id = intval($_GET['ct_id']);
        if ($ct_id <= 0) {
            showMessage(L('param_error'));
        }
        $ct_info = $model_ct->getConsultTypeInfo(array('ct_id' => $ct_id));
        Tpl::output('ct_info', $ct_info);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('consulting.type_edit');
    }

    /**
     * 删除咨询类型
     */
    public function type_delOp() {
        $ct_id = intval($_GET['ct_id']);
        if ($ct_id <= 0) {
            showMessage(L('param_error'));
        }
        $result = Model('consult_type')->delConsultType(array('ct_id' => $ct_id));
        if ($result) {
            $this->log('删除咨询类型 ID:'.$ct_id, 1);
            showMessage(L('nc_common_del_succ'));
        } else {
            $this->log('删除咨询类型 ID:'.$ct_id, 0);
            showMessage(L('nc_common_del_fail'));
        }
    }
}
