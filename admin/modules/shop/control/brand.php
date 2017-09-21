<?php
/**
 * 商品品牌管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class brandControl extends SystemControl{
    const EXPORT_SIZE = 1000;
    public function __construct(){
        parent::__construct();
        Language::read('brand');
    }

    public function indexOp() {
        $this->brandOp();
    }

    /**
     * 品牌列表
     */
    public function brandOp(){
                        
        Tpl::setDirquna('shop');
        Tpl::showpage('brand.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_brand = Model('brand');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('brand_id', 'brand_name', 'brand_initial', 'brand_pic', 'brand_bgpic','brand_sort', 'brand_recommend', 'show_type');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
                $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        // 品牌列表
        if ($_GET['type'] == 'apply') {
            $brand_list = $model_brand->getBrandNoPassedList($condition, '*', $page, $order);
        } else {
            $brand_list = $model_brand->getBrandPassedList($condition, '*', $page, $order);
        }

        $data = array();
        $data['now_page'] = $model_brand->shownowpage();
        $data['total_num'] = $model_brand->gettotalnum();
        foreach ($brand_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del(".$value['brand_id'].")\"><i class='fa fa-trash-o'></i>删除</a>";
            if ($_GET['type'] == 'apply') {
                $operation .= "<a class='btn orange' href='javascript:void(0)' onclick=\"fg_apply(".$value['brand_id'].")\"><i class='fa fa-check-square'></i>审核</a>";
            } else {
                $operation .= "<a class='btn blue' href='index.php?act=brand&op=brand_edit&brand_id=" . $value['brand_id'] . "'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            }
            $param['operation'] = $operation;
            $param['brand_id'] = $value['brand_id'];
            $param['brand_name'] = $value['brand_name'];
            $param['brand_initial'] = $value['brand_initial'];
            $param['brand_pic'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=". brandImage($value['brand_pic']).">\")'><i class='fa fa-picture-o'></i></a>";
            $param['brand_sort'] = $value['brand_sort'];
            $param['brand_recommend'] = $value['brand_recommend'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['show_type'] = $value['show_type'] == '1' ? '文字' : '图片';
            $data['list'][$value['brand_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * csv导出
     */
    public function export_csvOp() {
        $model_brand = Model('brand');
        $condition = array();
        $limit = false;
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['brand_id'] = array('in', $id_array);
        }
        if (!is_numeric($_GET['curpage'])){
            $count = $model_brand->getBrandCount($condition);
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $array = array();
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=store&op=index');
                                
        Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
                exit();
            }
        } else {
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = $limit1 .','. $limit2;
        }

        $brand_list = $model_brand->getBrandPassedList($condition, '*', null, 'brand_sort asc, brand_id desc', $limit);
        $this->createCsv($brand_list);
    }
    /**
     * 生成csv文件
     */
    private function createCsv($brand_list) {
        $data = array();
        foreach ($brand_list as $value) {
            $param = array();
            $param['brand_id'] = $value['brand_id'];
            $param['brand_name'] = $value['brand_name'];
            $param['brand_initial'] = $value['brand_initial'];
            $param['brand_pic'] = brandImage($value['brand_pic']);
            $param['brand_sort'] = $value['brand_sort'];
            $param['brand_recommend'] = $value['brand_recommend'] ==  '1' ? '是' : '否';
            $param['show_type'] = $value['show_type'] == '1' ? '文字' : '图片';
            $data[$value['brand_id']] = $param;
        }

        $header = array(
                'brand_id' => '品牌ID',
                'brand_name' => '品牌名称',
                'brand_initial' => '首字母',
                'brand_pic' => '品牌图片',
                'brand_sort' => '品牌排序',
                'brand_recommend' => '品牌推荐',
                'show_type' => '展示形式'
        );
        array_unshift($data, $header);
        $csv = new Csv();
        $export_data = $csv->charset($data,CHARSET,'gbk');
        $csv->filename = $csv->charset('brand_list',CHARSET).$_GET['curpage'] . '-'.date('Y-m-d');
        $csv->export($data);   
    }

    /**
     * 增加品牌
     */
    public function brand_addOp(){
        $lang   = Language::getLangContent();
        $model_brand = Model('brand');
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["brand_name"], "require"=>"true", "message"=>$lang['brand_add_name_null']),
                array("input"=>$_POST["brand_initial"], "require"=>"true", "message"=>'请填写首字母'),
                array("input"=>$_POST["brand_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['brand_add_sort_int']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $insert_array = array();
                $insert_array['brand_name'] = trim($_POST['brand_name']);
                $insert_array['brand_initial'] = strtoupper($_POST['brand_initial']);
                $insert_array['brand_tjstore'] = trim($_POST['brand_tjstore']);
                $insert_array['brand_bgpic'] = trim($_POST['brand_bgpic']);
                $insert_array['brand_xbgpic'] = trim($_POST['brand_xbgpic']);
                $insert_array['brand_introduction'] = trim($_POST['brand_introduction']);
                $insert_array['class_id']   = $_POST['class_id'];
                $insert_array['brand_class'] = trim($_POST['brand_class']);
                $insert_array['brand_pic'] = trim($_POST['brand_pic']);
                $insert_array['brand_recommend'] = trim($_POST['brand_recommend']);
                $insert_array['brand_sort'] = intval($_POST['brand_sort']);
                $insert_array['show_type'] = intval($_POST['show_type'])==1?1:0;
                $result = $model_brand->addBrand($insert_array);
                if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?act=brand&op=brand_add',
                            'msg'=>$lang['brand_add_again'],
                        ),
                        array(
                            'url'=>'index.php?act=brand&op=brand',
                            'msg'=>$lang['brand_add_back_to_list'],
                        )
                    );
                    $this->log(L('nc_add,brand_index_brand').'['.$_POST['brand_name'].']',1);
                    showMessage($lang['nc_common_save_succ'],$url);
                }else {
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }

        // 一级商品分类
        $gc_list = Model('goods_class')->getGoodsClassListByParentId(0);
        Tpl::output('gc_list', $gc_list);
                
        Tpl::setDirquna('shop');
        Tpl::showpage('brand.add');
    }

    /**
     * 品牌编辑
     */
    public function brand_editOp(){
        $lang   = Language::getLangContent();
        $model_brand = Model('brand');

        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["brand_name"], "require"=>"true", "message"=>$lang['brand_add_name_null']),
                array("input"=>$_POST["brand_initial"], "require"=>"true", "message"=>'请填写首字母'),
                array("input"=>$_POST["brand_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['brand_add_sort_int']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $brand_info = $model_brand->getBrandInfo(array('brand_id' => intval($_POST['brand_id'])));
                $where = array();
                $where['brand_id'] = intval($_POST['brand_id']);
                $update_array = array();
                $update_array['brand_name'] = trim($_POST['brand_name']);
                $update_array['brand_initial'] = strtoupper($_POST['brand_initial']);
                $update_array['brand_tjstore'] = trim($_POST['brand_tjstore']);
                $update_array['brand_bgpic'] = trim($_POST['brand_bgpic']);
                $update_array['brand_xbgpic'] = trim($_POST['brand_xbgpic']);
                $update_array['brand_introduction'] = trim($_POST['brand_introduction']);
                $update_array['class_id']   = $_POST['class_id'];
                $update_array['brand_class'] = trim($_POST['brand_class']);
                if (!empty($_POST['brand_pic'])){
                    $update_array['brand_pic'] = $_POST['brand_pic'];
                }
                $update_array['brand_recommend'] = intval($_POST['brand_recommend']);
                $update_array['brand_sort'] = intval($_POST['brand_sort']);
                $update_array['show_type'] = intval($_POST['show_type'])==1?1:0;
                $result = $model_brand->editBrand($where, $update_array);
                if ($result){
                    if (!empty($_POST['brand_pic']) && !empty($brand_info['brand_pic'])){
                        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_BRAND.DS.$brand_info['brand_pic']);
                    }
                    $url = array(
                        array(
                            'url'=>'index.php?act=brand&op=brand_edit&brand_id='.intval($_POST['brand_id']),
                            'msg'=>$lang['brand_edit_again'],
                        ),
                        array(
                            'url'=>'index.php?act=brand&op=brand',
                            'msg'=>$lang['brand_add_back_to_list'],
                        )
                    );
                    $this->log(L('nc_edit,brand_index_brand').'['.$_POST['brand_name'].']',1);
                    showMessage($lang['nc_common_save_succ'],$url);
                }else {
                    $this->log(L('nc_edit,brand_index_brand').'['.$_POST['brand_name'].']',0);
                    showMessage($lang['nc_common_save_fail']);
                }
            }
        }

        $brand_info = $model_brand->getBrandInfo(array('brand_id' => intval($_GET['brand_id'])));
        if (empty($brand_info)){
            showMessage($lang['param_error']);
        }
        Tpl::output('brand_array',$brand_info);

        // 一级商品分类
        $gc_list = Model('goods_class')->getGoodsClassListByParentId(0);
        Tpl::output('gc_list', $gc_list);
                
        Tpl::setDirquna('shop');
        Tpl::showpage('brand.edit');
    }

    /**
     * 删除品牌
     */
    public function brand_delOp(){
        $brand_id = intval($_GET['id']);
        if ($brand_id <= 0) {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
        Model('brand')->delBrand(array('brand_id' => $brand_id));
        $this->log(L('nc_delete,brand_index_brand').'[ID:'.$brand_id.']',1);
        exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
    }

    /**
     * 品牌申请
     */
    public function brand_applyOp(){
                        
        Tpl::setDirquna('shop');
        Tpl::showpage('brand.apply');
    }

    /**
     * 审核 申请品牌操作
     */
    public function brand_apply_setOp(){
        $brand_id = intval($_GET['id']);
        if ($brand_id <= 0) {
            exit(json_encode(array('state'=>false,'msg'=>'参数错误')));
        }

        /**
         * 更新品牌 申请状态
         */
        $update_array = array();
        $update_array['brand_apply'] = 1;
        $result = Model('brand')->editBrand(array('brand_id' => $brand_id), $update_array);
        if ($result){
            $this->log(L('brand_apply_pass').'[ID:'.$brand_id.']',null);
            exit(json_encode(array('state'=>true,'msg'=>'审核成功')));
        }else {
            $this->log(L('brand_apply_pass').'[ID:'.$brand_id.']',0);
            exit(json_encode(array('state'=>false,'msg'=>'审核失败')));
        }

    }

    /**
     * ajax操作
     */
    public function ajaxOp(){
        $model_brand = Model('brand');
        switch ($_GET['branch']){
            /**
             * 验证品牌名称是否有重复
             */
            case 'check_brand_name':
                $condition['brand_name'] = trim($_GET['brand_name']);
                $condition['brand_id'] = array('neq', intval($_GET['id']));
                $result = $model_brand->getBrandList($condition);
                if (empty($result)){
                    echo 'true';exit;
                }else {
                    echo 'false';exit;
                }
                break;
        }
    }

    /**
     * 品牌导出第一步
     */
    public function export_step1Op(){
        $model_brand = Model('brand');
        $condition = array();
        $condition['brand_apply']   = '1';
        if ($_GET['query'] != '') {
            $condition[$_GET['qtype']] = array('like', '%' . $_GET['query'] . '%');
        }
        $order = '';
        $param = array('brand_id', 'brand_name', 'brand_initial', 'brand_pic', 'brand_sort', 'brand_recommend', 'show_type');
        if (in_array($_GET['sortname'], $param) && in_array($_GET['sortorder'], array('asc', 'desc'))) {
            $order = $_GET['sortname'] . ' ' . $_GET['sortorder'];
        }
        if (!is_numeric($_GET['curpage'])){
            $count = $model_brand->getBrandCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=brand&op=brand');
                                
        Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
            }else{  //如果数量小，直接下载
                $data = $model_brand->getBrandList($condition, '*', 0, $order, self::EXPORT_SIZE);
                $this->createExcel($data);
            }
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_brand->getBrandList($condition, '*', 0, $order, "{$limit1},{$limit2}");
            $this->createExcel($data);
        }
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_brandid'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_brand'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_brand_cate'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_brand_img'));
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['brand_id']);
            $tmp[] = array('data'=>$v['brand_name']);
            $tmp[] = array('data'=>$v['brand_class']);
            $tmp[] = array('data'=>$v['brand_pic']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_brand'),CHARSET));
        $excel_obj->generateXML($excel_obj->charset(L('exp_brand'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

}
