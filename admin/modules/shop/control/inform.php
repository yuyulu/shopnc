<?php
/**
 * 举报管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class informControl extends SystemControl{
    private $links = array(
            array('url'=>'act=inform&op=inform_list','text'=>'未处理'),
            array('url'=>'act=inform&op=inform_handled_list','text'=>'已处理'),
            array('url'=>'act=inform&op=inform_subject_type_list','text'=>'类型设置'),
            array('url'=>'act=inform&op=inform_subject_list','text'=>'主题设置')
    );
    public function __construct(){
        parent::__construct();
        Language::read('inform');
        if ($_GET['op'] == 'index') $_GET['op'] = 'inform_list';
        Tpl::output('top_link',$this->sublink($this->links,$_GET['op']));
    }

    /*
     * 默认操作列出未处理的举报
     */
    public function indexOp(){
        $this->inform_listOp();
    }

    /*
     * 未处理的举报列表
     */
    public function inform_listOp(){
        $_GET['type'] = 'waiting';
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('inform.list');
    }

    /*
     * 已处理的举报列表
     */
    public function inform_handled_listOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('inform.list');
    }

    public function get_xmlOp(){
        //实例化分页
        $page = new Page();
        $page->setEachNum($_POST['rp']) ;
        $page->setStyle('admin') ;
        //获得举报列表
        $model_inform = Model('inform') ;
        $condition['inform_state'] = $_GET['type'] == 'waiting' ? 1 : 2;
        if ($_POST['query'] && in_array($_POST['qtype'],array('inform_goods_name','inform_member_name','inform_store_name','inform_type','inform_subject'))) {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        if (in_array($_POST['sortname'],array('inform_datetime','inform_member_id','inform_goods_id','inform_store_id')) && in_array($_POST['sortorder'],array('asc','desc'))) {
            $condition['order'] = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $inform_list = $model_inform->getInform($condition,$page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        if ($inform_list && is_array($inform_list)) {
            $pic_base_url = UPLOAD_SITE_URL.'/shop/inform/';
            foreach ($inform_list as $k => $v){
                $list = array();
                if ($_GET['type'] == 'waiting') {
                $list['operation'] = "<a class=\"btn orange\" href=\"index.php?act=inform&op=show_handle_page&inform_id={$v['inform_id']}&inform_goods_name={$v['inform_goods_name']}\"><i class=\"fa fa-gavel\"></i>处理</a>";
                } else {
                    $list['operation'] = '--';
                }
                $list['inform_member_name'] = $v['inform_member_name'];
                $list['inform_subject_type_id'] = $v['inform_subject_type_name'];
                $list['inform_subject_id'] = $v['inform_subject_content'];
                $list['inform_goods_name'] = "<a class='open' title='{$v['inform_goods_name']}' href='". urlShop('goods', 'index', array('goods_id' => $v['inform_goods_id'])) ."' target='blank'>{$v['inform_goods_name']}</a>";
                $list['inform_pic'] = '';
                if(!empty($v['inform_pic1'])) {
                    $list['inform_pic'] .= "<a href='".$pic_base_url.$v['inform_pic1']."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$v['inform_pic1'].">\")'><i class='fa fa-picture-o'></i></a> ";
                }
                if(!empty($v['inform_pic2'])) {
                    $list['inform_pic'] .= "<a href='".$pic_base_url.$v['inform_pic2']."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$v['inform_pic2'].">\")'><i class='fa fa-picture-o'></i></a> ";
                }
                if(!empty($v['inform_pic3'])) {
                    $list['inform_pic'] .= "<a href='".$pic_base_url.$v['inform_pic3']."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$v['inform_pic3'].">\")'><i class='fa fa-picture-o'></i></a> ";
                }
                $list['inform_datetime'] = date('Y-m-d H:i:s',$v['inform_datetime']);
                if ($_GET['type'] == '') {
                    $list['inform_handle_type'] = str_replace(array(1,2,3),array('无效举报','恶意举报','有效举报'),$v['inform_handle_type']);
                    $list['inform_handle_message'] = $v['inform_handle_message'];
                }
                $list['inform_store_name'] = $v['inform_store_name'];
                $list['inform_member_id'] = $v['inform_member_id'];
                $list['inform_goods_id'] = $v['inform_goods_id'];
                $list['inform_store_id'] = $v['inform_store_id'];
                $data['list'][$v['inform_id']] = $list;
            }
        }
        exit(Tpl::flexigridXML($data));

    }

    /*
     * 举报类型列表
     */
    public function inform_subject_type_listOp() {

        //实例化分页
        $page = new Page();
        $page->setEachNum(300) ;
        $page->setStyle('admin') ;

        //获得有效举报类型列表
        $model_inform_subject_type = Model('inform_subject_type') ;
        $inform_type_list = $model_inform_subject_type->getActiveInformSubjectType($page) ;

        Tpl::output('list', $inform_type_list) ;
        Tpl::output('show_page',$page->show()) ;
		Tpl::setDirquna('shop');
        Tpl::showpage('inform_subject_type.list') ;
    }


    /*
     * 举报主题列表
     */
    public function inform_subject_listOp(){

        //实例化分页
        $page = new Page();
        $page->setEachNum(300) ;
        $page->setStyle('admin') ;

        //获得举报主题列表
        $model_inform_subject = Model('inform_subject') ;

        //搜索条件
        $condition = array();
        $condition['order'] = 'inform_subject_id asc';
        $condition['inform_subject_type_id'] = trim($_GET['inform_subject_type_id']);
        $condition['inform_subject_state'] = 1;
        $inform_subject_list = $model_inform_subject->getInformSubject($condition,$page) ;

        //获取有效举报类型
        $model_inform_subject_type = Model('inform_subject_type');
        $type_list= $model_inform_subject_type->getActiveInformSubjectType();

        Tpl::output('list', $inform_subject_list) ;
        Tpl::output('type_list', $type_list) ;
        Tpl::output('show_page',$page->show()) ;
		Tpl::setDirquna('shop');
        Tpl::showpage('inform_subject.list') ;
    }

    /*
     * 添加举报类型页面
     */
    public function inform_subject_type_addOp(){
Tpl::setDirquna('shop');
        Tpl::showpage('inform_subject_type.add') ;

    }

    /*
     * 保存添加的举报类型
     */
    public function inform_subject_type_saveOp(){

        //获取提交的内容
        $input['inform_type_name'] = trim($_POST['inform_type_name']);
        $input['inform_type_desc']  = trim($_POST['inform_type_desc']);

        //验证提交的内容
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$input['inform_type_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('inform_type_null')),
            array("input"=>$input['inform_type_desc'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"100","message"=>Language::get('inform_type_desc_null')),
        );
        $error = $obj_validate->validate();

        if ($error != ''){
            showMessage($error);
        }
        else {
            //验证成功保存
            $input['inform_type_state'] = 1;
            $model_inform_subject_type = Model('inform_subject_type');
            $model_inform_subject_type->saveInformSubjectType($input);
            $this->log(L('nc_add,inform_type').'['.$_POST['inform_type_name'].']',1);
            showMessage(Language::get('nc_common_save_succ'),'index.php?act=inform&op=inform_subject_type_list');
        }
    }

    /*
     * 删除举报类型,伪删除只是修改标记
     */
    public function inform_subject_type_dropOp(){

        $inform_type_id = $_GET['inform_type_id'];
        $inform_type_id = "'".implode("','", explode(',', $inform_type_id))."'";
        if(empty($inform_type_id)) {
            showMessage(Language::get('param_error'),'index.php?act=inform');
        }

        //删除分类
        $model_inform_subject_type = Model('inform_subject_type');
        $update_array = array();
        $update_array['inform_type_state'] = 2;
        $where_array = array();
        $where_array['in_inform_type_id'] = $inform_type_id;
        $model_inform_subject_type->updateInformSubjectType($update_array,$where_array);

        //删除分类下边的主题
        $model_inform_subject= Model('inform_subject');
        $update_subject_array = array();
        $update_subject_array['inform_subject_state'] = 2;
        $where_subject_array = array();
        $where_subject_array['in_inform_subject_type_id'] = $inform_type_id;
        $model_inform_subject->updateInformSubject($update_subject_array,$where_subject_array);
        $this->log(L('nc_del,inform_type').'[ID:'.$_POST['inform_type_id'].']',1);
        showMessage(Language::get('nc_common_del_succ'),'index.php?act=inform&op=inform_subject_type_list');

    }


    /*
     * 添加举报主题页面
     */
    public function inform_subject_addOp(){

        //获得可用举报类型列表
        $model_inform_subject_type = Model('inform_subject_type');
        $inform_type_list = $model_inform_subject_type->getActiveInformSubjectType();

        if(empty($inform_type_list)) {
            showMessage(Language::get('inform_type_error'));
        }

        Tpl::output('list', $inform_type_list) ;
		Tpl::setDirquna('shop');
        Tpl::showpage('inform_subject.add') ;

    }

    /*
     * 保存添加的举报主题
     */
    public function inform_subject_saveOp(){

        //获取提交的内容
        list($input['inform_subject_type_id'],$input['inform_subject_type_name']) = explode(',',trim($_POST['inform_subject_type']));
        $input['inform_subject_content'] = trim($_POST['inform_subject_content']);

        //验证提交的内容
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$input['inform_subject_type_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('inform_subject_null')),
            array("input"=>$input['inform_subject_content'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('inform_content_null')),
            array("input"=>$input['inform_subject_type_id'], "require"=>"true","validator"=>"Number","message"=>Language::get('param_error')),
        );
        $error = $obj_validate->validate();

        if ($error != ''){
            showMessage($error);
        }
        else {
            //验证成功保存
            $input['inform_subject_state'] = 1;
            $model_inform_subject = Model('inform_subject');
            $model_inform_subject->saveInformSubject($input);
            $this->log(L('nc_add,inform_subject').'['.$input['inform_subject_type_name'].']',1);
            showMessage(Language::get('nc_common_save_succ'),'index.php?act=inform&op=inform_subject_list');
        }
    }

    /*
     * 删除举报主题,伪删除只是修改标记
     */
    public function inform_subject_dropOp(){

        $inform_subject_id = $_GET['inform_subject_id'];
        if(empty($inform_subject_id)) {
            showMessage(Language::get('param_error'),'index.php?act=inform');
        }
        $model_inform_subject= Model('inform_subject');
        $update_array = array();
        $update_array['inform_subject_state'] = 2;
        $where_array = array();
        $where_array['in_inform_subject_id'] = "'".implode("','", explode(',', $inform_subject_id))."'";
        $model_inform_subject->updateInformSubject($update_array,$where_array);
        $this->log(L('nc_del,inform_subject').'['.$_POST['inform_subject_id'].']',1);
        showMessage(Language::get('nc_common_del_succ'),'index.php?act=inform&op=inform_subject_list');
    }

    /*
     * 显示处理举报
     */
    public function show_handle_pageOp() {
        $inform_id = intval($_GET['inform_id']);
        $inform_goods_name = urldecode($_GET['inform_goods_name']);

        if(strtoupper(CHARSET) == 'GBK') {
            $inform_goods_name = Language::getGBK($inform_goods_name);
        }

        TPL::output('inform_id',$inform_id);
        TPL::output('inform_goods_name',$inform_goods_name);
		Tpl::setDirquna('shop');
        Tpl::showpage('inform.handle');
    }

    /*
     * 处理举报
     */
    public function inform_handleOp(){

        $inform_id = intval($_POST['inform_id']);
        $inform_handle_type = intval($_POST['inform_handle_type']);
        $inform_handle_message = trim($_POST['inform_handle_message']);

        if(empty($inform_id)||empty($inform_handle_type)) {
            showMessage(Language::get('param_error'),'');
        }

        //验证输入的数据
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(

            array("input"=>$inform_handle_message, "require"=>"true","validator"=>"Length","min"=>"1","max"=>"100","message"=>Language::get('inform_handle_message_null')),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }

        $model_inform = Model('inform');
        $inform_info = $model_inform->getoneInform($inform_id);
        if(empty($inform_info)||intval($inform_info['inform_state'])===2) {
            showMessage(Language::get('param_error'));
        }

        $update_array = array();
        $where_array = array();

        //根据选择处理
        switch($inform_handle_type) {

            case 1:
                $where_array['inform_id'] = $inform_id;
                break;
            case 2:
                //恶意举报，清理所有该用户的举报，设置该用户禁止举报
                $where_array['inform_member_id'] = $inform_info['inform_member_id'];
                $this->denyMemberInform($inform_info['inform_member_id']);
                break;
            case 3:
                //有效举报，商品禁售
                $where_array['inform_id'] = $inform_id;
                $this->denyGoods($inform_info['inform_goods_id']);
                break;
            default:
                showMessage(Language::get('param_error'));

        }

        $update_array['inform_state'] = 2;
        $update_array['inform_handle_type'] = $inform_handle_type;
        $update_array['inform_handle_message'] = $inform_handle_message;
        $update_array['inform_handle_datetime'] = time();
        $admin_info = $this->getAdminInfo();
        $update_array['inform_handle_member_id'] = $admin_info['id'];
        $where_array['inform_state'] = 1;

        if($model_inform->updateInform($update_array,$where_array)) {
            $this->log(L('inform_text_handle,inform').'[ID:'.$inform_id.']',1);
            showMessage(Language::get('nc_common_op_succ'),'index.php?act=inform&op=inform_list');
        }
        else {
            showMessage(Language::get('nc_common_op_fail'));
        }
    }

    /*
     * 禁止该用户举报
     */
    private function denyMemberInform($member_id) {

        $model_member = Model('member');
        $param = array();
        $param['inform_allow'] = 2;
        return $model_member->editMember(array('member_id'=>$member_id),$param);
    }

    /*
     * 禁止商品销售
     */
    private function denyGoods($goods_id) {
        //修改商品状态
        $model_goods = Model('goods');
        $goods_info = $model_goods->getGoodsInfoByID($goods_id, 'goods_commonid');
        if (empty($goods_info)) {
            return true;
        }
        return Model('goods')->editProducesLockUp(array('goods_stateremark' => '商品被举报，平台禁售'),array('goods_commonid' => $goods_info['goods_commonid']));
    }
}
