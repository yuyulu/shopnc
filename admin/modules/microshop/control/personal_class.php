<?php
/**
 * 微商城
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class personal_classControl extends SystemControl{

    const MICROSHOP_CLASS_LIST = 'index.php?act=goods_class&op=goodsclass_list';
    const GOODS_FLAG = 1;
    const PERSONAL_FLAG = 2;
    const ALBUM_FLAG = 3;
    const STORE_FLAG = 4;

    public function __construct(){
        parent::__construct();
        Language::read('store');
        Language::read('microshop');
    }

    public function indexOp() {
       $this->personalclass_listOp();
    }

    /**
     * 微商城商品(随心看)分类管理
     **/
    public function personalclass_listOp() {
        $model_class = Model("micro_personal_class");
        $list = $model_class->getList(TRUE);
        Tpl::output('list',$list);
        $this->show_menu_personal_class("personal_class_list");
        Tpl::setDirquna('microshop');
Tpl::showpage("microshop_personal_class.list");
    }

    /**
     * 微商城个人秀分类添加
     **/
    public function personalclass_addOp() {
        $this->show_menu_personal_class('personal_class_add');
        Tpl::setDirquna('microshop');
Tpl::showpage('microshop_personal_class.add');
    }

    /**
     * 微商城商品(随心看)分类编辑
     **/
    public function personalclass_editOp() {
        $class_id = intval($_GET['class_id']);
        if(empty($class_id)) {
            showMessage(Language::get('param_error'),'','','error');
        }
        $model_class = Model("micro_personal_class");
        $condition = array();
        $condition['class_id'] = $class_id;
        $class_info = $model_class->getOne($condition);
        Tpl::output('class_info',$class_info);

        $this->show_menu_personal_class("personal_class_edit");
        Tpl::setDirquna('microshop');
Tpl::showpage("microshop_personal_class.add");
    }

    /**
     * 微商城个人秀分类保存
     **/
    public function personalclass_saveOp() {
        $obj_validate = new Validate();
        $validate_array = array(
            array('input'=>$_POST['class_name'],'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"10",'message'=>Language::get('class_name_error')),
            array('input'=>$_POST['class_sort'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('class_sort_error')),
        );
        $obj_validate->validateparam = $validate_array;
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage(Language::get('error').$error,'','','error');
        }

        $param = array();
        $param['class_name'] = trim($_POST['class_name']);
        $param['class_sort'] = intval($_POST['class_sort']);
        if(!empty($_FILES['class_image']['name'])) {
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_MICROSHOP);
            $result = $upload->upfile('class_image');
            if(!$result) {
                showMessage($upload->error);
            }
            $param['class_image'] = $upload->file_name;
            //删除老图片
            if(!empty($_POST['old_class_image'])) {
                $old_image = BASE_UPLOAD_PATH.DS.ATTACH_MICROSHOP.DS.$_POST['old_class_image'];
                if(is_file($old_image)) {
                    unlink($old_image);
                }
            }
        }

        $model_class = Model("micro_personal_class");
        if(isset($_POST['class_id']) && intval($_POST['class_id']) > 0) {
            $result = $model_class->modify($param,array('class_id'=>$_POST['class_id']));
        } else {
            $result = $model_class->save($param);
        }
        if($result) {
            showMessage(Language::get('class_add_success'),"index.php?act=personal_class&op=personalclass_list");
        } else {
            showMessage(Language::get('class_add_fail'),"index.php?act=personal_class&op=personalclass_list",'','error');
        }

    }

    /*
     * ajax修改分类排序
     */
    public function personalclass_sort_updateOp() {
        if(intval($_GET['id']) <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_sort = intval($_GET['value']);
        if ($new_sort > 255){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_sort_error')));
            die;
        } else {
            $model_class = Model("micro_personal_class");
            $result = $model_class->modify(array('class_sort'=>$new_sort),array('class_id'=>$_GET['id']));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>'class_add_success'));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_add_fail')));
                die;
            }
        }
    }

    /*
     * ajax修改分类名称
     */
    public function personalclass_name_updateOp() {
        $class_id = intval($_GET['id']);
        if($class_id <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }

        $new_name = trim($_GET['value']);
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array('input'=>$new_name,'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"10",'message'=>Language::get('class_name_error')),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_name_error')));
            die;
        } else {
            $model_class = Model("micro_personal_class");
            $result = $model_class->modify(array('class_name'=>$new_name),array('class_id'=>$class_id));
            if($result) {
                echo json_encode(array('result'=>TRUE,'message'=>'class_add_success'));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>Language::get('class_add_fail')));
                die;
            }
        }
    }

    /**
     * 个人秀分类删除
     **/
     public function personalclass_dropOp() {

        $class_id = trim($_REQUEST['class_id']);
        $model_class = Model('micro_personal_class');
        $condition = array();
        $condition['class_id'] = array('in',$class_id);
        //删除分类图片
        $list = $model_class->getList($condition);
        if(!empty($list)) {
            foreach ($list as $value) {
                //删除老图片
                if(!empty($value['class_image'])) {
                    $old_image = BASE_UPLOAD_PATH.DS.ATTACH_MICROSHOP.DS.$value['class_image'];
                    if(is_file($old_image)) {
                        unlink($old_image);
                    }
                }
            }
        }

        $result = $model_class->drop($condition);
        if($result) {
            showMessage(Language::get('class_drop_success'),'');
        } else {
            showMessage(Language::get('class_drop_fail'),'','','error');
        }

     }

     private function show_menu_personal_class($menu_key) {
         $menu_array = array(
                 'personal_class_list'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_manage'),'menu_url'=>'index.php?act=personal_class&op=personalclass_list'),
                 'personal_class_add'=>array('menu_type'=>'link','menu_name'=>Language::get('nc_new'),'menu_url'=>'index.php?act=personal_class&op=personalclass_add'),
         );
         if($menu_key == 'personal_class_edit') {
             $menu_array['personal_class_edit'] = array('menu_type'=>'link','menu_name'=>Language::get('nc_edit'),'menu_url'=>'###');
         }
         $menu_array[$menu_key]['menu_type'] = 'text';
         Tpl::output('menu',$menu_array);
     }

}
