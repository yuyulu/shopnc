<?php
/**
 * 抢购管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377 
 */



defined('In33hao') or exit('Access Invalid!');
class groupbuyControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('groupbuy');

        //如果是执行开启抢购操作，直接返回
        if ($_GET['groupbuy_open'] == 1) return true;

        //检查抢购功能是否开启
        if (C('groupbuy_allow') != 1){
            $url = array(
                array(
                    'url'=>'index.php?act=setting',
                    'msg'=>Language::get('close'),
                ),
                array(
                    'url'=>'index.php?act=groupbuy&op=groupbuy_template_list&groupbuy_open=1',
                    'msg'=>Language::get('open'),
                ),
            );
            showMessage(Language::get('admin_groupbuy_unavailable'),$url,'html','succ',1,6000);
        }
    }

    public function indexOp() {
        $this->groupbuy_listOp();
    }

    /**
     * 进行中抢购列表，只可推荐
     *
     */
    public function groupbuy_listOp()
    {
        $model_groupbuy = Model('groupbuy');
        Tpl::output('groupbuy_state_array', $model_groupbuy->getGroupbuyStateArray());

        $this->show_menu('groupbuy_list');
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('groupbuy.list');
    }

    public function groupbuy_list_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['groupbuy_name']))) {
                $condition['groupbuy_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['goods_name']))) {
                $condition['goods_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['store_name']))) {
                $condition['store_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['is_vr']))) {
                $condition['is_vr'] = (int) $q;
            }
            if (($q = (int) $_REQUEST['state']) > 0) {
                $condition['state'] = $q;
            }

            $pdates = array();
            if (strlen($q = trim((string) $_REQUEST['pdate1'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "end_time >= {$q}";
            }
            if (strlen($q = trim((string) $_REQUEST['pdate2'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "start_time <= {$q}";
            }
            if ($pdates) {
                $condition['pdates'] = array(
                    'exp',
                    implode(' or ', $pdates),
                );
            }

        } else {
            switch ($_REQUEST['qtype']) {
                case 'groupbuy_name':
                    $condition['groupbuy_name'] = array('like', '%'.$_REQUEST['query'].'%');
                    break;
                case 'goods_name':
                    $condition['goods_name'] = array('like', '%'.$_REQUEST['query'].'%');
                    break;
                case 'store_name':
                    $condition['store_name'] = array('like', '%'.$_REQUEST['query'].'%');
                    break;
            }
        }

        switch ($_REQUEST['sortname']) {
            case 'views':
            case 'buy_quantity':
                $sort = $_REQUEST['sortname'];
                break;
            case 'start_time_text':
                $sort = 'start_time';
                break;
            case 'end_time_text':
                $sort = 'end_time';
                break;
            default:
                $sort = 'groupbuy_id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }

        $model_groupbuy = Model('groupbuy');
        $groupbuy_list = (array) $model_groupbuy->getGroupbuyExtendList($condition, $_REQUEST['rp'], $sort);

        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        $data = array();
        $data['now_page'] = $model_groupbuy->shownowpage();
        $data['total_num'] = $model_groupbuy->gettotalnum();

        foreach ($groupbuy_list as $val) {
            $u = SHOP_SITE_URL . "/index.php?act=show_groupbuy&op=groupbuy_detail&group_id=" . $val['groupbuy_id'];
            $o = '<a class="btn red confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('groupbuy', 'groupbuy_del', array(
                'groupbuy_id' => $val['groupbuy_id'],
            )) . '"><i class="fa fa-trash-o"></i>删除</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            $o .= '<li><a class="confirm-on-click" href="' . $u . '" target="_blank">查看活动</a></li>';

            if ($val['reviewable']) {
                $o .= '<li><a class="confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('groupbuy', 'groupbuy_review_pass', array(
                    'groupbuy_id' => $val['groupbuy_id'],
                )) . '">批准活动</a></li>';
                $o .= '<li><a class="confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('groupbuy', 'groupbuy_review_fail', array(
                    'groupbuy_id' => $val['groupbuy_id'],
                )) . '">拒绝活动</a></li>';
            }

            if ($val['recommended'] == '1') {
                $o .= '<li><a href="javascript:;" data-href="' . urlAdminShop('groupbuy', 'groupbuy_rec', array(
                    'groupbuy_id' => $val['groupbuy_id'],
                    'rec' => 0,
                )) . '">取消推荐</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-href="' . urlAdminShop('groupbuy', 'groupbuy_rec', array(
                    'groupbuy_id' => $val['groupbuy_id'],
                    'rec' => 1,
                )) . '">推荐活动</a></li>';
            }

            if ($val['cancelable']) {
                $o .= '<li><a class="confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('groupbuy', 'groupbuy_cancel', array(
                    'groupbuy_id' => $val['groupbuy_id'],
                )) . '">取消活动</a></li>';
            }

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;

            $i['is_vr'] = $val['is_vr'] == 1 ? '虚拟' : '实物';

            $i['groupbuy_name'] = $val['groupbuy_name'];

            $i['goods_name'] = '<a target="_blank" href="'
                . SHOP_SITE_URL . "/index.php?act=goods&goods_id=" . $val['goods_id']
                . '">' . $val['goods_name'] . '</a>';

            $i['store_name'] = '<a target="_blank" href="'
                . urlShop('show_store', 'index', array('store_id'=>$val['store_id']))
                . '">' . $val['store_name'] . '</a>';

            if (isset($flippedOwnShopIds[$val['store_id']])) {
                $i['store_name'] .= '<span class="ownshop">[自营]</span>';
            }

            $gi = gthumb($val['groupbuy_image'], 'small');
            $i['groupbuy_image'] = <<<EOB
<a href="javascript:;" class="pic-thumb-tip" onMouseOut="toolTip()" onMouseOver="toolTip('<img src=\'{$gi}\'>')">
<i class='fa fa-picture-o'></i></a>
EOB;

            $i['start_time_text'] = $val['start_time_text'];
            $i['end_time_text'] = $val['end_time_text'];
            $i['views'] = $val['views'];
            $i['buy_quantity'] = $val['buy_quantity'];

            $i['recommended'] = $val['recommended'] == '1'
                ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>'
                : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $i['groupbuy_state_text'] = $val['groupbuy_state_text'];

            $data['list'][$val['groupbuy_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 审核通过
     */
    public function groupbuy_review_passOp(){
        $groupbuy_id = intval($_REQUEST['groupbuy_id']);

        $model_groupbuy = Model('groupbuy');
        $result = $model_groupbuy->reviewPassGroupbuy($groupbuy_id);
        if($result) {
            $this->log('通过抢购活动申请，抢购编号'.$groupbuy_id,null);
            // 添加队列
            $groupbuy_info = $model_groupbuy->getGroupbuyInfo(array('groupbuy_id' => $groupbuy_id));
            Model('cron')->addCron(array('exetime' => $groupbuy_info['start_time'], 'exeid' => $groupbuy_info['goods_commonid'], 'type' => 5));
            Model('cron')->addCron(array('exetime' => $groupbuy_info['end_time'], 'exeid' => $groupbuy_info['goods_commonid'], 'type' => 6));

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 审核失败
     */
    public function groupbuy_review_failOp(){
        $groupbuy_id = intval($_REQUEST['groupbuy_id']);

        $model_groupbuy = Model('groupbuy');
        $result = $model_groupbuy->reviewFailGroupbuy($groupbuy_id);
        if($result) {
            $this->log('拒绝抢购活动申请，抢购编号'.$groupbuy_id,null);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 取消
     */
    public function groupbuy_cancelOp() {
        $groupbuy_id = intval($_REQUEST['groupbuy_id']);

        $model_groupbuy = Model('groupbuy');
        $result = $model_groupbuy->cancelGroupbuy($groupbuy_id);
        if($result) {
            $this->log('取消抢购活动，抢购编号'.$groupbuy_id,null);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 删除
     */
    public function groupbuy_delOp(){
        $groupbuy_id = intval($_REQUEST['groupbuy_id']);

        $model_groupbuy = Model('groupbuy');
        $result = $model_groupbuy->delGroupbuy(array('groupbuy_id' => $groupbuy_id));
        if($result) {
            $this->log('删除抢购活动，抢购编号'.$groupbuy_id,null);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 推荐
     */
    public function groupbuy_recOp()
    {
        $model= Model('groupbuy');
        $update_array['recommended'] = $_GET['rec'] == '1' ? 1 : 0;
        $where_array['groupbuy_id'] = $_GET['groupbuy_id'];
        $result = $model->editGroupbuy($update_array, $where_array);

        if ($result) {
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    public function class_sort_updateOp()
    {
        $update_array = array();
        $where_array = array();

        $model= Model('groupbuy_class');
        $update_array['sort'] = $_GET['value'];
        $where_array['class_id'] = $_GET['id'];
        $result = $model->updates($update_array, $where_array);

        // 删除抢购分类缓存
        Model('groupbuy')->dropCachedData('groupbuy_classes');

        $this->jsonOutput();
    }

    public function class_name_updateOp()
    {
        $update_array = array();
        $where_array = array();

        $model= Model('groupbuy_class');
        $update_array['class_name'] = $_GET['value'];
        $where_array['class_id'] = $_GET['id'];
        $result = $model->updates($update_array, $where_array);

        // 删除抢购分类缓存
        Model('groupbuy')->dropCachedData('groupbuy_classes');
        $this->log(L('groupbuy_class_edit_success').'[ID:'.$_GET['id'].']', null);

        $this->jsonOutput();
    }

    /**
     * ajax修改抢购信息
     */
    public function ajaxOp(){

        $result = true;
        $update_array = array();
        $where_array = array();

        switch ($_GET['branch']){
         case 'recommended':
            $model= Model('groupbuy');
            $update_array['recommended'] = $_GET['value'];
            $where_array['groupbuy_id'] = $_GET['id'];
            $result = $model->editGroupbuy($update_array, $where_array);
            break;
        }
        if($result) {
            echo 'true';exit;
        }
        else {
            echo 'false';exit;
        }

    }

    /**
     * 套餐管理
     */
    public function groupbuy_quotaOp()
    {
        $this->show_menu('groupbuy_quota');
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('groupbuy_quota.list');
    }

    /**
     * 套餐管理
     */
    public function groupbuy_quota_xmlOp()
    {
        $condition = array();

        switch ($_REQUEST['qtype']) {
            case 'store_name':
                $condition['store_name'] = array('like', '%'.$_REQUEST['query'].'%');
                break;
        }

        $model_groupbuy_quota = Model('groupbuy_quota');
        $list = (array) $model_groupbuy_quota->getGroupbuyQuotaList($condition, $_REQUEST['rp'], 'end_time desc');

        $data = array();
        $data['now_page'] = $model_groupbuy_quota->shownowpage();
        $data['total_num'] = $model_groupbuy_quota->gettotalnum();

        foreach ($list as $val) {
            $i = array();
            $i['operation'] = '<span>--</span>';

            $i['store_name'] = '<a target="_blank" href="'
                . urlShop('show_store', 'index', array('store_id'=>$val['store_id']))
                . '">' . $val['store_name'] . '</a>';

            $i['start_time_text'] = date("Y-m-d", $val['start_time']);
            $i['end_time_text'] = date("Y-m-d", $val['end_time']);

            $data['list'][$val['quota_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 抢购类别列表
     */
    public function class_listOp() {

        $model_groupbuy_class = Model('groupbuy_class');
        $param = array();
        $param['order'] = 'sort asc';
        $groupbuy_class_list = $model_groupbuy_class->getTreeList($param);

        $this->show_menu('class_list');
        Tpl::output('list',$groupbuy_class_list);
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('groupbuy_class.list');
    }

    /**
     * 添加抢购分类页面
     */
    public function class_addOp() {

        $model_groupbuy_class = Model('groupbuy_class');
        $param = array();
        $param['order'] = 'sort asc';
        $param['class_parent_id'] = 0;
        $groupbuy_class_list = $model_groupbuy_class->getList($param);
        Tpl::output('list',$groupbuy_class_list);

        $this->show_menu('class_add');
        Tpl::output('parent_id',$_GET['parent_id']);
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('groupbuy_class.add');

    }

    /**
     * 保存添加的抢购类别
     */
    public function class_saveOp() {

        $class_id = intval($_POST['class_id']);
        $param = array();
        $param['class_name'] = trim($_POST['input_class_name']);
        if(empty($param['class_name'])) {
            showMessage(Language::get('class_name_error'),'');
        }
        $param['sort'] = intval($_POST['input_sort']);
        $param['class_parent_id'] = intval($_POST['input_parent_id']);

        $model_groupbuy_class = Model('groupbuy_class');

        // 删除抢购分类缓存
        Model('groupbuy')->dropCachedData('groupbuy_classes');

        if(empty($class_id)) {
            //新增
            if ($class_id = $model_groupbuy_class->save($param)) {
                $this->log(L('groupbuy_class_add_success').'[ID:'.$class_id.']', null);
                showMessage(Language::get('groupbuy_class_add_success'),'index.php?act=groupbuy&op=class_list');
            }
            else {
                showMessage(Language::get('groupbuy_class_add_fail'),'index.php?act=groupbuy&op=class_list');
            }
        }
        else {
            //编辑
            if($model_groupbuy_class->updates($param,array('class_id'=>$class_id))) {
                $this->log(L('groupbuy_class_edit_success').'[ID:'.$class_id.']', null);
                showMessage(Language::get('groupbuy_class_edit_success'),'index.php?act=groupbuy&op=class_list');
            }
            else {
                showMessage(Language::get('groupbuy_class_edit_fail'),'index.php?act=groupbuy&op=class_list');
            }
        }

    }

    /**
     * 删除抢购类别
     */
    public function class_dropOp() {

        $class_id = trim($_POST['class_id']);
        if(empty($class_id)) {
            showMessage(Language::get('param_error'),'');
        }

        $model_groupbuy_class = Model('groupbuy_class');
        //获得所有下级类别编号
        $all_class_id = $model_groupbuy_class->getAllClassId(explode(',',$class_id));
        $param = array();
        $param['in_class_id'] = implode(',',$all_class_id);
        if($model_groupbuy_class->drop($param)) {
            // 删除抢购分类缓存
            Model('groupbuy')->dropCachedData('groupbuy_classes');

            $this->log(L('groupbuy_class_drop_success').'[ID:'.$param['in_class_id'].']',null);
            showMessage(Language::get('groupbuy_class_drop_success'),'');
        }
        else {
            showMessage(Language::get('groupbuy_class_drop_fail'),'');
        }

    }

    /**
     * 抢购价格区间列表
     */
    public function price_listOp()
    {
        $this->show_menu('price_list');
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('groupbuy_price.list');
    }

    /**
     * 抢购价格区间列表
     */
    public function price_list_xmlOp()
    {
        $model= Model('groupbuy_price_range');
        $groupbuy_price_list = (array) $model->getList();

        $data = array();

        $data['now_page'] = 1;
        $data['total_num'] = count($groupbuy_price_list);

        foreach ($groupbuy_price_list as $val) {
            $o = '<a class="confirm-del-on-click btn red" href="index.php?act=groupbuy&op=price_drop&range_id='
                . $val['range_id']
                . '"><i class="fa fa-trash-o"></i>删除</a>';
            $o .= '<a class="btn blue" href="index.php?act=groupbuy&op=price_edit&range_id='
                . $val['range_id']
                . '"><i class="fa fa-pencil-square-o"></i>编辑</a>';

            $i = array();
            $i['operation'] = $o;

            $i['range_name'] = $val['range_name'];
            $i['range_start'] = $val['range_start'];
            $i['range_end'] = $val['range_end'];

            $data['list'][$val['range_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 添加抢购价格区间页面
     */
    public function price_addOp() {

        $this->show_menu('price_add');
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('groupbuy_price.add');

    }

    /**
     * 编辑抢购价格区间页面
     */
    public function price_editOp() {

        $range_id = intval($_GET['range_id']);
        if(empty($range_id)) {
            showMessage(Language::get('param_error'),'');
        }

        $model = Model('groupbuy_price_range');

        $price_info = $model->getOne($range_id);
        if(empty($price_info)) {
            showMessage(Language::get('param_error'),'');
        }
        Tpl::output('price_info',$price_info);

        $this->show_menu('price_edit');
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('groupbuy_price.add');

    }

    /**
     * 保存添加的抢购价格区间
     */
    public function price_saveOp() {

        $range_id = intval($_POST['range_id']);
        $param = array();
        $param['range_name'] = trim($_POST['range_name']);
        if(empty($param['range_name'])) {
            showMessage(Language::get('range_name_error'),'');
        }
        $param['range_start'] = intval($_POST['range_start']);
        $param['range_end'] = intval($_POST['range_end']);

        $model = Model('groupbuy_price_range');

        if(empty($range_id)) {
            //新增
            if($model->save($param)) {
                dkcache('groupbuy_price');
                $this->log(L('groupbuy_price_range_add_success').'['.$_POST['range_name'].']',null);
                showMessage(Language::get('groupbuy_price_range_add_success'),'index.php?act=groupbuy&op=price_list');
            }
            else {
                showMessage(Language::get('groupbuy_price_range_add_fail'),'index.php?act=groupbuy&op=price_list');
            }
        }
        else {
            //编辑
            if($model->updates($param,array('range_id'=>$range_id))) {
                dkcache('groupbuy_price');
                $this->log(L('groupbuy_price_range_edit_success').'['.$_POST['range_name'].']',null);
                showMessage(Language::get('groupbuy_price_range_edit_success'),'index.php?act=groupbuy&op=price_list');
            }
            else {
                showMessage(Language::get('groupbuy_price_range_edit_fail'),'index.php?act=groupbuy&op=price_list');
            }
        }

    }

    /**
     * 删除抢购价格区间
     */
    public function price_dropOp() {

        $range_id = trim($_REQUEST['range_id']);
        if(empty($range_id)) {
            showMessage(Language::get('param_error'),'');
        }

        $model = Model('groupbuy_price_range');
        $param = array();
        $param['in_range_id'] = "'".implode("','", explode(',', $range_id))."'";
        if($model->drop($param)) {
            dkcache('groupbuy_price');
            $this->log(L('groupbuy_price_range_drop_success').'[ID:'.$range_id.']',null);
            showMessage(Language::get('groupbuy_price_range_drop_success'),'');
        }
        else {
            showMessage(Language::get('groupbuy_price_range_drop_fail'),'');
        }
    }

    /**
     * 设置
     **/
    public function groupbuy_settingOp() {

        $model_setting = Model('setting');
        $setting = $model_setting->GetListSetting();
        Tpl::output('setting',$setting);

        $this->show_menu('groupbuy_setting');
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('groupbuy.setting');
    }

    public function groupbuy_setting_saveOp() {
        $groupbuy_price = intval($_POST['groupbuy_price']);
        if($groupbuy_price < 0) {
            $groupbuy_price = 0;
        }

        $groupbuy_review_day = intval($_POST['groupbuy_review_day']);
        if($groupbuy_review_day< 0) {
            $groupbuy_review_day = 0;
        }

        $model_setting = Model('setting');
        $update_array = array();
        $update_array['groupbuy_price'] = $groupbuy_price;
        $update_array['groupbuy_review_day'] = $groupbuy_review_day;
        $result = $model_setting->updateSetting($update_array);
        if ($result){
            $this->log('修改抢购套餐价格为'.$groupbuy_price.'元');
            showMessage(Language::get('nc_common_op_succ'),'');
        }else {
            showMessage(Language::get('nc_common_op_fail'),'');
        }
    }

    /**
     * 幻灯片设置
     */
    public function sliderOp()
    {
        $model_setting = Model('setting');
        if (chksubmit()) {
            $update = array();
            if (!empty($_FILES['live_pic1']['name'])) {
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_LIVE);
                $result = $upload->upfile('live_pic1');
                if ($result) {
                    $update['live_pic1'] = $upload->file_name;
                }else {
                    showMessage($upload->error, '', '', 'error');
                }
            }
            if (!empty($_POST['live_link1'])) {
                $update['live_link1'] = $_POST['live_link1'];
            }
			if (!empty($_POST['live_color1'])) {
                $update['live_color1'] = $_POST['live_color1'];
            }

            if (!empty($_FILES['live_pic2']['name'])) {
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_LIVE);
                $result = $upload->upfile('live_pic2');
                if ($result) {
                    $update['live_pic2'] = $upload->file_name;
                } else {
                    showMessage($upload->error, '', '', 'error');
                }
            }

            if (!empty($_POST['live_link2'])) {
                $update['live_link2'] = $_POST['live_link2'];
            }
						if (!empty($_POST['live_color2'])) {
                $update['live_color2'] = $_POST['live_color2'];
            }

            if (!empty($_FILES['live_pic3']['name'])) {
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_LIVE);
                $result = $upload->upfile('live_pic3');
                if ($result) {
                    $update['live_pic3'] = $upload->file_name;
                } else {
                    showMessage($upload->error, '', '', 'error');
                }
            }

            if (!empty($_POST['live_link3'])) {
                $update['live_link3'] = $_POST['live_link3'];
            }
			if (!empty($_POST['live_color3'])) {
                $update['live_color3'] = $_POST['live_color3'];
            }
            if (!empty($_FILES['live_pic4']['name'])) {
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_LIVE);
                $result = $upload->upfile('live_pic4');
                if ($result) {
                    $update['live_pic4'] = $upload->file_name;
                } else {
                    showMessage($upload->error, '', '', 'error');
                }
            }

            if (!empty($_POST['live_link4'])) {
                $update['live_link4'] = $_POST['live_link4'];
            }
						if (!empty($_POST['live_color4'])) {
                $update['live_color4'] = $_POST['live_color4'];
            }

            $list_setting = $model_setting->getListSetting();
            $result = $model_setting->updateSetting($update);
            if ($result) {
                if($list_setting['live_pic1'] != '' && isset($update['live_pic1'])){
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_LIVE.DS.$list_setting['live_pic1']);
                }

                if($list_setting['live_pic2'] != '' && isset($update['live_pic2'])){
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_LIVE.DS.$list_setting['live_pic2']);
                }

                if($list_setting['live_pic3'] != '' && isset($update['live_pic3'])){
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_LIVE.DS.$list_setting['live_pic3']);
                }

                if($list_setting['live_pic4'] != '' && isset($update['live_pic4'])){
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_LIVE.DS.$list_setting['live_pic4']);
                }

                dkcache('setting');
                $this->log('修改抢购幻灯片设置', 1);
                showMessage('编辑成功', '', '', 'succ');
            } else {
                showMessage('编辑失败', '', '', 'error');
            }
        }

        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting', $list_setting);

        $this->show_menu('slider');
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('groupbuy.slider');
    }

    /**
     * 幻灯片清空
     */
    public function slider_clearOp()
    {
        $model_setting = Model('setting');
        $update = array();
        $update['live_pic1'] = '';
        $update['live_link1'] = '';
        $update['live_pic2'] = '';
        $update['live_link2'] = '';
        $update['live_pic3'] = '';
        $update['live_link3'] = '';
        $update['live_pic4'] = '';
        $update['live_link4'] = '';
        $res = $model_setting->updateSetting($update);
        if ($res) {
            dkcache('setting');
            $this->log('清空抢购幻灯片设置', 1);
            echo json_encode(array('result'=>'true'));
        } else {
            echo json_encode(array('result'=>'false'));
        }
        exit;
    }

    /**
     * 页面内导航菜单
     *
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function show_menu($menu_key) {
        $menu_array = array(
            'groupbuy_list'=>array('menu_type'=>'link','menu_name'=>'抢购活动','menu_url'=>'index.php?act=groupbuy&op=groupbuy_list'),
            'groupbuy_quota'=>array('menu_type'=>'link','menu_name'=>'套餐管理','menu_url'=>'index.php?act=groupbuy&op=groupbuy_quota'),
            'class_list'=>array('menu_type'=>'link','menu_name'=>Language::get('groupbuy_class_list'),'menu_url'=>'index.php?act=groupbuy&op=class_list'),
            'class_add'=>array('menu_type'=>'link','menu_name'=>Language::get('groupbuy_class_add'),'menu_url'=>'index.php?act=groupbuy&op=class_add'),
            'price_list'=>array('menu_type'=>'link','menu_name'=>Language::get('groupbuy_price_list'),'menu_url'=>'index.php?act=groupbuy&op=price_list'),
            'price_add'=>array('menu_type'=>'link','menu_name'=>Language::get('groupbuy_price_add'),'menu_url'=>'index.php?act=groupbuy&op=price_add'),
            'price_edit'=>array('menu_type'=>'link','menu_name'=>Language::get('groupbuy_price_edit'),'menu_url'=>'index.php?act=groupbuy&op=price_edit'),
            'groupbuy_setting'=>array('menu_type'=>'link','menu_name'=>'设置','menu_url'=>urlAdminShop('groupbuy', 'groupbuy_setting')),
            'slider'=>array('menu_type'=>'link','menu_name'=>'幻灯片管理','menu_url'=>urlAdminShop('groupbuy', 'slider')),
        );
        switch ($menu_key) {
            case 'class_add':
                unset($menu_array['price_add']);
                unset($menu_array['price_edit']);
                break;
            case 'price_add':
                unset($menu_array['class_add']);
                unset($menu_array['price_edit']);
                break;
            case 'price_edit':
                unset($menu_array['class_add']);
                unset($menu_array['price_add']);
                break;
            default:
                unset($menu_array['class_add']);
                unset($menu_array['price_add']);
                unset($menu_array['price_edit']);
                break;
        }
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }
}
