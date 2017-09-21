<?php
/**
 * 广告管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class advControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('adv');
    }

    public function indexOp() {
        $this->ap_manageOp();
    }

    /**
     *
     * 管理员添加广告
     */
    public function adv_addOp(){
        if(!chksubmit()){
            $adv  = Model('adv');
            /**
             * 取广告位信息
             */
            $ap_list = $adv->getApList();
            Tpl::output('ap_list',$ap_list);
			Tpl::setDirquna('system');
            Tpl::showpage('adv.add');
        }else{
            $lang = Language::getLangContent();
            $adv  = Model('adv');
            $upload     = new UploadFile();
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $validate_arr = array();
            $validate_arr[] = array("input"=>$_POST["adv_name"], "require"=>"true", "message"=>$lang['adv_can_not_null']);
            $validate_arr[] = array("input"=>$_POST["aptype_hidden"], "require"=>"true", "message"=>$lang['must_select_ap']);
            $validate_arr[] = array("input"=>$_POST["ap_id"], "require"=>"true", "message"=>$lang['must_select_ap']);
            $validate_arr[] = array("input"=>$_POST["adv_start_time"], "require"=>"true", "message"=>$lang['must_select_start_time']);
            $validate_arr[] = array("input"=>$_POST["adv_end_time"], "require"=>"true", "message"=>$lang['must_select_end_time']);
            if ($_POST["aptype_hidden"] == '1'){
                //文字广告
                $validate_arr[] = array("input"=>$_POST["adv_word"], "require"=>"true", "message"=>$lang['textadv_null_error']);
            }elseif ($_POST["aptype_hidden"] == '3'){
                //flash广告
                $validate_arr[] = array("input"=>$_FILES['flash_swf']['name'], "require"=>"true", "message"=>$lang['flashadv_null_error']);
            }else {
                //图片广告
                $validate_arr[] = array("input"=>$_FILES['adv_pic']['name'], "require"=>"true", "message"=>$lang['picadv_null_error']);
            }
            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $insert_array['adv_title']       = trim($_POST['adv_name']);
                $insert_array['ap_id']           = intval($_POST['ap_id']);
                $insert_array['adv_start_date']  = $this->getunixtime($_POST['adv_start_time']);
                $insert_array['adv_end_date']    = $this->getunixtime($_POST['adv_end_time']);
                $insert_array['is_allow']        = '1';
                /**
                 * 建立文字广告信息的入库数组
                 */
                //判断页面编码确定汉字所占字节数
                switch (CHARSET){
                   case 'UTF-8':
                      $charrate = 3;
                      break;
                   case 'GBK':
                      $charrate = 2;
                      break;
                }
                //图片广告
                if($_POST['aptype_hidden'] == '0'){
                    $upload->set('default_dir',ATTACH_ADV);
                    $result = $upload->upfile('adv_pic');
                    if (!$result){
                        showMessage($upload->error,'','','error');
                    }
                    $ac = array(
                    'adv_pic'     =>$upload->file_name,
                    'adv_pic_url' =>trim($_POST['adv_pic_url'])
                    );
                    $ac = serialize($ac);
                    $insert_array['adv_content'] = $ac;
                }
                //文字广告
                if($_POST['aptype_hidden'] == '1'){
                    if(strlen($_POST['adv_word'])>($_POST['adv_word_len']*$charrate)){
                        $error = $lang['wordadv_toolong'];
                        showMessage($error);die;
                    }
                    $ac = array(
                    'adv_word'    =>trim($_POST['adv_word']),
                    'adv_word_url'=>trim($_POST['adv_word_url'])
                    );
                    $ac = serialize($ac);
                    $insert_array['adv_content'] = $ac;
                }
                //建立Flash广告信息的入库数组
                if($_POST['aptype_hidden'] == '3'){
                    $upload->set('default_dir',ATTACH_ADV);
                    $upload->upfile('flash_swf');
                    $ac = array(
                    'flash_swf'  =>$upload->file_name,
                    'flash_url'  =>trim($_POST['flash_url'])
                    );
                    $ac = serialize($ac);
                    $insert_array['adv_content'] = $ac;
                }
                //广告信息入库
                $result = $adv->adv_add($insert_array);
                //更新相应广告位所拥有的广告数量
                $condition['ap_id'] = intval($_POST['ap_id']);
                $ap_list = $adv->getApList($condition);
                $ap_list = $ap_list['0'];
                $adv_num = $ap_list['adv_num'];
                $param['ap_id']   = intval($_POST['ap_id']);
                $param['adv_num'] = $adv_num+1;
                $result2 = $adv->ap_update($param);

                if ($result&&$result2){
                    $this->log(L('adv_add_succ').'['.$_POST["adv_name"].']',null);
                    showMessage($lang['adv_add_succ'],'index.php?act=adv&op=adv&ap_id='.$_POST['ap_id']);
                }else {
                    showMessage($lang['adv_add_fail'],'index.php?act=adv&op=adv&ap_id='.$_POST['ap_id']);
                }
        }
      }
    }

    /**
     *
     * 管理广告位
     */
    public function ap_manageOp(){
		Tpl::setDirquna('system');
        Tpl::showpage('ap_manage');
    }

    public function get_ap_xmlOp(){
        $lang = Language::getLangContent();
        $adv  = Model('adv');
        $condition  = array();
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('ap_name'))) {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $sort_fields = array('ap_class','ap_display','is_use');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $page = new Page();
        $page->setEachNum($_POST['rp']);
        $page->setStyle('admin');
        $ap_list  = $adv->getApList($condition,$page,$order);
        $adv_list = $adv->getList();
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        foreach ((array)$ap_list as $k => $ap_info) {
            $list = array();$operation_detail = '';
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$ap_info['ap_id']})\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation_detail = "<li><a href=\"index.php?act=adv&op=adv&ap_id={$ap_info['ap_id']}\"></i>管理广告</a></li>";
            $operation_detail .= "<li><a href='index.php?act=adv&op=ap_edit&ap_id={$ap_info['ap_id']}'>编辑属性</a></li>";
            $operation_detail .= "<li><a onclick=\"copyToClipBoard('{$ap_info['ap_id']}');\" href=\"javascript:void(0)\">代码调用</a></li>";
            if ($operation_detail) {
                $list['operation'] .= "<span class='btn'><em><i class='fa fa-cog'></i>设置 <i class='arrow'></i></em><ul>{$operation_detail}</ul>";
            }
            $list['ap_name'] = $ap_info['ap_name'];
            $list['ap_class'] = str_replace(array(0,1,3), array('图片','文字','Flash'),$ap_info['ap_class']);
            $list['ap_display'] = str_replace(array(0,1,2), array($lang['ap_slide_show'],$lang['ap_mul_adv'],$lang['ap_one_adv']),$ap_info['ap_display']);
            $list['ap_width'] = $ap_info['ap_width'];
            $list['ap_height'] = $ap_info['ap_class'] == '1' ? '' : $ap_info['ap_height'];

            $ap_now_count = 0;$ap_count = 0;
            $time = time();
            if(!empty($adv_list)){
                foreach ($adv_list as $adv_k => $adv_v){
                    if($adv_v['ap_id'] == $ap_info['ap_id'] && $adv_v['adv_end_date'] > $time && $adv_v['adv_start_date'] < $time && $adv_v['is_allow'] == '1'){
                        $ap_now_count++;
                    }
                    if($adv_v['ap_id'] == $ap_info['ap_id']){
                        $ap_count++;
                    }
                }}
            $list['ap_count'] = $ap_count;
            $list['ap_now_count'] = $ap_now_count;
            $list['is_use'] = $ap_info['is_use'] ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-check-circle"></i>否</span>';
            $data['list'][$ap_info['ap_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * js代码调用
     */
    public function ap_copyOp(){
		Tpl::setDirquna('system');
        Tpl::showpage('ap_copy', 'null_layout');
    }

    public function deleteOp() {
         if (preg_match('/^[\d,]+$/', $_GET['del_id'])) {
            $adv = Model('adv');
            $where  = "where adv_id in (".$_GET['del_id'].")";
            Model('adv')-> ap_del("adv",$where);
            foreach (explode(',',$_GET['del_id']) as $v) {
                if (!empty($v)) {
                    $result  = $adv->ap_del(intval($v));
                }
            }
            $this->log('删除广告位成功'.'[ID:'.$_GET['del_id'].']',null);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    public function adv_deleteOp() {
        if (preg_match('/^[\d,]+$/', $_GET['del_id'])) {
            $adv = Model('adv');
            $where  = "where adv_id in (".$_GET['del_id'].")";
            Model('adv')-> adv_del("adv",$where);
			
            foreach (explode(',',$_GET['del_id']) as $v) {
                if (!empty($v)) {
                    $result  = $adv->adv_del(intval($v));
                }
            }
            $this->log('删除广告成功'.'[ID:'.$_GET['del_id'].']',null);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     *
     * 修改广告位
     */
    public function ap_editOp(){
        if (empty($_GET['ap_id'])) {
            showMessage('参数错误');
        }
        if(!chksubmit()){
             $adv  = Model('adv');
             $condition['ap_id'] = intval($_GET['ap_id']);
             $ap_list = $adv->getApList($condition);
             Tpl::output('ref_url',getReferer());
             Tpl::output('ap_list',$ap_list);
			 Tpl::setDirquna('system');
             Tpl::showpage('ap_edit');
        }else{
            $lang = Language::getLangContent();
            $adv  = Model('adv');
            $upload     = new UploadFile();

            $obj_validate = new Validate();
            if($_POST['ap_class'] == '1'){
                $obj_validate->validateparam = array(
                array("input"=>$_POST["ap_name"], "require"=>"true", "message"=>$lang['ap_can_not_null']),
                array("input"=>$_POST["ap_width"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_width_must_num']),
                );
            }else{
                $obj_validate->validateparam = array(
                array("input"=>$_POST["ap_name"], "require"=>"true", "message"=>$lang['ap_can_not_null']),
                array("input"=>$_POST["ap_width"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_width_must_num']),
                array("input"=>$_POST["ap_height"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_height_must_num']),
                );
            }

            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $param['ap_id']      = intval($_GET['ap_id']);
                $param['ap_name']    = trim($_POST["ap_name"]);
                $param['ap_width']   = intval(trim($_POST["ap_width"]));
                $param['ap_height']  = intval(trim($_POST["ap_height"]));
                if($_POST["ap_display"] != ''){
                    $param['ap_display'] = intval($_POST["ap_display"]);
                }
                if($_POST["is_use"] != ''){
                    $param['is_use']     = intval($_POST["is_use"]);
                }
                if($_FILES['default_pic']['name'] != ''){
                    $upload->set('default_dir',ATTACH_ADV);
                    $result = $upload->upfile('default_pic');
                    if (!$result){
                        showMessage($upload->error,'','','error');
                    }
                    $param['default_content'] = $upload->file_name;
                }
                if($_POST['default_word'] != ''){
                    $param['default_content'] = trim($_POST['default_word']);
                }
                $result = $adv->ap_update($param);

                if ($result){
                    $this->log(L('ap_change_succ').'['.$_POST["ap_name"].']',null);
                    showMessage($lang['ap_change_succ'],$_POST['ref_url']);
                }else {
                    showMessage($lang['ap_change_fail'] ,$url);
                }
            }
        }
    }

    /**
     *
     * 新增广告位
     */
    public function ap_addOp(){
        if($_POST['form_submit'] != 'ok'){
			Tpl::setDirquna('system');
            Tpl::showpage('ap_add');
        }else{
            $lang = Language::getLangContent();
            $adv  = Model('adv');
            $upload     = new UploadFile();

            $obj_validate = new Validate();
            if($_POST['ap_class'] == '1'){
                $obj_validate->validateparam = array(
                array("input"=>$_POST["ap_name"], "require"=>"true", "message"=>$lang['ap_can_not_null']),
                array("input"=>$_POST["ap_width_word"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_wordwidth_must_num']),
                array("input"=>$_POST["default_word"], "require"=>"true", "message"=>$lang['default_word_can_not_null']),
            );
            }else{
                $obj_validate->validateparam = array(
                array("input"=>$_POST["ap_name"], "require"=>"true", "message"=>$lang['ap_can_not_null']),
                array("input"=>$_POST["ap_width_media"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_width_must_num']),
                array("input"=>$_POST["ap_height"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['ap_height_must_num']),
                array("input"=>$_FILES["default_pic"], "require"=>"true", "message"=>$lang['default_pic_can_not_null']),
            );
            }
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $insert_array['ap_name']    = trim($_POST['ap_name']);
                $insert_array['ap_class']   = intval($_POST['ap_class']);
                $insert_array['ap_display'] = intval($_POST['ap_display']);
                $insert_array['is_use']     = intval($_POST['is_use']);
                if($_POST['ap_width_media'] != ''){
                    $insert_array['ap_width']  = intval(trim($_POST['ap_width_media']));
                }
                if($_POST['ap_width_word'] != ''){
                    $insert_array['ap_width']  = intval(trim($_POST['ap_width_word']));
                }
                if($_POST['default_word'] != ''){
                    $insert_array['default_content'] = trim($_POST['default_word']);
                }
                if($_FILES['default_pic']['name'] != ''){
                    $upload->set('default_dir',ATTACH_ADV);
                    $result = $upload->upfile('default_pic');
                    if (!$result){
                        showMessage($upload->error,'','','error');
                    }
                    $insert_array['default_content'] = $upload->file_name;
                }
                $insert_array['ap_height'] = intval(trim($_POST['ap_height']));
                $result  = $adv->ap_add($insert_array);

                if ($result){
                    $this->log(L('ap_add_succ').'['.$_POST["ap_name"].']',null);
                    showMessage($lang['ap_add_succ'],'index.php?act=adv&op=ap_manage','html','succ',1,4000);
                }else {
                    showMessage($lang['ap_add_fail']);
                }
            }
        }
    }

    /**
     *
     * 广告管理
     */
    public function advOp(){
        Tpl::output('ap_name',Model()->table('adv_position')->getfby_ap_id(intval($_GET['ap_id']),'ap_name'));
		Tpl::setDirquna('system');
        Tpl::showpage('adv.index');
    }

    public function get_adv_xmlOp(){
        $lang = Language::getLangContent();
        $adv  = Model('adv');
        $condition  = array();
        $condition['ap_id'] = intval($_GET['ap_id']);
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('adv_title'))) {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $sort_fields = array('ap_id','adv_start_date','adv_end_date');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $condition['is_allow'] = '1';
        $page = new Page();
        $page->setEachNum($_POST['rp']);
        $page->setStyle('admin');
        $adv_list  = $adv->getList($condition,$page,'',$order);
        $ap_list = $adv->getApList();
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        foreach ((array)$adv_list as $k => $adv_info) {
            $list = array();$operation_detail = '';
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$adv_info['adv_id']})\"><i class='fa fa-trash-o'></i>删除</a><a class='btn blue' href='index.php?act=adv&op=adv_edit&adv_id={$adv_info['adv_id']}'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $list['adv_title'] = $adv_info['adv_title'];
            $list['ap_id'] = $list['ap_class'];
            $list['ap_class'] = '';
            foreach ($ap_list as $ap_k => $ap_v){
                if($adv_info['ap_id'] == $ap_v['ap_id']){
                    $list['ap_id'] = $ap_v['ap_name'];
                    $list['ap_class'] = str_replace(array(0,1,3), array('图片','文字','Flash'),$ap_v['ap_class']);
                    break;
                }
            }
            $list['adv_start_date'] = date('Y-m-d',$adv_info['adv_start_date']);
            $list['adv_end_date'] = date('Y-m-d',$adv_info['adv_end_date']);
            $data['list'][$adv_info['adv_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     *
     * 修改广告
     */
    public function adv_editOp(){
        if (empty($_GET['adv_id'])) {
            showMessage('参数错误');
        }
        if($_POST['form_submit'] != 'ok'){
             $adv  = Model('adv');
             $condition['adv_id'] = intval($_GET['adv_id']);
             $adv_list = $adv->getList($condition);
             $ap_info  = $adv->getApList();
             Tpl::output('ref_url',getReferer());
             Tpl::output('adv_list',$adv_list);
             Tpl::output('ap_info',$ap_info);
			 Tpl::setDirquna('system');
             Tpl::showpage('adv.edit');
        }else{
            $lang = Language::getLangContent();
            $adv  = Model('adv');
            $upload     = new UploadFile();
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["adv_name"], "require"=>"true", "message"=>$lang['ap_can_not_null']),
                array("input"=>$_POST["adv_start_date"], "require"=>"true","message"=>$lang['must_select_start_time']),
                array("input"=>$_POST["adv_end_date"], "require"=>"true", "message"=>$lang['must_select_end_time'])
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $param['adv_id']         = intval($_GET['adv_id']);
                $param['adv_title']      = trim($_POST['adv_name']);
                $param['adv_start_date'] = $this->getunixtime(trim($_POST['adv_start_date']));
                $param['adv_end_date']   = $this->getunixtime(trim($_POST['adv_end_date']));
                /**
                  * 建立图片广告信息的入库数组
                  */
                if($_POST['mark'] == '0'){
                if($_FILES['adv_pic']['name'] != ''){
                    $upload->set('default_dir',ATTACH_ADV);
                    $result = $upload->upfile('adv_pic');
                    if (!$result){
                        showMessage($upload->error,'','','error');
                    }
                    $ac = array(
                    'adv_pic'     =>$upload->file_name,
                    'adv_pic_url' =>trim($_POST['adv_pic_url'])
                    );
                    $ac = serialize($ac);
                    $param['adv_content'] = $ac;
                }else{
                    $ac = array(
                    'adv_pic'     =>trim($_POST['pic_ori']),
                    'adv_pic_url' =>trim($_POST['adv_pic_url'])
                    );
                    $ac = serialize($ac);
                    $param['adv_content'] = $ac;
                }
                }
               /**
                 * 建立文字广告信息的入库数组
                 */
                if($_POST['mark'] == '1'){
                //判断页面编码确定汉字所占字节数
                switch (CHARSET){
                   case 'UTF-8':
                        $charrate = 3;
                        break;
                   case 'GBK':
                        $charrate = 2;
                        break;
                }
                if(strlen($_POST['adv_word'])>($_POST['adv_word_len']*$charrate)){
                        $error = $lang['wordadv_toolong'];
                        showMessage($error);die;
                }
                    $ac = array(
                    'adv_word'    =>trim($_POST['adv_word']),
                    'adv_word_url'=>trim($_POST['adv_word_url'])
                    );
                    $ac = serialize($ac);
                    $param['adv_content'] = $ac;
                }
                /**
                 * 建立Flash广告信息的入库数组
                 */
                if($_POST['mark'] == '3'){
                if($_FILES['flash_swf']['name'] != ''){
                    $upload->set('default_dir',ATTACH_ADV);
                    $result = $upload->upfile('flash_swf');
                    $ac = array(
                    'flash_swf'  =>$upload->file_name,
                    'flash_url'  =>trim($_POST['flash_url'])
                    );
                    $ac = serialize($ac);
                    $param['adv_content'] = $ac;
                 }else{
                    $ac = array(
                    'flash_swf'  =>trim($_POST['flash_ori']),
                    'flash_url'  =>trim($_POST['flash_url'])
                    );
                    $ac = serialize($ac);
                    $param['adv_content'] = $ac;
                 }
                }
                $result = $adv->updates($param);

                if ($result){
                    $url = array(
                        array(
                            'url'=>trim($_POST['ref_url']),
                            'msg'=>$lang['goback_ap_manage'],
                        )
                    );
                    $this->log(L('adv_change_succ').'['.$_POST["adv_name"].']',null);
                    showMessage($lang['adv_change_succ'],$url);
                }else {
                    showMessage($lang['adv_change_fail'],$url);
                }
            }
        }
    }

    /**
     *
     * 获取UNIX时间戳
     */
    public function getunixtime($time){
        $array     = explode("-", $time);
        $unix_time = mktime(0,0,0,$array[1],$array[2],$array[0]);
        return $unix_time;
    }

    /**
     *
     * ajaxOp
     */
    public function ajaxOp(){
        switch ($_GET['branch']){
            case 'is_use':
            $adv=Model('adv');
            $param[trim($_GET['column'])]=intval($_GET['value']);
            $param['ap_id']=intval($_GET['id']);
            $adv->ap_update($param);

            echo 'true';exit;
            break;
        }
    }
}
