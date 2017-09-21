<?php
/**
 * 活动管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class activityControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('activity');
    }
    /**
     * 活动列表
     */
    public function indexOp(){
        $this->activityOp();
    }
    /**
     * 活动列表/删除活动
     */
    public function activityOp()
    {
		Tpl::setDirquna('shop');
        Tpl::showpage('activity.index');
    }

    /**
     * 活动列表/删除活动XML
     */
    public function activity_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['activity_title']))) {
                $condition['activity_title'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['activity_state']))) {
                $condition['activity_state'] = (int) $q;
            }

            $pdates = array();
            if (strlen($q = trim((string) $_REQUEST['pdate1'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "activity.activity_end_date >= {$q}";
            }
            if (strlen($q = trim((string) $_REQUEST['pdate2'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "activity.activity_start_date <= {$q}";
            }
            if ($pdates) {
                $condition['pdates'] = implode(' or ', $pdates);
            }

        } else {
            if (strlen($q = trim($_REQUEST['query'])) > 0) {
                switch ($_REQUEST['qtype']) {
                    case 'activity_title':
                        $condition['activity_title'] = $q;
                        break;
                }
            }
        }

        switch ($_REQUEST['sortname']) {
            case 'activity_sort':
            case 'activity_start_date':
            case 'activity_end_date':
                $sort = $_REQUEST['sortname'];
                break;
            default:
                $sort = 'activity_id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }

        // 只显示商品活动
        $condition['activity_type'] = '1';
        $condition['order'] = $sort;

        $page = new Page();
        $page->setEachNum($_REQUEST['rp']);

        $activity = Model('activity');
        $list = (array) $activity->getList($condition, $page);

        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');

        foreach ($list as $val) {
            $o = '';
            if ($val['activity_state'] == 0 || $val['activity_end_date'] < time()) {
                $o .= '<a class="btn red confirm-del-on-click" href="javascript:;" data-href="index.php?act=activity&op=del&activity_id=' .
                    $val['activity_id'] .
                    '"><i class="fa fa-trash-o"></i>删除</a>';
            }

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            $o .= '<li><a href="index.php?act=activity&op=edit&activity_id=' .
                    $val['activity_id'] .
                    '">编辑活动</a></li>';

            $o .= '<li><a href="index.php?act=activity&op=detail&id=' .
                $val['activity_id'] .
                '">处理申请</a></li>';

            $o .= '</ul></span>';


            $i = array();
            $i['operation'] = $o;

            $i['activity_sort'] = '<span class="editable" title="可编辑" style="width:50px;" data-live-inline-edit="activity_sort">' .
                $val['activity_sort'] . '</span>';

            $i['activity_title'] = '<span class="editable" title="可编辑" style="width:333px;" data-live-inline-edit="activity_title">' .
                $val['activity_title'] . '</span>';

            $img = UPLOAD_SITE_URL."/".ATTACH_ACTIVITY."/".$val['activity_banner'];
            $i['activity_banner'] = <<<EOB
<a href="javascript:;" class="pic-thumb-tip" onMouseOut="toolTip()" onMouseOver="toolTip('<img src=\'{$img}\'>')">
<i class='fa fa-picture-o'></i></a>
EOB;

            $i['activity_start_date'] = date('Y-m-d', $val['activity_start_date']);
            $i['activity_end_date'] = date('Y-m-d', $val['activity_end_date']);

            $i['activity_state'] = $val['activity_state'] == 1
                ? '<span class="yes"><i class="fa fa-check-circle"></i>开启</span>'
                : '<span class="no"><i class="fa fa-ban"></i>关闭</span>';

            $data['list'][$val['activity_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 新建活动/保存新建活动
     */
    public function newOp(){
        //新建处理
        if($_POST['form_submit'] != 'ok'){
		Tpl::setDirquna('shop');
            Tpl::showpage('activity.add');
            exit;
        }
        //提交表单
        $obj_validate = new Validate();
        $validate_arr[] = array("input"=>$_POST["activity_title"],"require"=>"true","message"=>Language::get('activity_new_title_null'));
        $validate_arr[] = array("input"=>$_POST["activity_start_date"],"require"=>"true","message"=>Language::get('activity_new_startdate_null'));
        $validate_arr[] = array("input"=>$_POST["activity_end_date"],"require"=>"true",'validator'=>'Compare','operator'=>'>','to'=>"{$_POST['activity_start_date']}","message"=>Language::get('activity_new_enddate_null'));
        $validate_arr[] = array("input"=>$_POST["activity_style"],"require"=>"true","message"=>Language::get('activity_new_style_null'));
        $validate_arr[] = array('input'=>$_POST['activity_type'],'require'=>'true','message'=>Language::get('activity_new_type_null'));
        $validate_arr[] = array('input'=>$_FILES['activity_banner']['name'],'require'=>'true','message'=>Language::get('activity_new_banner_null'));
        $validate_arr[] = array('input'=>$_POST['activity_sort'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('activity_new_sort_error'));
        $obj_validate->validateparam = $validate_arr;
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage(Language::get('error').$error,'','','error');
        }
        $upload = new UploadFile();
        $upload->set('default_dir',ATTACH_ACTIVITY);
        $result = $upload->upfile('activity_banner');
        if(!$result){
            showMessage($upload->error);
        }
        //保存
        $input  = array();
        $input['activity_title']    = trim($_POST['activity_title']);
        //$input['activity_type']       = trim($_POST['activity_type']);
        $input['activity_type']     = '1';
        $input['activity_banner']   = $upload->file_name;
        $input['activity_style']    = trim($_POST['activity_style']);
        $input['activity_desc']     = trim($_POST['activity_desc']);
        $input['activity_sort']     = intval(trim($_POST['activity_sort']));
        $input['activity_start_date']= strtotime(trim($_POST['activity_start_date']));
        $input['activity_end_date'] = strtotime(trim($_POST['activity_end_date']));
        $input['activity_state']    = intval($_POST['activity_state']);
        $activity   = Model('activity');
        $result = $activity->add($input);
        if($result){
            $this->log(L('nc_add,activity_index').'['.$_POST['activity_title'].']',null);
            showMessage(Language::get('nc_common_op_succ'),'index.php?act=activity&op=activity');
        }else{
            //添加失败则删除刚刚上传的图片,节省空间资源
            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY.DS.$upload->file_name);
            showMessage(Language::get('nc_common_op_fail'));
        }
    }

    /**
     * 异步修改
     */
    public function ajaxOp(){
        if(in_array($_GET['branch'],array('activity_title','activity_sort'))){
            $activity = Model('activity');
            $update_array = array();
            switch ($_GET['branch']){
                /**
                 * 活动主题
                 */
                case 'activity_title':
                    if(trim($_GET['value'])=='')exit;
                    break;
                /**
                 * 排序
                 */
                case 'activity_sort':
                    if(preg_match('/^\d+$/',trim($_GET['value']))<=0 or intval(trim($_GET['value']))<0 or intval(trim($_GET['value']))>255)exit;
                    break;
                default:
                        exit;
            }
            $update_array[$_GET['column']] = trim($_GET['value']);
            if($activity->updates($update_array,intval($_GET['id'])))
            echo 'true';
        }elseif(in_array($_GET['branch'],array('activity_detail_sort'))){
            $activity_detail = Model('activity_detail');
            $update_array = array();
            switch ($_GET['branch']){
                /**
                 * 排序
                 */
                case 'activity_detail_sort':
                    if(preg_match('/^\d+$/',trim($_GET['value']))<=0 or intval(trim($_GET['value']))<0 or intval(trim($_GET['value']))>255)exit;
                    break;
                default:
                        exit;
            }
            $update_array[$_GET['column']] = trim($_GET['value']);
            if($activity_detail->updates($update_array,intval($_GET['id'])))
            echo 'true';
        }
    }

    /**
     * 删除活动
     */
    public function delOp()
    {
        $activityIds = array();
        foreach (explode(',', (string) $_REQUEST['activity_id']) as $i) {
            $activityIds[(int) $i] = null;
        }
        unset($activityIds[0]);
        $activityIds = array_keys($activityIds);

        if (empty($activityIds)) {
            $this->jsonOutput(Language::get('activity_del_choose_activity'));
        }

        try{
            // 删除数据先删除横幅图片，节省空间资源
            foreach ($activityIds as $v) {
                $this->delBanner($v);
            }
        } catch (Exception $e) {
            $this->jsonOutput($e->getMessage());
        }

        $id = implode(",", $activityIds);

        $activity   = Model('activity');
        $activity_detail    = Model('activity_detail');
        //获取可以删除的数据
        $condition_arr = array();
        $condition_arr['activity_state'] = '0';//已关闭
        $condition_arr['activity_enddate_greater_or'] = time();//过期
        $condition_arr['activity_id_in'] = $id;
        $activity_list = $activity->getList($condition_arr);
        if (empty($activity_list)){//没有符合条件的活动信息直接返回成功信息
            $this->jsonOutput();
        }
        $id_arr = array();
        foreach ($activity_list as $v){
            $id_arr[] = $v['activity_id'];
        }
        $id_new = "'".implode("','",$id_arr)."'";
        //只有关闭或者过期的活动，能删除
        if($activity_detail->del($id_new)){
            if($activity->del($id_new)){
                $this->log(L('nc_del,activity_index').'[ID:'.$id.']',null);
                $this->jsonOutput();
            }
        }

        $this->jsonOutput('操作失败');
    }

    /**
     * 编辑活动/保存编辑活动
     */
    public function editOp(){
        if($_POST['form_submit'] != 'ok'){
            if(empty($_GET['activity_id'])){
                showMessage(Language::get('miss_argument'));
            }
            $activity   = Model('activity');
            $row    = $activity->getOneById(intval($_GET['activity_id']));
            Tpl::output('activity',$row);

		Tpl::setDirquna('shop');
            Tpl::showpage('activity.edit');
            exit;
        }
        //提交表单
        $obj_validate = new Validate();
        $validate_arr[] = array("input"=>$_POST["activity_title"],"require"=>"true","message"=>Language::get('activity_new_title_null'));
        $validate_arr[] = array("input"=>$_POST["activity_start_date"],"require"=>"true","message"=>Language::get('activity_new_startdate_null'));
        $validate_arr[] = array("input"=>$_POST["activity_end_date"],"require"=>"true",'validator'=>'Compare','operator'=>'>','to'=>"{$_POST['activity_start_date']}","message"=>Language::get('activity_new_enddate_null'));
        $validate_arr[] = array("input"=>$_POST["activity_style"],"require"=>"true","message"=>Language::get('activity_new_style_null'));
        $validate_arr[] = array('input'=>$_POST['activity_type'],'require'=>'true','message'=>Language::get('activity_new_type_null'));
        $validate_arr[] = array('input'=>$_POST['activity_desc'],'require'=>'true','message'=>Language::get('activity_new_desc_null'));
        $validate_arr[] = array('input'=>$_POST['activity_sort'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('activity_new_sort_error'));
        $obj_validate->validateparam = $validate_arr;
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage(Language::get('error').$error,'','','error');
        }
        //构造更新内容
        $input  = array();
        if($_FILES['activity_banner']['name']!=''){
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_ACTIVITY);
            $result = $upload->upfile('activity_banner');
            if(!$result){
                showMessage($upload->error);
            }
            $input['activity_banner']   = $upload->file_name;
        }
        $input['activity_title']    = trim($_POST['activity_title']);
        $input['activity_type']     = trim($_POST['activity_type']);
        $input['activity_style']    = trim($_POST['activity_style']);
        $input['activity_desc']     = trim($_POST['activity_desc']);
        $input['activity_sort']     = intval(trim($_POST['activity_sort']));
        $input['activity_start_date']   = strtotime(trim($_POST['activity_start_date']));
        $input['activity_end_date'] = strtotime(trim($_POST['activity_end_date']));
        $input['activity_state']    = intval($_POST['activity_state']);

        $activity   = Model('activity');
        $row    = $activity->getOneById(intval($_POST['activity_id']));
        $result = $activity->updates($input,intval($_POST['activity_id']));
        if($result){
            if($_FILES['activity_banner']['name']!=''){
                @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY.DS.$row['activity_banner']);
            }
            $this->log(L('nc_edit,activity_index').'[ID:'.$_POST['activity_id'].']',null);
            showMessage(Language::get('nc_common_save_succ'),'index.php?act=activity&op=activity');
        }else{
            if($_FILES['activity_banner']['name']!=''){
                @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY.DS.$upload->file_name);
            }
            showMessage(Language::get('nc_common_save_fail'));
        }
    }

    /**
     * 活动细节列表
     */
    public function detailOp()
    {
        $states = array(
            L('activity_detail_index_to_audit'),
            L('activity_detail_index_passed'),
            L('activity_detail_index_unpassed'),
        );
        Tpl::output('states', $states);

        $activity_detail = Model('activity')->getOneById($_REQUEST['id']);
        Tpl::output('activity_detail', $activity_detail);
		Tpl::setDirquna('shop');
        Tpl::showpage('activity_detail.index');
    }

    /**
     * 活动细节列表XML
     */
    public function detail_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['store_name']))) {
                $condition['store_name'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['item_name']))) {
                $condition['item_name'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['activity_detail_state']))) {
                $condition['activity_detail_state'] = (int) $q;
            }
        } else {
            if (strlen($q = trim($_REQUEST['query'])) > 0) {
                switch ($_REQUEST['qtype']) {
                    case 'store_name':
                        $condition['store_name'] = $q;
                        break;
                    case 'item_name':
                        $condition['item_name'] = $q;
                        break;
                }
            }
        }

        switch ($_REQUEST['sortname']) {
            case 'activity_detail_sort':
            case 'activity_detail_state':
                $sort = 'activity_detail.' . $_REQUEST['sortname'];
                break;
            default:
                $sort = 'activity_detail.activity_detail_id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }

        $condition['activity_id'] = (int) $_GET['id'];
        $condition['order'] = $sort;

        $page= new Page();
        $page->setEachNum($_REQUEST['rp']);

        $activitydetail_model = Model('activity_detail');
        $list = (array) $activitydetail_model->getList($condition, $page);

        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');

        $states = array(
            L('activity_detail_index_to_audit'),
            L('activity_detail_index_passed'),
            L('activity_detail_index_unpassed'),
        );

        foreach ($list as $val) {
            $o = '<a class="btn green" href="' .
                urlShop('goods', 'index', array('goods_id' => $val['item_id'])) .
                '"><i class="fa fa-list-alt"></i>查看</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            if ($val['activity_detail_state'] != 1) {
                $o .= '<li><a class="confirm-on-click" href="javascript:;" data-href="index.php?act=activity&op=deal&state=1&activity_detail_id=' .
                    $val['activity_detail_id'] .
                    '">通过</a></li>';
            }

            if ($val['activity_detail_state'] != 2) {
                $o .= '<li><a class="confirm-on-click" href="javascript:;" data-href="index.php?act=activity&op=deal&state=2&activity_detail_id=' .
                    $val['activity_detail_id'] .
                    '">拒绝</a></li>';
            }

            if ($val['activity_detail_state'] != 1) {
                $o .= '<li><a class="confirm-on-click" href="javascript:;" data-href="' .
                    'index.php?act=activity&op=del_detail&activity_detail_id=' .
                    $val['activity_detail_id'] .
                    '">删除</a></li>';
            }

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;

            $i['activity_detail_sort'] = '<span class="editable" title="可编辑" style="width:50px;" data-live-inline-edit="activity_detail_sort">' .
                $val['activity_detail_sort'] . '</span>';

            $i['item_name'] = $val['item_name'];

            $i['store_name'] = '<a target="_blank" href="' .
                urlShop('show_store', 'index', array('store_id' => $val['store_id'])) .
                '">' .
                $val['store_name'] .
                '</a>';

            $i['activity_detail_state_text'] = $states[(int) $val['activity_detail_state']];

            $data['list'][$val['activity_detail_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 活动内容处理
     */
    public function dealOp()
    {
        $ids = array();
        foreach (explode(',', (string) $_REQUEST['activity_detail_id']) as $i) {
            $ids[(int) $i] = null;
        }
        unset($ids[0]);
        $ids = array_keys($ids);

        if (empty($ids)) {
            showMessage(Language::get('activity_detail_del_choose_detail'));
        }

        // 获取id
        $id = implode(',', $ids);

        //创建活动内容对象
        $activity_detail    = Model('activity_detail');
        if($activity_detail->updates(array('activity_detail_state'=>intval($_GET['state'])),$id)){
            $this->log(L('nc_edit,activity_index').'[ID:'.$id.']',null);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 删除活动内容
     */
    public function del_detailOp()
    {
        $ids = array();
        foreach (explode(',', (string) $_REQUEST['activity_detail_id']) as $i) {
            $ids[(int) $i] = null;
        }
        unset($ids[0]);
        $ids = array_keys($ids);

        if (empty($ids)) {
            showMessage(Language::get('activity_detail_del_choose_detail'));
        }

        // 获取id
        $id = implode(',', $ids);

        $activity_detail    = Model('activity_detail');
        //条件
        $condition_arr = array();
        $condition_arr['activity_detail_id_in'] = $id;
        $condition_arr['activity_detail_state_in'] = "'0','2'";//未审核和已拒绝
        if($activity_detail->delList($condition_arr)){
            $this->log(L('nc_del,activity_index_content').'[ID:'.$id.']',null);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 根据活动编号删除横幅图片
     *
     * @param int $id
     */
    private function delBanner($id){
        $activity   = Model('activity');
        $row    = $activity->getOneById($id);
        //删除图片文件
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY.DS.$row['activity_banner']);
    }
}
