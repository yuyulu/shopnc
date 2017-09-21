<?php
/**
 * 消费者保障服务管理
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */


defined('In33hao') or exit('Access Invalid!');
class contractControl extends SystemControl{
    private $itemstate_arr;
    private $contract_auditstate_arr;
    private $contract_joinstate_arr;
    private $contract_closestate_arr;
    private $contract_quitstate_arr;
    private $join_auditstate_arr;
    private $quit_auditstate_arr;

    public function __construct(){
        parent::__construct();
        if (C('contract_allow') != 1){
            showDialog('需开启“消费者保障服务”功能','index.php?act=operating','error');
        }
        $model_contract = Model('contract');
        $this->itemstate_arr = $model_contract->getItemState();
        $this->contract_auditstate_arr = $model_contract->getContractAuditState();
        $this->contract_joinstate_arr = $model_contract->getContractJoinState();
        $this->contract_closestate_arr = $model_contract->getContractCloseState();
        $this->contract_quitstate_arr = $model_contract->getContractQuitState();
        $this->join_auditstate_arr = $model_contract->getJoinAuditState();
        $this->quit_auditstate_arr = $model_contract->getQuitAuditState();
    }
    public function indexOp(){
        $this->applylistOp();
    }
    /**
     * 保障服务项目列表
     */
    public function citemlistOp()
    {
        $this->show_menu('citemlist');
						
		Tpl::setDirquna('shop');
        Tpl::showpage('contract.itemlist');
    }
    /**
     * 保障服务项目列表XML
     */
    public function citemlist_xmlOp()
    {
        $model_contract = Model('contract');
        $list = $model_contract->contractItemList(array(),'cti_state desc,cti_sort asc,cti_name asc');
        $data = array();
        $data['now_page'] = 1;
        $data['total_num'] = count($list);
        foreach ($list as $val) {
            $o = '<a class="btn blue" href="' . urlAdminShop('contract', 'citemedit', array(
                    'itemid' => $val['cti_id'],
                )) . '"><i class="fa fa-pencil-square-o"></i>编辑</a>';
            $i = array();
            $i['operation'] = $o;
            $i['cti_sort'] = $val['cti_sort'];
            $i['cti_name'] = $val['cti_name'];
            $i['cti_cost'] = $val['cti_cost'];
            $i['cti_state_text'] = $val['cti_state_text'];
            $data['list'][$val['cti_id']] = $i;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }

    /*
     * 保障服务项目编辑
     */
    public function citemeditOp(){
        $itemid_id = intval($_GET['itemid']);
        if ($itemid_id <= 0){
            $itemid_id = intval($_POST['itemid_id']);
        }
        if ($itemid_id <= 0){
            showDialog(L('param_error'),'index.php?act=contract&op=citemlist');
        }
        $model_contract = Model('contract');
        //查询模板信息
        $where = array();
        $where['cti_id'] = $itemid_id;
        $item_info = $model_contract->getContractItemInfo($where);
        if (!$item_info){
            showDialog(L('param_error'),'index.php?act=contract&op=citemlist');
        }
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST['item_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>'项目名称不能为空且不能大于50个字符'),
                    array("input"=>$_POST['item_cost'], "require"=>"true","validator"=>"double","min"=>"1","message"=>'保证金应为大于1的数字'),
                    array("input"=>$_POST['item_desc'], "require"=>"true","message"=>'请添加项目描述'),
                    array("input"=>$_POST['item_sort'], "require"=>"true","validator"=>"Number","min"=>"1","message"=>'排序应为大于1的正整数')
            );
            $error = $obj_validate->validate();
            if ($error){
                showDialog($error, '', 'error');
            }
            $update_arr = array();
            $update_arr['cti_name'] = trim($_POST['item_name']);
            $update_arr['cti_describe'] = trim($_POST['item_desc']);
            $update_arr['cti_cost'] = floatval($_POST['item_cost']);
            $update_arr['cti_descurl'] = $_POST['item_descurl'];
            $update_arr['cti_state'] = $this->itemstate_arr[$_POST['item_state']]['sign'];
            $update_arr['cti_sort'] = intval($_POST['item_sort']);
            //自定义图片
            if (!empty($_FILES['item_icon']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir', ATTACH_CONTRACTICON);
                $upload->set('max_size',200);//小于200k
                $upload->set('thumb_width','40,60');
                $upload->set('thumb_height','40,60');
                $upload->set('thumb_ext','_40,_60');
                $result = $upload->upfile('item_icon');
                if ($result){
                    $update_arr['cti_icon'] =  $upload->file_name;
                    //删除旧图片
                    if ($item_info['cti_icon'] && is_file(BASE_UPLOAD_PATH . '/' . ATTACH_CONTRACTICON . '/' . $item_info['cti_icon'])) {
                        @unlink(BASE_UPLOAD_PATH . '/' . ATTACH_CONTRACTICON . '/' . $item_info['cti_icon']);
                        @unlink(BASE_UPLOAD_PATH . '/' . ATTACH_CONTRACTICON . '/' . str_ireplace('.', '_60.', $item_info['cti_icon']));
                    }
                }else{
                    showDialog($upload->error,'','error');
                }
            }
            $rs = $model_contract->editContractItem(array('cti_id'=>$itemid_id),$update_arr);
            if($rs){
                //记录日志
                $this->log('消费者保障服务修改成功');
                showDialog(L('nc_common_save_succ'),'index.php?act=contract&op=citemlist','succ');
            } else {
                showDialog(L('nc_common_save_fail'),'','error');
            }
        }else{
            TPL::output('itemstate_arr',$this->itemstate_arr);
            TPL::output('item_info',$item_info);
							
		Tpl::setDirquna('shop');
            Tpl::showpage('contract.citemedit');
        }
    }

    /**
     * 店铺保障服务列表
     */
    public function contractlistOp(){
        //查询保障项目
        $item_list = Model('contract')->getContractItemByCache('all');
        TPL::output('item_list',$item_list);
        TPL::output('contract_joinstate_arr',$this->contract_joinstate_arr);
        TPL::output('contract_closestate_arr',$this->contract_closestate_arr);
        $this->show_menu('contractlist');
						
		Tpl::setDirquna('shop');
        Tpl::showpage('contract.list');
    }

    /**
     * 店铺保障服务列表XML
     */
    public function contractlist_xmlOp()
    {
        $where = array();
        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['search_storename']))) {
                $where['ct_storename'] = array('like', '%' . $q . '%');
            }
            if (($q = (int) $_REQUEST['search_itemid']) > 0) {
                $where['ct_itemid'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['search_state']))) {
                $where['ct_joinstate'] = $this->contract_joinstate_arr[$q]['sign'];
            }
            if (strlen($q = trim((string) $_REQUEST['search_closestate']))) {
                $where['ct_closestate'] = $this->contract_closestate_arr[$q]['sign'];
            }
        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'ct_storename':
                        $where['ct_storename'] = array('like', '%'.$q.'%');
                        break;
                }
            }
        }
        switch ($_REQUEST['sortname']) {
            case 'ct_cost':
                $sort = $_REQUEST['sortname'];
                break;
            default:
                $sort = 'ct_id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }
        $model_contract = Model('contract');
        //查询保障项目
        $item_list = $model_contract->getContractItemByCache('all');
        //查询店铺保障项目
        $c_list = $model_contract->getContractList($where, '*', 0, $_REQUEST['rp'], $sort);
        $data = array();
        $data['now_page'] = $model_contract->shownowpage();
        $data['total_num'] = $model_contract->gettotalnum();
        foreach ($c_list as $val) {
            $o = '<a class="btn blue" href="' . urlAdminShop('contract', 'contractedit', array(
                    'ct_id' => $val['ct_id'],
                )) . '"><i class="fa fa-pencil-square-o"></i>编辑</a>';

            $o .= "<span class='btn'><em><i class='fa fa-cog'></i>设置 <i class='arrow'></i></em><ul>";
            $o .= "<li><a href='" . urlAdminShop('contract', 'contractinfo', array('ct_id' => $val['ct_id'])) . "'>查看详情</a></li>";
            $o .= "<li><a href='" . urlAdminShop('contract', 'costedit', array('ct_id' => $val['ct_id'])) . "'>编辑保证金</a></li>";
            $o .= "<li><a href='" . urlAdminShop('contract', 'costlog', array('ct_id' => $val['ct_id'])) . "'>保证金日志</a></li>";
            $o .= "</ul>";

            $i = array();
            $i['operation'] = $o;
            $i['ct_storename'] = $val['ct_storename'];
            $i['ct_itemname'] = $item_list[$val['ct_itemid']]['cti_name'];
            $i['ct_cost'] = $val['ct_cost'];
            if ($val['ct_state_sign'] == 'applying') {
                $i['ct_state_text'] = $val['ct_state_text']."（{$val['ct_auditstate_text']}）";
            }else{
                $i['ct_state_text'] = $val['ct_state_text'];
            }
            $data['list'][$val['ct_id']] = $i;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }

    /*
     * 店铺保障服务编辑
     */
    public function contracteditOp(){
        $ct_id = intval($_GET['ct_id']);
        if ($ct_id <= 0){
            $ct_id = intval($_POST['ct_id']);
        }
        if ($ct_id <= 0){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        $model_contract = Model('contract');
        //查询店铺保障服务信息
        $where = array();
        $where['ct_id'] = $ct_id;
        $c_info = $model_contract->getContractInfo($where);
        if (!$c_info){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        //查询保障项目
        $item_info = $model_contract->getContractItemInfoByCache($c_info['ct_itemid']);
        if (!$item_info){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        if(chksubmit()){
            if (!in_array($_POST['c_state'],array_keys($this->contract_closestate_arr))) {
                showDialog(L('nc_common_save_fail'), '', 'error');
            }
            //如果关闭状态没有修改，直接退出
            if ($c_info['ct_closestate_key'] == $_POST['c_state']) {
                showDialog(L('nc_common_save_succ'),'index.php?act=contract&op=contractlist','succ');
            }
            try {
                $model_contract->beginTransaction();
                //编辑店铺保障服务信息
                $update_arr = array();
                $update_arr['ct_closestate'] = $this->contract_closestate_arr[$_POST['c_state']]['sign'];
                $where = array();
                $where['ct_id'] = $ct_id;
                $result = $model_contract->editContract($where, $update_arr);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                //新增保障项目日志
                $log_msg = "关闭状态修改为“{$this->contract_closestate_arr[$_POST['c_state']]['name']}”";
                switch($_POST['c_state']){
                    case 'close':
                        $log_msg .='，原因：'.trim($_POST['c_reason']);
                        break;
                }
                $result = $this->saveContractLog($item_info, array('store_id'=>$c_info['ct_storeid'],'store_name'=>$c_info['ct_storename']), $log_msg);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                //记录日志
                $result = $this->log('店铺['.$c_info['ct_storename'].']的消费者保障服务['.$item_info['cti_name'].']'.$this->contract_closestate_arr[$_POST['c_state']]['name']);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                $model_contract->commit();
                //如果修改了关闭状态，则更新店铺商品状态
                if ($c_info['ct_closestate_key'] != $_POST['c_state']) {
                    QueueClient::push('updateStoreGoodsContract', array('store_id'=>$c_info['ct_storeid'],'item_id'=>$c_info['ct_itemid']));
                }
                showDialog(L('nc_common_save_succ'),'index.php?act=contract&op=contractlist','succ');
            }catch (Exception $e){
                $model_contract->rollback();
                showDialog($e->getMessage(), '', 'error');
            }
        }else{
            TPL::output('c_info',$c_info);
            TPL::output('item_info',$item_info);
            TPL::output('contractclosestate_arr',$this->contract_closestate_arr);
							
		Tpl::setDirquna('shop');
            Tpl::showpage('contract.contractedit');
        }
    }

    /**
     * 服务加入申请列表
     */
    public function applylistOp(){
        //查询保障项目
        $item_list = Model('contract')->getContractItemByCache('all');
        TPL::output('item_list',$item_list);
        TPL::output('join_auditstate_arr',$this->join_auditstate_arr);
        $this->show_menu('applylist');
						
		Tpl::setDirquna('shop');
        Tpl::showpage('contract.applylist');
    }

    /**
     * 服务加入申请列表XML
     */
    public function applylist_xmlOp()
    {
        $where = array();
        $sort = '';
        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['search_storename']))) {
                $where['cta_storename'] = array('like', '%' . $q . '%');
            }
            if (($q = (int) $_REQUEST['search_itemid']) > 0) {
                $where['cta_itemid'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['search_state']))) {
                $where['cta_auditstate'] = $this->join_auditstate_arr[$q]['sign'];
            }
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
            if (trim($sdate) && trim($edate)) {
                $sdate = strtotime($sdate);
                $edate = strtotime($edate);
                $where['cta_addtime'] = array('between', "$sdate,$edate");
            } elseif (trim($sdate)) {
                $sdate = strtotime($sdate);
                $where['cta_addtime'] = array('egt', $sdate);
            } elseif (trim($edate)) {
                $edate = strtotime($edate);
                $where['cta_addtime'] = array('elt', $edate);
            }
        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'cta_storename':
                        $where['cta_storename'] = array('like', '%'.$q.'%');
                        break;
                }
            }
        }
        switch ($_REQUEST['sortname']) {
            case 'cta_auditstate_text':
                $sort = 'cta_auditstate';
                break;
            default:
                $sort = 'cta_id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }

        $model_contract = Model('contract');
        //查询保障项目
        $item_list = $model_contract->getContractItemByCache('all');
        //查询申请列表
        $apply_list = $model_contract->getContractApplyList($where, '*', 0, $_REQUEST['rp'], $sort);
        $data = array();
        $data['now_page'] = $model_contract->shownowpage();
        $data['total_num'] = $model_contract->gettotalnum();
        foreach ($apply_list as $val) {
            $o = '';
            if (in_array($val['cta_auditstate_key'],array('notaudit','costpay'))) {
                $o = '<a class="btn blue" href="' . urlAdminShop('contract', 'applyedit', array(
                        'aid' => $val['cta_id'],
                    )) . '"><i class="fa fa-pencil-square-o"></i>编辑</a>';
            } else {
                $o .= '<a class="btn green" href="' . urlAdminShop('contract', 'applyinfo', array(
                        'aid' => $val['cta_id'],
                    )) . '"><i class="fa fa-list-alt"></i>查看</a>';
            }

            $i = array();
            $i['operation'] = $o;
            $i['cta_storename'] = $val['cta_storename'];
            $i['itemname'] = $item_list[$val['cta_itemid']]['cti_name'];
            $i['cta_addtime'] = @date('Y-m-d H:i:s',$val['cta_addtime']);
            $i['cta_auditstate_text'] = $val['cta_auditstate_text'];
            $data['list'][$val['cta_id']] = $i;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }

    /*
     * 保障服务申请编辑
     */
    public function applyeditOp(){
        $aid = intval($_GET['aid']);
        if ($aid <= 0){
            $aid = intval($_POST['aid']);
        }
        if ($aid <= 0){
            showDialog(L('param_error'),'index.php?act=contract&op=applylist');
        }
        $model_contract = Model('contract');
        //查询申请信息
        $where = array();
        $where['cta_id'] = $aid;
        $where['cta_auditstate'] = array('in',array($this->join_auditstate_arr['notaudit']['sign'],$this->join_auditstate_arr['costpay']['sign']));
        $apply_info = $model_contract->getContractApplyInfo($where);
        if (!$apply_info){
            showDialog(L('param_error'),'index.php?act=contract&op=applylist');
        }
        //查询保障项目
        $item_info = $model_contract->getContractItemInfoByCache($apply_info['cta_itemid']);
        if (!$item_info){
            showDialog(L('param_error'),'index.php?act=contract&op=applylist');
        }
        //当前可以编辑的审核状态
        $curr_applystatearr = array();
        foreach($this->join_auditstate_arr as $k=>$v){
            if ($apply_info['cta_auditstate_key'] == 'notaudit' && in_array($k,array('notaudit','auditpass','auditfailure'))) {
                $curr_applystatearr[$k] = $v;
            }
            if ($apply_info['cta_auditstate_key'] == 'costpay' && in_array($k,array('costpay','costpass','costfailure'))) {
                $curr_applystatearr[$k] = $v;
            }
        }
        if(chksubmit()){
            if ($_POST['apply_state'] == $apply_info['cta_auditstate_key']) {
                showDialog('不能重复更改申请状态','index.php?act=contract&op=applylist');
            }
            if (!in_array($_POST['apply_state'],array_keys($curr_applystatearr))) {
                showDialog('编辑失败','index.php?act=contract&op=applyedit&aid='.$apply_info['cta_id']);
            }
            try {
                $model_contract->beginTransaction();
                //编辑申请状态
                $update_arr = array();
                $update_arr['cta_auditstate'] = $this->join_auditstate_arr[$_POST['apply_state']]['sign'];
                $result = $model_contract->editContractApply(array('cta_id'=>$aid),$update_arr);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                //编辑店铺保障服务信息
                $update_arr = array();
                $update_arr['ct_auditstate'] = $this->contract_auditstate_arr[$_POST['apply_state']]['sign'];
                if ($_POST['apply_state'] == 'costpass') {//如果保证金通过审核，则状态修改为已加入
                    $update_arr['ct_joinstate'] = $this->contract_joinstate_arr['added']['sign'];
                    $update_arr['ct_cost'] = array('exp','ct_cost+'.$apply_info['cta_cost']);
                }
                if ($_POST['apply_state'] == 'auditfailure') {//如果审核失败，则状态修改为未申请
                    $update_arr['ct_joinstate'] = $this->contract_joinstate_arr['notapply']['sign'];
                }
                $where = array();
                $where['ct_storeid'] = $apply_info['cta_storeid'];
                $where['ct_itemid'] = $apply_info['cta_itemid'];
                $result = $model_contract->editContract($where, $update_arr);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                if ($_POST['apply_state'] == 'costpass') {//如果保证金通过审核，则增加保证金日志
                    //增加保证金日志
                    $insert_arr = array();
                    $insert_arr['clog_itemid'] = $item_info['cti_id'];
                    $insert_arr['clog_itemname'] = $item_info['cti_name'];
                    $insert_arr['clog_storeid'] = $apply_info['cta_storeid'];
                    $insert_arr['clog_storename'] = $apply_info['cta_storename'];
                    $insert_arr['clog_adminid'] = $this->admin_info['id'];
                    $insert_arr['clog_adminname'] = $this->admin_info['name'];
                    $insert_arr['clog_price'] = $apply_info['cta_cost'];
                    $insert_arr['clog_addtime'] = time();
                    $insert_arr['clog_desc'] = '申请加入保障服务，支付保证金';
                    $result = $model_contract->addContractCostlog($insert_arr);
                    if (!$result) {
                        throw new Exception(L('nc_common_save_fail'));
                    }
                }
                //新增保障项目日志
                $log_msg = '';
                switch($_POST['apply_state']){
                    case 'auditfailure':
                        $log_msg = $this->join_auditstate_arr[$_POST['apply_state']]['name'].'，原因：'.trim($_POST['apply_reason']);
                        break;
                    case 'costfailure':
                        $log_msg = $this->join_auditstate_arr[$_POST['apply_state']]['name'].'，原因：'.trim($_POST['apply_reason']);
                        break;
                    default:
                        $log_msg = $this->join_auditstate_arr[$_POST['apply_state']]['name'];
                }
                $result = $this->saveContractLog($item_info, array('store_id'=>$apply_info['cta_storeid'],'store_name'=>$apply_info['cta_storename']), $log_msg);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                //记录日志
                $result = $this->log('消费者保障服务申请[ID：'.$aid.']审核状态修改为'.$this->join_auditstate_arr[$_POST['apply_state']]['name']);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                $model_contract->commit();
                //更新店铺商品的保障服务信息
                if ($_POST['apply_state'] == 'costpass') {//如果保证金通过审核，则更新店铺商品
                    QueueClient::push('updateStoreGoodsContract', array('store_id'=>$apply_info['cta_storeid'],'item_id'=>$apply_info['cta_itemid']));
                }
                showDialog(L('nc_common_save_succ'),'index.php?act=contract&op=applylist','succ');
            }catch (Exception $e){
                $model_contract->rollback();
                showDialog($e->getMessage(), '', 'error');
            }
        }else{
            TPL::output('apply_info',$apply_info);
            TPL::output('item_info',$item_info);
            TPL::output('curr_applystatearr',$curr_applystatearr);
							
		Tpl::setDirquna('shop');
            Tpl::showpage('contract.applyedit');
        }
    }

    /*
     * 保障服务申请详情
     */
    public function applyinfoOp(){
        $aid = intval($_GET['aid']);
        if ($aid <= 0){
            showDialog(L('param_error'),'index.php?act=contract&op=applylist');
        }
        $model_contract = Model('contract');
        //查询申请信息
        $where = array();
        $where['cta_id'] = $aid;
        $apply_info = $model_contract->getContractApplyInfo($where);
        if (!$apply_info){
            showDialog(L('param_error'),'index.php?act=contract&op=applylist');
        }
        //查询保障项目
        $item_info = $model_contract->getContractItemInfoByCache($apply_info['cta_itemid']);
        if (!$item_info){
            showDialog(L('param_error'),'index.php?act=contract&op=applylist');
        }
        TPL::output('apply_info',$apply_info);
        TPL::output('item_info',$item_info);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('contract.applyinfo');
    }
    /*
     * 保障服务申请编辑
     */
    public function costeditOp(){
        $ct_id = intval($_GET['ct_id']);
        if ($ct_id <= 0) {
            $ct_id = intval($_POST['ct_id']);
        }
        if ($ct_id <= 0){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        $model_contract = Model('contract');
        //查询店铺保障服务
        $where = array();
        $where['ct_id'] = $ct_id;
        $c_info = $model_contract->getContractInfo($where);
        if (!$c_info){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        //查询保障项目
        $item_info = $model_contract->getContractItemInfoByCache($c_info['ct_itemid']);
        if (!$item_info){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        if(chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST['operatetype'], "require"=>"true","message"=>'请选择增减类型'),
                array("input"=>$_POST['price'], "require"=>"true","validator"=>"Number","min"=>"0","message"=>'金额不能为空且为大于0数字'),
                array("input"=>$_POST['clog_desc'], "require"=>"true","message"=>'原因描述必填且小于200个字符')
            );
            $error = $obj_validate->validate();
            if ($error){
                showDialog($error, '', 'error');
            }
            $price = floatval($_POST['price']);
            if ($_POST['operatetype'] == '1') {
                $price = -$price;
            }
            //减少保证金时，不能大于当前拥有的保证金金额
            if (($c_info['ct_cost']+$price) < 0) {
                showDialog('减少的保证金不能大于当前余额');
            }
            try {
                $model_contract->beginTransaction();
                //增加保证金日志
                $insert_arr = array();
                $insert_arr['clog_itemid'] = $item_info['cti_id'];
                $insert_arr['clog_itemname'] = $item_info['cti_name'];
                $insert_arr['clog_storeid'] = $c_info['ct_storeid'];
                $insert_arr['clog_storename'] = $c_info['ct_storename'];
                $insert_arr['clog_adminid'] = $this->admin_info['id'];
                $insert_arr['clog_adminname'] = $this->admin_info['name'];
                $insert_arr['clog_price'] = $price;
                $insert_arr['clog_addtime'] = time();
                $insert_arr['clog_desc'] = '管理员操作保证金，原因描述：'.$_POST['clog_desc'];
                $result = $model_contract->addContractCostlog($insert_arr);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                //编辑店铺保障服务信息
                $update_arr = array();
                $update_arr['ct_cost'] = array('exp','ct_cost+'.$price);
                $where = array();
                $where['ct_id'] = $ct_id;
                $result = $model_contract->editContract($where, $update_arr);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                $price_msg = '增加';
                if ($_POST['operatetype'] == '1') {
                    $price_msg = '减少';
                }
                $price_msg .= (ncPriceFormat(abs($price)).L('currency_zh'));
                //新增保障项目日志
                $result = $this->saveContractLog($item_info, array('store_id'=>$c_info['ct_storeid'],'store_name'=>$c_info['ct_storename']), '保证金'.$price_msg);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                //记录日志
                $result = $this->log('店铺['.$c_info['ct_storename'].']的保障服务['.$item_info['cti_name'].']保证金'.$price_msg);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                $model_contract->commit();
                showDialog(L('nc_common_save_succ'),'index.php?act=contract&op=contractlist','succ');
            }catch (Exception $e){
                $model_contract->rollback();
                showDialog($e->getMessage(), '', 'error');
            }
        }else{
            TPL::output('item_info',$item_info);
            TPL::output('c_info',$c_info);
							
		Tpl::setDirquna('shop');
            Tpl::showpage('contract.costedit');
        }
    }

    /**
     * 服务退出申请列表
     */
    public function quitlistOp(){
        //查询保障项目
        $item_list = Model('contract')->getContractItemByCache('all');
        TPL::output('item_list',$item_list);
        TPL::output('quit_auditstate_arr',$this->quit_auditstate_arr);
        $this->show_menu('quitlist');
						
		Tpl::setDirquna('shop');
        Tpl::showpage('contract.quitlist');
    }

    /**
     * 服务退出申请列表XML
     */
    public function quitlist_xmlOp()
    {
        $where = array();
        $sort = '';
        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['search_storename']))) {
                $where['ctq_storename'] = array('like', '%' . $q . '%');
            }
            if (($q = (int) $_REQUEST['search_itemid']) > 0) {
                $where['ctq_itemid'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['search_state']))) {
                $where['ctq_auditstate'] = $this->quit_auditstate_arr[$q]['sign'];
            }
            $sdate = $_REQUEST['sdate'];
            $edate = $_REQUEST['edate'];
            if (trim($sdate) && trim($edate)) {
                $sdate = strtotime($sdate);
                $edate = strtotime($edate);
                $where['ctq_addtime'] = array('between', "$sdate,$edate");
            } elseif (trim($sdate)) {
                $sdate = strtotime($sdate);
                $where['ctq_addtime'] = array('egt', $sdate);
            } elseif (trim($edate)) {
                $edate = strtotime($edate);
                $where['ctq_addtime'] = array('elt', $edate);
            }
        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'ctq_storename':
                        $where['ctq_storename'] = array('like', '%'.$q.'%');
                        break;
                }
            }
        }
        switch ($_REQUEST['sortname']) {
            case 'ctq_auditstate_text':
                $sort = 'ctq_auditstate';
                break;
            default:
                $sort = 'ctq_id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }
        $model_contract = Model('contract');
        $quit_list = $model_contract->getQuitApplyList($where, '*', 0, $_REQUEST['rp'], $sort);
        $data = array();
        $data['now_page'] = $model_contract->shownowpage();
        $data['total_num'] = $model_contract->gettotalnum();
        foreach ($quit_list as $val) {
            $o = '';
            if ($val['ctq_auditstate_key'] == 'notaudit') {
                $o = '<a class="btn blue" href="' . urlAdminShop('contract', 'quitedit', array(
                        'qid' => $val['ctq_id'],
                    )) . '"><i class="fa fa-pencil-square-o"></i>编辑</a>';
            } else {
                $o .= '<a class="btn green" href="' . urlAdminShop('contract', 'quitinfo', array(
                        'qid' => $val['ctq_id'],
                    )) . '"><i class="fa fa-list-alt"></i>查看</a>';
            }

            $i = array();
            $i['operation'] = $o;
            $i['ctq_storename'] = $val['ctq_storename'];
            $i['ctq_itemname'] = $val['ctq_itemname'];
            $i['ctq_addtime'] = @date('Y-m-d H:i:s',$val['ctq_addtime']);
            $i['ctq_auditstate_text'] = $val['ctq_auditstate_text'];
            $data['list'][$val['ctq_id']] = $i;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }

    /*
     * 保障服务申请编辑
     */
    public function quiteditOp(){
        $qid = intval($_GET['qid']);
        if ($qid <= 0){
            $qid = intval($_POST['qid']);
        }
        if ($qid <= 0){
            showDialog(L('param_error'),'index.php?act=contract&op=quitlist');
        }
        $model_contract = Model('contract');
        //查询申请信息
        $where = array();
        $where['ctq_id'] = $qid;
        $where['ctq_auditstate'] = $this->quit_auditstate_arr['notaudit']['sign'];
        $quit_info = $model_contract->getQuitApplyInfo($where);
        if (!$quit_info){
            showDialog(L('param_error'),'index.php?act=contract&op=quitlist');
        }
        if(chksubmit()){
            if ($_POST['quit_state'] == $quit_info['ctq_auditstate_key']) {
                showDialog('不能重复编辑申请状态','index.php?act=contract&op=quitlist');
            }
            if (!in_array($_POST['quit_state'],array_keys($this->quit_auditstate_arr))) {
                showDialog('编辑失败','index.php?act=contract&op=quitedit&qid='.$quit_info['ctq_id']);
            }
            try {
                $model_contract->beginTransaction();
                //编辑申请状态
                $update_arr = array();
                $update_arr['ctq_auditstate'] = $this->quit_auditstate_arr[$_POST['quit_state']]['sign'];
                $result = $model_contract->editQuitApply(array('ctq_id'=>$qid),$update_arr);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                //编辑店铺保障服务信息
                $update_arr = array();
                if ($_POST['quit_state'] == 'auditpass') {
                    //审核通过则店铺保障服务改为未申请
                    $update_arr['ct_quitstate'] = $this->contract_quitstate_arr['notapply']['sign'];
                    $update_arr['ct_joinstate'] = $this->contract_joinstate_arr['notapply']['sign'];
                }
                if ($_POST['quit_state'] == 'auditfailure') {
                    $update_arr['ct_quitstate'] = $this->contract_quitstate_arr['applyfailure']['sign'];
                }
                $where = array();
                $where['ct_storeid'] = $quit_info['ctq_storeid'];
                $where['ct_itemid'] = $quit_info['ctq_itemid'];
                $result = $model_contract->editContract($where, $update_arr);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                //新增保障项目日志
                $log_itemarr = array('cti_id'=>$quit_info['ctq_itemid'],'cti_name'=>$quit_info['ctq_itemname']);
                $log_storearr = array('store_id'=>$quit_info['ctq_storeid'],'store_name'=>$quit_info['ctq_storename']);
                $logmsg = '';
                switch($_POST['quit_state']){
                    case 'auditpass':
                        $logmsg = '管理员审核通过店铺退出保障服务的申请';
                        break;
                    case 'auditfailure':
                        $logmsg = '管理员拒绝店铺退出保障服务的申请';
                        if (trim($_POST['quit_remark'])) {
                            $logmsg .= ('，原因：'.$_POST['quit_remark']);
                        }
                        break;
                }
                $result = $this->saveContractLog($log_itemarr, $log_storearr, $logmsg);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                //记录日志
                $result = $this->log('消费者保障服务退出申请[ID：'.$qid.']审核状态修改为'.$this->quit_auditstate_arr[$_POST['quit_state']]['name']);
                if (!$result) {
                    throw new Exception(L('nc_common_save_fail'));
                }
                $model_contract->commit();
                //更新店铺商品的保障服务信息
                if ($_POST['quit_state'] == 'auditpass') {//如果通过审核
                    QueueClient::push('updateStoreGoodsContract', array('store_id'=>$quit_info['ctq_storeid'],'item_id'=>$quit_info['ctq_itemid']));
                }
                showDialog(L('nc_common_save_succ'),'index.php?act=contract&op=quitlist','succ');
            }catch (Exception $e){
                $model_contract->rollback();
                showDialog($e->getMessage(), '', 'error');
            }
        }else{
            TPL::output('quit_info',$quit_info);
            TPL::output('quit_auditstate_arr',$this->quit_auditstate_arr);
							
		Tpl::setDirquna('shop');
            Tpl::showpage('contract.quitedit');
        }
    }

    /*
     * 保障服务退出申请详情
     */
    public function quitinfoOp(){
        $qid = intval($_GET['qid']);
        if ($qid <= 0){
            showDialog(L('param_error'),'index.php?act=contract&op=quitlist');
        }
        //查询申请信息
        $where = array();
        $where['ctq_id'] = $qid;
        $quit_info = Model('contract')->getQuitApplyInfo($where);
        if (!$quit_info){
            showDialog(L('param_error'),'index.php?act=contract&op=quitlist');
        }
        TPL::output('quit_info',$quit_info);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('contract.quitinfo');
    }
    /*
     * 店铺保障服务详情
     */
    public function contractinfoOp(){
        $ct_id = intval($_GET['ct_id']);
        if ($ct_id <= 0){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        $model_contract = Model('contract');
        //查询店铺保障服务
        $where = array();
        $where['ct_id'] = $ct_id;
        $c_info = $model_contract->getContractInfo($where);
        if (!$c_info){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        //查询保障项目
        $item_info = $model_contract->getContractItemInfoByCache($c_info['ct_itemid']);
        if (!$item_info){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        TPL::output('c_info',$c_info);
        TPL::output('item_info',$item_info);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('contract.contractinfo');
    }
    /**
     * 保障服务日志列表XML
     */
    public function contractlog_xmlOp()
    {
        $item_id = intval($_GET['item_id']);
        $store_id = intval($_GET['store_id']);
        if ($item_id <= 0 || $store_id <= 0) {
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        $model_contract = Model('contract');
        $where = array();
        $where['log_itemid'] = $item_id;
        $where['log_storeid'] = $store_id;
        $sort = 'log_id desc';
        $log_list = $model_contract->getContractLogList($where, '*', 0, $_REQUEST['rp'], $sort);
        $data = array();
        $data['now_page'] = $model_contract->shownowpage();
        $data['total_num'] = $model_contract->gettotalnum();
        //角色数组
        $logrole_arr = $model_contract->getLogRole();
        foreach ($log_list as $val) {
            $i = array();
            $i['log_storename'] = $val['log_storename'];
            $i['log_itemname'] = $val['log_itemname'];
            $i['log_addtime'] = @date('Y-m-d H:i:s',$val['log_addtime']);
            $i['log_username'] = "{$val['log_username']}（{$logrole_arr[$val['log_role']]}）";
            $i['log_msg'] = $val['log_msg'];
            $data['list'][$val['log_id']] = $i;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }
    /*
     * 保证金日志
     */
    public function costlogOp(){
        $ct_id = intval($_GET['ct_id']);
        if ($ct_id <= 0){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        $model_contract = Model('contract');
        //查询店铺保障服务
        $where = array();
        $where['ct_id'] = $ct_id;
        $c_info = $model_contract->getContractInfo($where);
        if (!$c_info){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        //查询保障项目
        $item_info = $model_contract->getContractItemInfoByCache($c_info['ct_itemid']);
        if (!$item_info){
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        TPL::output('c_info',$c_info);
        TPL::output('item_info',$item_info);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('contract.costlog');
    }
    /**
     * 保证金列表XML
     */
    public function costlist_xmlOp()
    {
        $item_id = intval($_GET['item_id']);
        $store_id = intval($_GET['store_id']);
        if ($item_id <= 0 || $store_id <= 0) {
            showDialog(L('param_error'),'index.php?act=contract&op=contractlist');
        }
        $model_contract = Model('contract');
        $where = array();
        $where['clog_itemid'] = $item_id;
        $where['clog_storeid'] = $store_id;
        $sort = 'clog_id desc';
        $cost_list = $model_contract->getContractCostlogList($where, '*', 0, $_REQUEST['rp'], $sort);

        $data = array();
        $data['now_page'] = $model_contract->shownowpage();
        $data['total_num'] = $model_contract->gettotalnum();
        foreach ($cost_list as $val) {
            $i = array();
            $i['clog_storename'] = $val['clog_storename'];
            $i['clog_itemname'] = $val['clog_itemname'];
            $i['clog_price'] = $val['clog_price'];
            $i['clog_addtime'] = @date('Y-m-d H:i:s',$val['clog_addtime']);
            $i['clog_adminname'] = $val['clog_adminname'];
            $i['clog_desc'] = $val['clog_desc'];
            $data['list'][$val['clog_id']] = $i;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }
    /**
     * 更新店铺商品保障服务状态
     */
    public function contractgoodsOp(){
        $item_id = $_GET['item_id'];
        $store_id = $_GET['store_id'];
        if ($item_id <= 0 || $store_id <= 0) {
            showDialog(L('param_error'),'','error');
        }
        //更新商品信息
        QueueClient::push('updateStoreGoodsContract', array('store_id'=>$store_id,'item_id'=>$item_id));
        showDialog('更新商品保障服务信息任务已建立，稍后将生成','reload','succ');
    }
    /**
     * 记录服务操作日志
     */
    private function saveContractLog($item_info, $store_info, $log_msg){
        $insert_arr = array();
        $insert_arr['log_storeid'] = $store_info['store_id'];
        $insert_arr['log_storename'] = $store_info['store_name'];
        $insert_arr['log_itemid'] = $item_info['cti_id'];
        $insert_arr['log_itemname'] = $item_info['cti_name'];
        $insert_arr['log_addtime'] = time();
        $insert_arr['log_role'] = 'admin';
        $insert_arr['log_userid'] = $this->admin_info['id'];
        $insert_arr['log_username'] = $this->admin_info['name'];
        $insert_arr['log_msg'] = $log_msg;
        return Model('contract')->addContractLog($insert_arr);
    }
    /**
     * 页面内导航菜单
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function show_menu($menu_key=''){
        $menu_array = array(
            1=>array('menu_key'=>'applylist','menu_name'=>'服务加入申请', 'menu_url'=>'index.php?act=contract&op=applylist'),
            2=>array('menu_key'=>'quitlist','menu_name'=>'服务退出申请', 'menu_url'=>'index.php?act=contract&op=quitlist'),
            3=>array('menu_key'=>'contractlist','menu_name'=>'店铺保障服务', 'menu_url'=>'index.php?act=contract&op=contractlist'),
            4=>array('menu_key'=>'citemlist','menu_name'=>'保障服务管理', 'menu_url'=>'index.php?act=contract&op=citemlist')
        );
        Tpl::output('menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
