<?php
/**
 * 规格栏目管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class specControl extends SystemControl {
    const EXPORT_SIZE = 5000;
    public function __construct(){
        parent::__construct();
        Language::read('spec');
    }

    public function indexOp() {
        $this->specOp();
    }

    /**
     * 规格管理
     */
    public function specOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('spec.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $order = '';
        $param = array('sp_id', 'sp_name', 'sp_sort', 'class_id', 'class_name');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $condition['order'] = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page   = new Page();
        $page->setEachNum($_POST['rp']);
        $page->setStyle('admin');
        //店铺列表
        $spec_list = Model('spec')->specList($condition, $page);

        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        foreach ((array)$spec_list as $value) {
            $param = array();
            $operation = '';
            if ($value['sp_id'] != DEFAULT_SPEC_COLOR_ID) {
                $operation .= "<a class='btn red' href='javascript:void(0);' onclick='fg_del(". $value['sp_id'] .")'><i class='fa fa-trash-o'></i>删除</a>";
            }
            $operation .= "<a class='btn blue' href='index.php?act=spec&op=spec_edit&sp_id=".$value['sp_id']."'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $param['operation'] = $operation;
            $param['sp_id'] = $value['sp_id'];
            $param['sp_name'] = $value['sp_name'];
            $param['sp_sort'] = $value['sp_sort'];
            $param['class_id'] = $value['class_id'];
            $param['class_name'] = $value['class_name'];
            $data['list'][$value['sp_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 添加规格
     */
    public function spec_addOp(){
        $lang   = Language::getLangContent();
        $model_spec = Model('spec');
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["s_name"], "require"=>"true", "message"=>$lang['spec_add_name_no_null'])
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $spec = array();
                $spec['sp_name']        = $_POST['s_name'];
                $spec['sp_sort']        = intval($_POST['s_sort']);
                $spec['class_id']       = $_POST['class_id'];
                $spec['class_name']     = $_POST['class_name'];

                $return = $model_spec->addSpec($spec);
                if($return) {
                    $url = array(
                        array(
                            'url'=>'index.php?act=spec&op=spec_add',
                            'msg'=>$lang['spec_index_continue_to_dd']
                        ),
                        array(
                            'url'=>'index.php?act=spec&op=spec',
                            'msg'=>$lang['spec_index_return_type_list']
                        )
                    );
                    $this->log(L('nc_add,spec_index_spec_name').'['.$_POST['s_name'].']',1);
                    showMessage($lang['nc_common_save_succ'], $url);
                }else {
                    $this->log(L('nc_add,spec_index_spec_name').'['.$_POST['s_name'].']',0);
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }
        // 一级商品分类
        $gc_list = Model('goods_class')->getGoodsClassListByParentId(0);
        Tpl::output('gc_list', $gc_list);
		Tpl::setDirquna('shop');

        Tpl::showpage('spec.add');
    }

    /**
     * 编辑规格
     */
    public function spec_editOp() {
        $lang   = Language::getLangContent();
        if(empty($_GET['sp_id'])) {
            showMessage($lang['param_error']);
        }
        /**
         * 规格模型
         */
        $model_spec = Model('spec');

        /**
         * 编辑保存
         */
        if (chksubmit()) {
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["s_name"], "require"=>"true", "message"=>$lang['spec_add_name_no_null'])
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {

                //更新规格表
                $param      = array();
                $param['sp_name']       = trim($_POST['s_name']);
                $param['sp_sort']       = intval($_POST['s_sort']);
                $param['class_id']      = $_POST['class_id'];
                $param['class_name']    = $_POST['class_name'];
                $return = $model_spec->specUpdate($param, array('sp_id'=>intval($_POST['s_id'])), 'spec');
                if ($return) {
                    $url = array(
                        array(
                            'url'=>'index.php?act=spec&op=spec',
                            'msg'=>$lang['spec_index_return_type_list']
                        )
                    );
                    $this->log(L('nc_edit,spec_index_spec_name').'['.$_POST['s_name'].']',1);
                    showMessage($lang['nc_common_save_succ'], $url);
                } else {
                    $this->log(L('nc_edit,spec_index_spec_name').'['.$_POST['s_name'].']',0);
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }

        //规格列表
        $spec_list  = $model_spec->getSpecInfo(intval($_GET['sp_id']));
        if(!$spec_list){
            showMessage($lang['param_error']);
        }

        // 一级商品分类
        $gc_list = Model('goods_class')->getGoodsClassListByParentId(0);
        Tpl::output('gc_list', $gc_list);

        Tpl::output('sp_list',$spec_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('spec.edit');
    }

    /**
     * 删除规格
     */
    public function spec_delOp(){
        $lang   = Language::getLangContent();
        if(empty($_GET['id'])) {
            exit(json_encode(array('state'=>false,'msg'=>L('param_error'))));
        }
        //规格模型
        $model_spec = Model('spec');

        if(is_array($_GET['id'])){
            $id = "'".implode("','", $_GET['id'])."'";
        }else{
            $id = intval($_GET['id']);
        }
        //规格列表
        $spec_list  = $model_spec->specList(array('in_sp_id'=>$id));

        if(is_array($spec_list) && !empty($spec_list)){
            // 删除类型与规格关联表
            $return = $model_spec->delSpec('type_spec', array('in_sp_id'=>$id));
            if(!$return){
                exit(json_encode(array('state'=>false,'msg'=>L('nc_common_save_fail'))));
            }

            //删除规格值表
            $return = $model_spec->delSpec('spec_value',array('in_sp_id'=>$id));
            if(!$return){
                exit(json_encode(array('state'=>false,'msg'=>L('nc_common_save_fail'))));
            }

            //删除规格表
            $return = $model_spec->delSpec('spec',array('in_sp_id'=>$id));
            if(!$return){
                exit(json_encode(array('state'=>false,'msg'=>L('nc_common_save_fail'))));
            }

            $this->log(L('nc_delete,spec_index_spec_name').'[ID:'.$id.']',1);
            exit(json_encode(array('state'=>true,'msg'=>L('nc_common_del_succ'))));
        }else{
            $this->log(L('nc_delete,spec_index_spec_name').'[ID:'.$id.']',0);
            exit(json_encode(array('state'=>false,'msg'=>L('param_error'))));
        }
    }
}
