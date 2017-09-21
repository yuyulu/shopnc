<?php
/**
 * 商品分类管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class goods_classControl extends SystemControl{
    private $links = array(
        array('url'=>'act=goods_class&op=goods_class','lang'=>'nc_manage'),
        array('url'=>'act=goods_class&op=goods_class_import','lang'=>'goods_class_index_import'),
        array('url'=>'act=goods_class&op=tag','lang'=>'goods_class_index_tag')
    );
    private $show_type = array(
        1 => '颜色',
        2 => 'SPU'
    );
    public function __construct(){
        parent::__construct();
        Language::read('goods_class');
    }

    public function indexOp() {
        $this->goods_classOp();
    }

    /**
     * 分类管理
     */
    public function goods_classOp(){
        Tpl::output('show_type', $this->show_type);
        $model_class = Model('goods_class');

        //父ID
        $parent_id = 0;
        $gc_id = $_GET['gc_id']?intval($_GET['gc_id']):0;

        //列表
        $tmp_list = $model_class->getTreeClassList(3);
        if (is_array($tmp_list)){
            foreach ($tmp_list as $k => $v){
                if ($v['gc_parent_id'] == $gc_id){
                    //判断是否有子类
                    if ($tmp_list[$k+1]['deep'] > $v['deep']){
                        $v['have_child'] = 1;
                    }
                    $class_list[] = $v;
                }
                if ($v['gc_id'] == $gc_id) {
                    $parent_id = $v['gc_parent_id'];
                    $parent_name = $v['gc_name'];
                }
            }
        }
        if ($gc_id > 0){
            if ($parent_id == 0) {
                $title = '"' . $parent_name . '"的下级列表(二级)';
                $deep = 2;
            } else {
                foreach ($tmp_list as $v) {
                    if ($v['gc_id'] == $parent_id) {
                        $grandparents_name = $v['gc_name'];
                    }
                }
                $title = '"' . $grandparents_name . ' - ' . $parent_name . '"的下级列表(三级)';
                $deep = 3;
            }
            Tpl::output('deep', 3);
            Tpl::output('title', $title);
            Tpl::output('parent_id', $parent_id);
            Tpl::output('gc_id', $gc_id);
            Tpl::output('class_list',$class_list);
						
		    Tpl::setDirquna('shop');
            Tpl::showpage('goods_class.child_list');
        } else {
            Tpl::output('class_list',$class_list);
            Tpl::output('top_link',$this->sublink($this->links,'goods_class'));
            Tpl::setDirquna('shop');
            Tpl::showpage('goods_class.index');
        }
    }

    /**
     * 商品分类添加
     */
    public function goods_class_addOp(){
        $lang   = Language::getLangContent();
        $model_class = Model('goods_class');
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["gc_name"], "require"=>"true", "message"=>$lang['goods_class_add_name_null']),
                array("input"=>$_POST["gc_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['goods_class_add_sort_int']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $insert_array = array();
                $insert_array['gc_name']        = $_POST['gc_name'];
                $insert_array['type_id']        = intval($_POST['t_id']);
                $insert_array['type_name']      = $_POST['t_name'];
                $insert_array['gc_parent_id']   = intval($_POST['gc_parent_id']);
                $insert_array['commis_rate']    = intval($_POST['commis_rate']);
                $insert_array['gc_sort']        = intval($_POST['gc_sort']);
                $insert_array['gc_virtual']     = intval($_POST['gc_virtual']);
                $insert_array['show_type']      = intval($_POST['show_type']);
                $result = $model_class->addGoodsClass($insert_array);
                if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?act=goods_class&op=goods_class_add&gc_parent_id='.$_POST['gc_parent_id'],
                            'msg'=>$lang['goods_class_add_again'],
                        ),
                        array(
                            'url'=>'index.php?act=goods_class&op=goods_class',
                            'msg'=>$lang['goods_class_add_back_to_list'],
                        )
                    );
                    $this->log(L('nc_add,goods_class_index_class').'['.$_POST['gc_name'].']',1);
                    showMessage($lang['nc_common_save_succ'],$url);
                }else {
                    $this->log(L('nc_add,goods_class_index_class').'['.$_POST['gc_name'].']',0);
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }

        //父类列表，只取到第二级
        $parent_list = $model_class->getTreeClassList(2);
        $gc_list = array();
        if (is_array($parent_list)){
            foreach ($parent_list as $k => $v){
                $parent_list[$k]['gc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['gc_name'];
                if($v['deep'] == 1) $gc_list[$k] = $v;
            }
        }
        Tpl::output('gc_list', $gc_list);
        //类型列表
        $model_type = Model('type');
        $type_list  = $model_type->typeList(array('order'=>'type_sort asc'), '', 'type_id,type_name,class_id,class_name');
        $t_list = array();
        if(is_array($type_list) && !empty($type_list)){
            foreach($type_list as $k=>$val){
                $t_list[$val['class_id']]['type'][$k] = $val;
                $t_list[$val['class_id']]['name'] = $val['class_name']==''?L('nc_default'):$val['class_name'];
            }
        }
        ksort($t_list);

        Tpl::output('show_type', $this->show_type);
        Tpl::output('type_list',$t_list);
        Tpl::output('gc_parent_id',$_GET['gc_parent_id']);
        Tpl::output('parent_list',$parent_list);
        Tpl::output('top_link',$this->sublink($this->links,'goods_class_add'));
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_class.add');
    }

    /**
     * 编辑
     */
    public function goods_class_editOp(){
        Tpl::output('show_type', $this->show_type);
        $lang   = Language::getLangContent();
        $model_class = Model('goods_class');

        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["gc_name"], "require"=>"true", "message"=>$lang['goods_class_add_name_null']),
                array("input"=>$_POST["commis_rate"], "require"=>"true", 'validator'=>'range','max'=>100,'min'=>0, "message"=>$lang['goods_class_add_commis_rate_error']),
                array("input"=>$_POST["gc_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['goods_class_add_sort_int']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }

            // 更新分类信息
            $where = array('gc_id' => intval($_POST['gc_id']));
            $update_array = array();
            $update_array['gc_name']        = $_POST['gc_name'];
            $update_array['type_id']        = intval($_POST['t_id']);
            $update_array['type_name']      = trim($_POST['t_name']);
            $update_array['commis_rate']    = intval($_POST['commis_rate']);
            $update_array['gc_sort']        = intval($_POST['gc_sort']);
            $update_array['gc_virtual']     = intval($_POST['gc_virtual']);
            $update_array['show_type']      = intval($_POST['show_type']);

            $result = $model_class->editGoodsClass($update_array, $where);
            if (!$result){
                $this->log(L('nc_edit,goods_class_index_class').'['.$_POST['gc_name'].']',0);
                showMessage($lang['goods_class_batch_edit_fail']);
            }

            // 检测是否需要关联自己操作，统一查询子分类
            if ($_POST['t_commis_rate'] == '1' || $_POST['t_associated'] == '1' || $_POST['t_gc_virtual'] == '1' || $_POST['t_show_type'] == '1') {
                $gc_id_list = $model_class->getChildClass($_POST['gc_id']);
                $gc_ids = array();
                if (is_array($gc_id_list) && !empty($gc_id_list)) {
                    foreach ($gc_id_list as $val){
                        $gc_ids[] = $val['gc_id'];
                    }
                }
                $where = array();
                $where['gc_id'] = array('in', $gc_ids);
                $update = array();
                // 更新该分类下子分类的所有分佣比例
                if ($_POST['t_commis_rate'] == '1') {
                    $update['commis_rate']  = $update_array['commis_rate'];
                }
                // 更新该分类下子分类的所有类型
                if ($_POST['t_associated'] == '1') {
                    $update['type_id']      = $update_array['type_id'];
                    $update['type_name']    = $update_array['type_name'];
                }
                // 虚拟商品
                if ($_POST['t_gc_virtual'] == '1') {
                    $update['gc_virtual']   = $update_array['gc_virtual'];
                }
                // 商品展示方式
                if ($_POST['t_show_type'] == '1') {
                    $update['show_type']    = $update_array['show_type'];
                }
                $model_class->editGoodsClass($update,$where);
            }

            $url = array(
                array(
                    'url'=>'index.php?act=goods_class&op=goods_class_edit&gc_id='.intval($_POST['gc_id']),
                    'msg'=>$lang['goods_class_batch_edit_again'],
                ),
                array(
                    'url'=>'index.php?act=goods_class&op=goods_class',
                    'msg'=>$lang['goods_class_add_back_to_list'],
                )
            );
            $this->log(L('nc_edit,goods_class_index_class').'['.$_POST['gc_name'].']',1);
            showMessage($lang['goods_class_batch_edit_ok'],$url,'html','succ',1,5000);
        }

        $class_array = $model_class->getGoodsClassInfoById(intval($_GET['gc_id']));
        if (empty($class_array)){
            showMessage($lang['goods_class_batch_edit_paramerror']);
        }

        //类型列表
        $model_type = Model('type');
        $type_list  = $model_type->typeList(array('order'=>'type_sort asc'), '', 'type_id,type_name,class_id,class_name');
        $t_list = array();
        if(is_array($type_list) && !empty($type_list)){
            foreach($type_list as $k=>$val){
                $t_list[$val['class_id']]['type'][$k] = $val;
                $t_list[$val['class_id']]['name'] = $val['class_name']==''?L('nc_default'):$val['class_name'];
            }
        }
        ksort($t_list);
        //父类列表，只取到第二级
        $parent_list = $model_class->getTreeClassList(2);
        if (is_array($parent_list)){
            foreach ($parent_list as $k => $v){
                $parent_list[$k]['gc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['gc_name'];
            }
        }
        Tpl::output('parent_list',$parent_list);
        // 一级分类列表
        $gc_list = Model('goods_class')->getGoodsClassListByParentId(0);
        Tpl::output('gc_list', $gc_list);

        Tpl::output('type_list',$t_list);
        Tpl::output('class_array',$class_array);
        $this->links[] = array('url'=>'act=goods_class&op=goods_class_edit','lang'=>'nc_edit');
        Tpl::output('top_link',$this->sublink($this->links,'goods_class_edit'));
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_class.edit');
    }

    /**
     * 分类导入
     */
    public function goods_class_importOp(){
        $lang   = Language::getLangContent();
        $model_class = Model('goods_class');
        //导入
        if (chksubmit()){
            //得到导入文件后缀名
            $csv_array = explode('.',$_FILES['csv']['name']);
            $file_type = end($csv_array);
            if (!empty($_FILES['csv']) && !empty($_FILES['csv']['name']) && $file_type == 'csv'){
                $fp = @fopen($_FILES['csv']['tmp_name'],'rb');
                // 父ID
                $parent_id_1 = 0;

                while (!feof($fp)) {
                    $data = trim(fgets($fp, 4096));
                    switch (strtoupper($_POST['charset'])){
                        case 'UTF-8':
                            if (strtoupper(CHARSET) !== 'UTF-8'){
                                $data = iconv('UTF-8',strtoupper(CHARSET),$data);
                            }
                            break;
                        case 'GBK':
                            if (strtoupper(CHARSET) !== 'GBK'){
                                $data = iconv('GBK',strtoupper(CHARSET),$data);
                            }
                            break;
                    }

                    if (!empty($data)){
                        $data   = str_replace('"','',$data);
                        //逗号去除
                        $tmp_array = array();
                        $tmp_array = explode(',',$data);
                        if($tmp_array[0] == 'sort_order')continue;
                        //第一位是序号，后面的是内容，最后一位名称
                        $tmp_deep = 'parent_id_'.(count($tmp_array)-1);

                        $insert_array = array();
                        $insert_array['gc_sort'] = $tmp_array[0];
                        $insert_array['gc_parent_id'] = $$tmp_deep;
                        $insert_array['gc_name'] = $tmp_array[count($tmp_array)-1];
                        $gc_id = $model_class->addGoodsClass($insert_array);
                        //赋值这个深度父ID
                        $tmp = 'parent_id_'.count($tmp_array);
                        $$tmp = $gc_id;
                    }
                }
                $this->log(L('goods_class_index_import,goods_class_index_class'),1);
                showMessage($lang['nc_common_op_succ'],'index.php?act=goods_class&op=goods_class');
            }else {
                $this->log(L('goods_class_index_import,goods_class_index_class'),0);
                showMessage($lang['goods_class_import_csv_null']);
            }
        }
        Tpl::output('top_link',$this->sublink($this->links,'goods_class_import'));
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_class.import');
    }

    /**
     * 分类导出
     */
    public function goods_class_exportOp(){
            $model_class = Model('goods_class');
            $class_list = $model_class->getTreeClassList();

        @header("Content-type: application/unknown");
        @header("Content-Disposition: attachment; filename=goods_class.csv");
        if (is_array($class_list)){
            foreach ($class_list as $k => $v){
                $tmp = array();
                //序号
                $tmp['gc_sort'] = $v['gc_sort'];
                //深度
                for ($i=1; $i<=($v['deep']-1); $i++){
                    $tmp[] = '';
                }
                //分类名称
                $tmp['gc_name'] = $v['gc_name'];
                $tmp_line = iconv('UTF-8','GB2312//IGNORE',join(',',$tmp));
                $tmp_line = str_replace("\r\n",'',$tmp_line);
                echo $tmp_line."\r\n";
            }
        }
        $this->log(L('goods_class_index_export,goods_class_index_class'),1);
        exit;
    }

    /**
     * 删除分类
     */
    public function goods_class_delOp(){
        if ($_GET['id'] != ''){
            //删除分类
            Model('goods_class')->delGoodsClassByGcIdString($_GET['id']);
            $this->log(L('nc_delete,goods_class_index_class') . '[ID:' . $_GET['id'] . ']',1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * tag列表
     */
    public function tagOp(){
        Tpl::output('top_link',$this->sublink($this->links,'tag'));
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_class_tag.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_class_tag = Model('goods_class_tag');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('gc_tag_id', 'gc_tag_name', 'gc_tag_value', 'gc_id_1', 'gc_id_2', 'gc_id_3');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        //店铺列表
        $tag_list = $model_class_tag->getTagList($condition, $_POST['rp'], '*', $order);

        $data = array();
        $data['now_page'] = $model_class_tag->shownowpage();
        $data['total_num'] = $model_class_tag->gettotalnum();
        foreach ((array)$tag_list as $value) {
            $param = array();
            $operation = "<a class='btn blue' href='javascript:void(0)' onclick=\"fg_edit(".$value['gc_tag_id'].")\"><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $param['operation'] = $operation;
            $param['gc_tag_id'] = $value['gc_tag_id'];
            $param['gc_tag_name'] = $value['gc_tag_name'];
            $param['gc_tag_value'] = $value['gc_tag_value'];
            $param['gc_id_1'] = $value['gc_id_1'];
            $param['gc_id_2'] = $value['gc_id_2'];
            $param['gc_id_3'] = $value['gc_id_3'];
            $data['list'][$value['gc_tag_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 重置TAG
     */
    public function tag_resetOp(){
        $lang   = Language::getLangContent();
        //实例化模型
        $model_class = Model('goods_class');
        $model_class_tag = Model('goods_class_tag');

        //清空TAG
        $return = $model_class_tag->clearTag();
        if(!$return){
            showMessage($lang['goods_class_reset_tag_fail'], 'index.php?act=goods_class&op=tag');
        }

        //商品分类
        $goods_class        = $model_class->getTreeClassList(3);
        //格式化分类。组成三维数组
        if(is_array($goods_class) and !empty($goods_class)) {
            $goods_class_array = array();
            foreach ($goods_class as $val) {
                //一级分类
                if($val['gc_parent_id'] == 0) {
                    $goods_class_array[$val['gc_id']]['gc_name']    = $val['gc_name'];
                    $goods_class_array[$val['gc_id']]['gc_id']      = $val['gc_id'];
                    $goods_class_array[$val['gc_id']]['type_id']    = $val['type_id'];
                }else {
                    //二级分类
                    if(isset($goods_class_array[$val['gc_parent_id']])){
                        $goods_class_array[$val['gc_parent_id']]['sub_class'][$val['gc_id']]['gc_name']         = $val['gc_name'];
                        $goods_class_array[$val['gc_parent_id']]['sub_class'][$val['gc_id']]['gc_id']           = $val['gc_id'];
                        $goods_class_array[$val['gc_parent_id']]['sub_class'][$val['gc_id']]['gc_parent_id']    = $val['gc_parent_id'];
                        $goods_class_array[$val['gc_parent_id']]['sub_class'][$val['gc_id']]['type_id']         = $val['type_id'];
                    }else{
                        foreach ($goods_class_array as $v){
                            //三级分类
                            if(isset($v['sub_class'][$val['gc_parent_id']])){
                                $goods_class_array[$v['sub_class'][$val['gc_parent_id']]['gc_parent_id']]['sub_class'][$val['gc_parent_id']]['sub_class'][$val['gc_id']]['gc_name'] = $val['gc_name'];
                                $goods_class_array[$v['sub_class'][$val['gc_parent_id']]['gc_parent_id']]['sub_class'][$val['gc_parent_id']]['sub_class'][$val['gc_id']]['gc_id']   = $val['gc_id'];
                                $goods_class_array[$v['sub_class'][$val['gc_parent_id']]['gc_parent_id']]['sub_class'][$val['gc_parent_id']]['sub_class'][$val['gc_id']]['type_id'] = $val['type_id'];
                            }
                        }
                    }
                }
            }

            $return = $model_class_tag->tagAdd($goods_class_array);

            if($return){
                $this->log(L('nc_reset').'tag',1);
                showMessage($lang['nc_common_op_succ'], 'index.php?act=goods_class&op=tag');
            }else{
                $this->log(L('nc_reset').'tag',0);
                showMessage($lang['nc_common_op_fail'], 'index.php?act=goods_class&op=tag');
            }
        }else{
            $this->log(L('nc_reset').'tag',0);
            showMessage($lang['goods_class_reset_tag_fail_no_class'], 'index.php?act=goods_class&op=tag');
        }
    }

    /**
     * 更新TAG名称
     */
    public function tag_updateOp(){
        $lang   = Language::getLangContent();
        $model_class = Model('goods_class');
        $model_class_tag = Model('goods_class_tag');

        //需要更新的TAG列表
        $tag_list = $model_class_tag->getTagList(array(), '', 'gc_tag_id,gc_id_1,gc_id_2,gc_id_3');
        if(is_array($tag_list) && !empty($tag_list)){
            foreach ($tag_list as $val){
                //查询分类信息
                $in_gc_id = array();
                if($val['gc_id_1'] != '0'){
                    $in_gc_id[] = $val['gc_id_1'];
                }
                if($val['gc_id_2'] != '0'){
                    $in_gc_id[] = $val['gc_id_2'];
                }
                if($val['gc_id_3'] != '0'){
                    $in_gc_id[] = $val['gc_id_3'];
                }
                $gc_list    = $model_class->getGoodsClassListByIds($in_gc_id);

                //更新TAG信息
                $update_tag                 = array();
                if(isset($gc_list['0']['gc_id']) && $gc_list['0']['gc_id'] != '0'){
                    $update_tag['gc_id_1']      = $gc_list['0']['gc_id'];
                    $update_tag['gc_tag_name']  .= $gc_list['0']['gc_name'];
                }
                if(isset($gc_list['1']['gc_id']) && $gc_list['1']['gc_id'] != '0'){
                    $update_tag['gc_id_2']      = $gc_list['1']['gc_id'];
                    $update_tag['gc_tag_name']  .= "&nbsp;&gt;&nbsp;".$gc_list['1']['gc_name'];
                }
                if(isset($gc_list['2']['gc_id']) && $gc_list['2']['gc_id'] != '0'){
                    $update_tag['gc_id_3']      = $gc_list['2']['gc_id'];
                    $update_tag['gc_tag_name']  .= "&nbsp;&gt;&nbsp;".$gc_list['2']['gc_name'];
                }
                unset($gc_list);
                $update_tag['gc_tag_id']    = $val['gc_tag_id'];
                $return = $model_class_tag->updateTag($update_tag);
                if(!$return){
                    $this->log(L('nc_update').'tag',0);
                    showMessage($lang['nc_common_op_fail'], 'index.php?act=goods_class&op=tag');
                }
            }
            $this->log(L('nc_update').'tag',1);
            showMessage($lang['nc_common_op_succ'], 'index.php?act=goods_class&op=tag');
        }else{
            $this->log(L('nc_update').'tag',0);
            showMessage($lang['goods_class_update_tag_fail_no_class'], 'index.php?act=goods_class&op=tag');
        }

    }

    public function tag_editOp() {
        $model_class_tag = Model('goods_class_tag');
        if($_POST['form_submit']){
            $return = $model_class_tag->updateTag(array('gc_tag_id'=> $_POST['id'], 'gc_tag_value' => $_POST['tag_value']));
            if($return){
                $this->log('编辑TAG值成功['.$_POST['attr_name'].']',1);
                showDialog('编辑成功', '', 'succ', 'CUR_DIALOG.close();$("#flexigrid").flexReload()');
            }else{
                $this->log('编辑TAG值失败['.$_POST['id'].']',0);
                showDialog('编辑失败', '', '', '', 'CUR_DIALOG.close();');
            }
        }
        $id = $_GET['id'];
        $tag_list = $model_class_tag->getTagList(array('gc_tag_id' => $id));
        Tpl::output('tag_info', $tag_list[0]);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_class_tag.edit', 'null_layout');
    }
    
    /**
     * 分类导航
     */
    public function nav_editOp() {
        $gc_id = $_REQUEST['gc_id'];
        $model_goods = Model('goods_class');
        $class_info = $model_goods->getGoodsClassInfoById($gc_id);
        $model_class_nav = Model('goods_class_nav');
        $nav_info = $model_class_nav->getGoodsClassNavInfoByGcId($gc_id);
        if (chksubmit()) {
            $update = array();
            $update['gc_id'] = $gc_id;
            $update['cn_alias'] = $_POST['cn_alias'];
            if (is_array($_POST['class_id'])) {
                $update['cn_classids'] = implode(',', $_POST['class_id']);
            }
            if (is_array($_POST['brand_id'])) {
                $update['cn_brandids'] = implode(',', $_POST['brand_id']);
            }
            $update['cn_adv1_link'] = $_POST['cn_adv1_link'];
            $update['cn_adv2_link'] = $_POST['cn_adv2_link'];
            if (!empty($_FILES['pic']['name'])) {//上传图片
                $upload = new UploadFile();
                @unlink(BASE_UPLOAD_PATH. '/' . ATTACH_GOODS_CLASS . '/' . $nav_info['cn_pic']);
                $upload->set('default_dir',ATTACH_GOODS_CLASS);
                $upload->upfile('pic');
                $update['cn_pic'] = $upload->file_name;
            }
            if (!empty($_FILES['adv1']['name'])) {//上传广告图片
                $upload = new UploadFile();
                @unlink(BASE_UPLOAD_PATH. '/' . ATTACH_GOODS_CLASS . '/' . $nav_info['cn_adv1']);
                $upload->set('default_dir',ATTACH_GOODS_CLASS);
                $upload->upfile('adv1');
                $update['cn_adv1'] = $upload->file_name;
            }
            if (!empty($_FILES['adv2']['name'])) {//上传广告图片
                $upload = new UploadFile();
                @unlink(BASE_UPLOAD_PATH. '/' . ATTACH_GOODS_CLASS . '/' . $nav_info['cn_adv2']);
                $upload->set('default_dir',ATTACH_GOODS_CLASS);
                $upload->upfile('adv2');
                $update['cn_adv2'] = $upload->file_name;
            }
            if (empty($nav_info)) {
                $result = $model_class_nav->addGoodsClassNav($update);
            } else {
                $result = $model_class_nav->editGoodsClassNav($update, $gc_id);
            }
            if($result){
                $this->log('编辑分类导航，'.$class_info['gc_name'],1);
                showMessage('编辑成功');
            }else{
                $this->log('编辑分类导航，'.$class_info['gc_name'],0);
                showMessage('编辑成功', '', '', 'error');
            }
        }

        $pic_name = BASE_UPLOAD_PATH . '/' . ATTACH_GOODS_CLASS . '/' . $nav_info['cn_pic'];
        if (file_exists($pic_name)) {
            $nav_info['cn_pic'] = UPLOAD_SITE_URL. '/' . ATTACH_GOODS_CLASS . '/' . $nav_info['cn_pic'];
        }
        $pic_name = BASE_UPLOAD_PATH . '/' . ATTACH_GOODS_CLASS . '/' . $nav_info['cn_adv1'];
        if (file_exists($pic_name)) {
            $nav_info['cn_adv1'] = UPLOAD_SITE_URL. '/' . ATTACH_GOODS_CLASS . '/' . $nav_info['cn_adv1'];
        }
        $pic_name = BASE_UPLOAD_PATH . '/' . ATTACH_GOODS_CLASS . '/' . $nav_info['cn_adv2'];
        if (file_exists($pic_name)) {
            $nav_info['cn_adv2'] = UPLOAD_SITE_URL. '/' . ATTACH_GOODS_CLASS . '/' . $nav_info['cn_adv2'];
        }
        $nav_info['cn_classids'] = explode(',', $nav_info['cn_classids'] );
        $nav_info['cn_brandids'] = explode(',', $nav_info['cn_brandids'] );
        Tpl::output('nav_info', $nav_info);
        Tpl::output('class_info', $class_info);
        // 一级分类列表
        $gc_list = $model_goods->getGoodsClassListByParentId(0);
        Tpl::output('gc_list', $gc_list);
    
        // 全部三级分类
        $third_class = $model_goods->getChildClassByFirstId($gc_id);
        Tpl::output('third_class', $third_class);
    
        // 品牌列表
        $model_brand    = Model('brand');
        $brand_list     = $model_brand->getBrandPassedList(array());
        $b_list = array();
        if(is_array($brand_list) && !empty($brand_list)){
            foreach($brand_list as $k=>$val){
                $b_list[$val['class_id']]['brand'][$k] = $val;
                $b_list[$val['class_id']]['name'] = $val['brand_class']==''?L('nc_default'):$val['brand_class'];
            }
        }
        ksort($b_list);
        Tpl::output('brand_list', $b_list);
    	
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_class.nav_edit');
    }

    /**
     * ajax操作
     */
    public function ajaxOp(){
        switch ($_GET['branch']){
            /**
             * 更新分类
             */
            case 'gc_name':
                $model_class = Model('goods_class');
                $class_array = $model_class->getGoodsClassInfoById(intval($_GET['id']));

                $condition['gc_name'] = trim($_GET['value']);
                $condition['gc_parent_id'] = $class_array['gc_parent_id'];
                $condition['gc_id'] = array('neq', intval($_GET['id']));
                $class_list = $model_class->getGoodsClassList($condition);
                if (empty($class_list)){
                    $where = array('gc_id' => intval($_GET['id']));
                    $update_array = array();
                    $update_array['gc_name'] = trim($_GET['value']);
                    $model_class->editGoodsClass($update_array, $where);
                    $return = true;
                }else {
                    $return = false;
                }
                exit(json_encode(array('result'=>$return)));
                break;
            /**
             * 分类 排序 显示 设置
             */
            case 'gc_sort':
                $model_class = Model('goods_class');
                $where = array('gc_id' => intval($_GET['id']));
                $update_array = array();
                $update_array['gc_sort'] = $_GET['value'];
                $model_class->editGoodsClass($update_array, $where);
                $return = 'true';
                exit(json_encode(array('result'=>$return)));
                break;
            /**
             * 添加、修改操作中 检测类别名称是否有重复
             */
            case 'check_class_name':
                $model_class = Model('goods_class');
                $condition['gc_name'] = trim($_GET['gc_name']);
                $condition['gc_parent_id'] = intval($_GET['gc_parent_id']);
                $condition['gc_id'] = array('neq', intval($_GET['gc_id']));
                $class_list = $model_class->getGoodsClassList($condition);
                if (empty($class_list)){
                    echo 'true';exit;
                }else {
                    echo 'false';exit;
                }
                break;
        }
    }
}
