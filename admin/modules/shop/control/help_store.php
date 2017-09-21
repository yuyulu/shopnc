<?php
/**
 * 店铺帮助管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class help_storeControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->help_storeOp();
    }

    /**
     * 帮助列表
     */
    public function help_storeOp() {
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('help_store.list');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_help = Model('help');
        $condition = array();
        $condition['help_id'] = array('gt','99');//内容列表不显示系统自动添加的数据
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('help_id','help_sort','help_title','update_time','type_id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $help_list = $model_help->getStoreHelpList($condition, $page, $order);

        $type_list = $model_help->getStoreHelpTypeList();
        $type_array = array();
        if (!empty($type_list)) {
            foreach ($type_list as $v) {
                $type_array[$v['type_id']] = $v['type_name'];
            }
        }

        $data = array();
        $data['now_page'] = $model_help->shownowpage();
        $data['total_num'] = $model_help->gettotalnum();
        foreach ($help_list as $value) {
            $param = array();
            $param['operation'] = "<a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del('".$value['help_id']."')\"><i class='fa fa-trash-o'></i>删除</a><a class='btn blue' href='index.php?act=help_store&op=edit_help&help_id=".$value['help_id']."' class='url'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $param['help_id'] = $value['help_id'];
            $param['help_sort'] = $value['help_sort'];
            $param['help_title'] = $value['help_title'];
            $param['type_id'] = $type_array[$value['type_id']];
            $param['update_time'] = date('Y-m-d H:i:s', $value['update_time']);
            $data['list'][$value['help_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 帮助类型
     */
    public function help_typeOp() {
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('help_store_type.list');
    }

    /**
     * 输出XML数据
     */
    public function get_type_xmlOp() {
        $model_help = Model('help');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('help_id','help_sort','help_title','update_time','type_id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $type_list = $model_help->getStoreHelpTypeList($condition,$page, 0, $order);


        $data = array();
        $data['now_page'] = $model_help->shownowpage();
        $data['total_num'] = $model_help->gettotalnum();
        foreach ($type_list as $value) {
            $param = array();
            $operation = '';
            if ($value['help_code'] == 'auto') {
                $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del('". $value['type_id'] ."')\"><i class='fa fa-trash-o'></i>删除</a>";
            }
            $operation .= "<a class='btn blue' href='index.php?act=help_store&op=edit_type&type_id=".$value['type_id']."' class='url'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $param['operation'] = $operation;
            $param['type_id'] = $value['type_id'];
            $param['type_name'] = $value['type_name'];
            $param['type_sort'] = $value['type_sort'];
            $param['help_show'] = $value['help_show'] == 1 ? '显示' : '隐藏';
            $data['list'][$value['type_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 新增帮助
     *
     */
    public function add_helpOp() {
        $model_help = Model('help');
        if (chksubmit()) {
            $help_array = array();
            $help_array['help_title'] = $_POST['help_title'];
            $help_array['help_url'] = $_POST['help_url'];
            $help_array['help_info'] = $_POST['content'];
            $help_array['help_sort'] = intval($_POST['help_sort']);
            $help_array['type_id'] = intval($_POST['type_id']);
            $help_array['update_time'] = time();
            $help_array['page_show'] = '1';//页面类型:1为店铺,2为会员
            $state = $model_help->addHelp($help_array);
            if ($state) {
                if (!empty($_POST['file_id']) && is_array($_POST['file_id'])){
                    $model_help->editHelpPic($state, $_POST['file_id']);
                }
                $this->log('新增店铺帮助，编号'.$state);
                showMessage(Language::get('nc_common_save_succ'),'index.php?act=help_store&op=help_store');
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $type_list = $model_help->getStoreHelpTypeList();
        Tpl::output('type_list',$type_list);
        $condition = array();
        $condition['item_id'] = '0';
        $pic_list = $model_help->getHelpPicList($condition);
        Tpl::output('pic_list',$pic_list);
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('help_store.add');
    }

    /**
     * 编辑帮助
     *
     */
    public function edit_helpOp() {
        $model_help = Model('help');
        $condition = array();
        $help_id = intval($_GET['help_id']);
        $condition['help_id'] = $help_id;
        $help_list = $model_help->getStoreHelpList($condition);
        $help = $help_list[0];
        Tpl::output('help',$help);
        if (chksubmit()) {
            $help_array = array();
            $help_array['help_title'] = $_POST['help_title'];
            $help_array['help_url'] = $_POST['help_url'];
            $help_array['help_info'] = $_POST['content'];
            $help_array['help_sort'] = intval($_POST['help_sort']);
            $help_array['type_id'] = intval($_POST['type_id']);
            $help_array['update_time'] = time();
            $state = $model_help->editHelp($condition, $help_array);
            if ($state) {
                $this->log('编辑店铺帮助，编号'.$help_id);
                showMessage(Language::get('nc_common_save_succ'),'index.php?act=help_store&op=help_store');
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $type_list = $model_help->getStoreHelpTypeList();
        Tpl::output('type_list',$type_list);
        $condition = array();
        $condition['item_id'] = $help_id;
        $pic_list = $model_help->getHelpPicList($condition);
        Tpl::output('pic_list',$pic_list);
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('help_store.edit');
    }

    /**
     * 删除帮助
     *
     */
    public function del_helpOp() {
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_help = Model('help');
            $condition = array();
            $condition['help_id'] = $id;
            $state = $model_help->delHelp($condition,array($id));
            $this->log('删除店铺帮助，ID'.$id);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 新增帮助类型
     *
     */
    public function add_typeOp() {
        $model_help = Model('help');
        if (chksubmit()) {
            $type_array = array();
            $type_array['type_name'] = $_POST['type_name'];
            $type_array['type_sort'] = intval($_POST['type_sort']);
            $type_array['help_show'] = intval($_POST['help_show']);//是否显示,0为否,1为是
            $type_array['page_show'] = '1';//页面类型:1为店铺,2为会员

            $state = $model_help->addHelpType($type_array);
            if ($state) {
                $this->log('新增店铺帮助类型，编号'.$state);
                showMessage(Language::get('nc_common_save_succ'),'index.php?act=help_store&op=help_type');
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('help_store_type.add');
    }

    /**
     * 编辑帮助类型
     *
     */
    public function edit_typeOp() {
        $model_help = Model('help');
        $condition = array();
        $condition['type_id'] = intval($_GET['type_id']);
        $type_list = $model_help->getHelpTypeList($condition);
        $type = $type_list[0];
        if (chksubmit()) {
            $type_array = array();
            $type_array['type_name'] = $_POST['type_name'];
            $type_array['type_sort'] = intval($_POST['type_sort']);
            $type_array['help_show'] = intval($_POST['help_show']);//是否显示,0为否,1为是
            $state = $model_help->editHelpType($condition, $type_array);
            if ($state) {
                $this->log('编辑店铺帮助类型，编号'.$condition['type_id']);
                showMessage(Language::get('nc_common_save_succ'),'index.php?act=help_store&op=help_type');
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        Tpl::output('type',$type);
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('help_store_type.edit');
    }

    /**
     * 删除帮助类型
     *
     */
    public function del_typeOp() {
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_help = Model('help');
            $condition['help_code'] = 'auto';
            $condition['type_id'] = $id;
            $result = $model_help->delHelpType($condition);
            $this->log('删除店铺帮助类型，ID'.$id);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 上传图片
     */
    public function upload_picOp() {
        $data = array();
        if (!empty($_FILES['fileupload']['name'])) {//上传图片
            $fprefix = 'help_store';
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_ARTICLE);
            $upload->set('fprefix',$fprefix);
            $upload->upfile('fileupload');
            $model_upload = Model('upload');
            $file_name = $upload->file_name;
            $insert_array = array();
            $insert_array['file_name'] = $file_name;
            $insert_array['file_size'] = $_FILES['fileupload']['size'];
            $insert_array['upload_time'] = time();
            $insert_array['item_id'] = intval($_GET['item_id']);
            $insert_array['upload_type'] = '2';
            $result = $model_upload->add($insert_array);
            if ($result) {
                $data['file_id'] = $result;
                $data['file_name'] = $file_name;
            }
        }
        echo json_encode($data);exit;
    }

    /**
     * 删除图片
     */
    public function del_picOp() {
        $condition = array();
        $condition['upload_id'] = intval($_GET['file_id']);
        $model_help = Model('help');
        $state = $model_help->delHelpPic($condition);
        if ($state) {
            echo 'true';exit;
        } else {
            echo 'false';exit;
        }
    }
}
