<?php
/**
 * 兑换礼品管理
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class pointprodControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('pointprod,pointorder');
    }

    public function indexOp() {
        $this->pointprodOp();
    }

    /**
     * 积分礼品列表
     */
    public function pointprodOp()
    {
		Tpl::setDirquna('shop');
        Tpl::showpage('pointprod.list');
    }

    /**
     * 积分礼品列表
     */
    public function pointprod_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['pgoods_name']))) {
                $condition['pgoods_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['pgoods_show']))) {
                $condition['pgoods_show'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['pgoods_commend']))) {
                $condition['pgoods_commend'] = (int) $q;
            }
        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'pgoods_name':
                        $condition['pgoods_name'] = array('like', '%'.$q.'%');
                        break;
                }
            }
        }

        switch ($_REQUEST['sortname']) {
            case 'pgoods_points':
            case 'pgoods_price':
            case 'pgoods_storage':
            case 'pgoods_view':
            case 'pgoods_salenum':
                $sort = $_REQUEST['sortname'];
                break;
            default:
                $sort = 'pgoods_sort asc, pgoods_id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }

        $pointprod_model = Model('pointprod');
        $prod_list = (array) $pointprod_model->getPointProdList(
            $condition,
            '*',
            $sort,
            0,
            $_REQUEST['rp']
        );

        $data = array();
        $data['now_page'] = $pointprod_model->shownowpage();
        $data['total_num'] = $pointprod_model->gettotalnum();

        foreach ($prod_list as $val) {
            $o = '<a class="btn red confirm-del-on-click" href="javascript:;" data-href="' . urlAdminShop('pointprod', 'prod_drop', array(
                'pg_id' => $val['pgoods_id'],
            )) . '"><i class="fa fa-trash-o"></i>删除</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            $o .= '<li><a href="' . urlAdminShop('pointprod', 'prod_edit', array(
                'pg_id' => $val['pgoods_id'],
            )) . '">编辑礼品</a></li>';

            if ($val['pgoods_show'] == '1') {
                $o .= '<li><a href="javascript:;" data-ie-column="pgoods_show" data-ie-value="0">下架礼品</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-ie-column="pgoods_show" data-ie-value="1">上架礼品</a></li>';
            }

            if ($val['pgoods_commend'] == '1') {
                $o .= '<li><a href="javascript:;" data-ie-column="pgoods_commend" data-ie-value="0">取消推荐</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-ie-column="pgoods_commend" data-ie-value="1">推荐礼品</a></li>';
            }

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;

            $i['pgoods_name'] = '<a target="_blank" href="' . urlShop('pointprod', 'pinfo', array( 'id' => $val['pgoods_id'])) . '">'.
                $val['pgoods_name'].'<i class="fa fa-external-link " title="新窗口打开"></i></a>';

            $i['pgoods_image_url'] = <<<EOB
<a href="javascript:;" class="pic-thumb-tip"
onmouseout="toolTip()" onmouseover="toolTip('<img src=\'{$val['pgoods_image_small']}\'>')">
<i class='fa fa-picture-o'></i></a>
EOB;

            $i['pgoods_points'] = $val['pgoods_points'];
            $i['pgoods_price'] = $val['pgoods_price'];
            $i['pgoods_storage'] = $val['pgoods_storage'];
            $i['pgoods_view'] = $val['pgoods_view'];
            $i['pgoods_salenum'] = $val['pgoods_salenum'];

            $i['pgoods_show_onoff'] = $val['pgoods_show'] == '1'
                ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>'
                : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $i['pgoods_commend_onoff'] = $val['pgoods_commend'] == '1'
                ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>'
                : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $data['list'][$val['pgoods_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 积分礼品添加
     */
    public function prod_addOp(){
        $hourarr = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
        $upload_model = Model('upload');
        if (chksubmit()){
            //验证表单
            $obj_validate = new Validate();
            $validate_arr[] = array("input"=>$_POST["goodsname"],"require"=>"true","message"=>L('admin_pointprod_add_goodsname_error'));
            $validate_arr[] = array("input"=>$_POST["goodsprice"],"require"=>"true","validator"=>"DoublePositive","message"=>L('admin_pointprod_add_goodsprice_number_error'));
            $validate_arr[] = array('input'=>$_POST['goodspoints'],'require'=>'true','validator'=>'IntegerPositive','message'=>L('admin_pointprod_add_goodspoint_number_error'));
            $validate_arr[] = array('input'=>$_POST['goodsserial'],'require'=>'true','message'=>L('admin_pointprod_add_goodsserial_null_error'));
            $validate_arr[] = array('input'=>$_POST['goodsstorage'],'require'=>'true','validator'=>'IntegerPositive','message'=>L('admin_pointprod_add_storage_number_error'));
            $validate_arr[] = array('input'=>$_POST['sort'],'require'=>'true','validator'=>'IntegerPositive','message'=>L('admin_pointprod_add_sort_number_error'));
            if ($_POST['islimit'] == 1){
                $validate_arr[] = array('input'=>$_POST['limitnum'],'validator'=>'IntegerPositive','message'=>L('admin_pointprod_add_limitnum_digits_error'));
            }
            if ($_POST['islimittime']){
                $validate_arr[] = array('input'=>$_POST['starttime'],'require'=>'true','message'=>L('admin_pointprod_add_limittime_null_error'));
                $validate_arr[] = array('input'=>$_POST['endtime'],'require'=>'true','message'=>L('admin_pointprod_add_limittime_null_error'));
            }
            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog(L('error').$error,'','error');
            }

            $model_pointprod = Model('pointprod');
            $goods_array = array();
            $goods_array['pgoods_name']     = trim($_POST['goodsname']);
            $goods_array['pgoods_tag']      = trim($_POST['goodstag']);
            $goods_array['pgoods_price']    = trim($_POST['goodsprice']);

            $goods_array['pgoods_points']   = trim($_POST['goodspoints']);
            $goods_array['pgoods_serial']   = trim($_POST['goodsserial']);
            $goods_array['pgoods_storage']  = intval($_POST['goodsstorage']);


            $goods_array['pgoods_islimit'] = intval($_POST['islimit']);
            if ($goods_array['pgoods_islimit'] == 1){
                $goods_array['pgoods_limitnum'] = intval($_POST['limitnum']);
            }else {
                $goods_array['pgoods_limitnum'] = 0;
            }
            $goods_array['pgoods_islimittime'] = intval($_POST['islimittime']);
            if ($goods_array['pgoods_islimittime'] == 1){
                //如果添加了开始时间
                if (trim($_POST['starttime'])){
                    $starttime = trim($_POST['starttime']);
                    $sdatearr = explode('-',$starttime);
                    $starttime = mktime(intval($_POST['starthour']),0,0,$sdatearr[1],$sdatearr[2],$sdatearr[0]);
                    unset($sdatearr);
                }
                if (trim($_POST['endtime'])){
                    $endtime = trim($_POST['endtime']);
                    $edatearr = explode('-',$endtime);
                    $endtime = mktime(intval($_POST['endhour']),0,0,$edatearr[1],$edatearr[2],$edatearr[0]);
                }
                $goods_array['pgoods_starttime'] = $starttime;
                $goods_array['pgoods_endtime'] = $endtime;
            }else {
                $goods_array['pgoods_starttime'] = '';
                $goods_array['pgoods_endtime'] = '';
            }
            $goods_array['pgoods_show']     = trim($_POST['showstate']);
            $goods_array['pgoods_commend']  = trim($_POST['commendstate']);
            $goods_array['pgoods_add_time'] = time();
            $goods_array['pgoods_state']        = intval($_POST['forbidstate']);
            $goods_array['pgoods_close_reason']     = trim($_POST['forbidreason']);
            $goods_array['pgoods_keywords']     = trim($_POST['keywords']);
            $goods_array['pgoods_description']   = trim($_POST['description']);
            $goods_array['pgoods_body']   = trim($_POST['pgoods_body']);
            $goods_array['pgoods_sort']   = intval($_POST['sort']);
            $goods_array['pgoods_limitmgrade']   = intval($_POST['limitgrade']);

            //添加礼品代表图片
            $indeximg_succ = false;
            if (!empty($_FILES['goods_image']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_POINTPROD);
                $upload->set('thumb_width', '60,240');
                $upload->set('thumb_height','60,240');
                $upload->set('thumb_ext',   '_small,_mid');
                $result = $upload->upfile('goods_image');
                if ($result){
                    $indeximg_succ = true;
                    $goods_array['pgoods_image'] = $upload->file_name;
                }else {
                    showDialog($upload->error,'','error');
                }
            }
            $state = $model_pointprod->addPointGoods($goods_array);
            if($state){
                //礼品代表图片数据入库
                if ($indeximg_succ){
                    $insert_array = array();
                    $insert_array['file_name'] = $upload->file_name;
                    $insert_array['file_thumb'] = $upload->thumb_image;
                    $insert_array['upload_type'] = 5;
                    $insert_array['file_size'] = filesize(BASE_UPLOAD_PATH.DS.ATTACH_POINTPROD.DS.$upload->file_name);
                    $insert_array['item_id'] = $state;
                    $insert_array['upload_time'] = time();
                    $upload_model->add($insert_array);
                }
                //更新积分礼品描述图片
                $file_idstr = '';
                if (is_array($_POST['file_id']) && count($_POST['file_id'])>0){
                    $file_idstr = "'".implode("','",$_POST['file_id'])."'";
                }
                $upload_model->updatebywhere(array('item_id'=>$state),array('upload_type'=>6,'item_id'=>'0','upload_id_in'=>"{$file_idstr}"));
                $this->log(L('admin_pointprod_add_title').'['.$_POST['goodsname'].']');
                showDialog(L('admin_pointprod_add_success'),'index.php?act=pointprod&op=pointprod','succ');
            }
        }
        //模型实例化
        $where = array();
        $where['upload_type'] = '6';
        $where['item_id'] = '0';
        $file_upload = $upload_model->getUploadList($where);
        if (is_array($file_upload)){
            foreach ($file_upload as $k => $v){
                $file_upload[$k]['upload_path'] = UPLOAD_SITE_URL.DS.ATTACH_POINTPROD.DS.$file_upload[$k]['file_name'];
            }
        }
        Tpl::output('file_upload',$file_upload);
        Tpl::output('PHPSESSID',session_id());
        $hourarr = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
        Tpl::output('hourarr',$hourarr);
        //会员级别
        $member_grade = Model('member')->getMemberGradeArr();
        Tpl::output('member_grade',$member_grade);
		Tpl::setDirquna('shop');
        Tpl::showpage('pointprod.add');
    }

    /**
     * 积分礼品编辑
     */
    public function prod_editOp(){
        $hourarr = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
        $upload_model = Model('upload');
        $pg_id = intval($_GET['pg_id']);
        if (!$pg_id){
            showDialog(L('admin_pointprod_parameter_error'),'index.php?act=pointprod&op=pointprod','error');
        }
        $model_pointprod = Model('pointprod');
        //查询礼品记录是否存在
        $prod_info = $model_pointprod->getPointProdInfo(array('pgoods_id'=>$pg_id));
        if (!$prod_info){
            showDialog(L('admin_pointprod_record_error'),'index.php?act=pointprod&op=pointprod','error');
        }
        if (chksubmit()){
            //验证表单
            $obj_validate = new Validate();
            $validate_arr[] = array("input"=>$_POST["goodsname"],"require"=>"true","message"=>L('admin_pointprod_add_goodsname_error'));
            $validate_arr[] = array("input"=>$_POST["goodsprice"],"require"=>"true","validator"=>"DoublePositive","message"=>L('admin_pointprod_add_goodsprice_number_error'));
            $validate_arr[] = array('input'=>$_POST['goodspoints'],'require'=>'true','validator'=>'IntegerPositive','message'=>L('admin_pointprod_add_goodspoint_number_error'));
            $validate_arr[] = array('input'=>$_POST['goodsserial'],'require'=>'true','message'=>L('admin_pointprod_add_goodsserial_null_error'));
            $validate_arr[] = array('input'=>$_POST['goodsstorage'],'require'=>'true','validator'=>'IntegerPositive','message'=>L('admin_pointprod_add_storage_number_error'));
            $validate_arr[] = array('input'=>$_POST['sort'],'require'=>'true','validator'=>'IntegerPositive','message'=>L('admin_pointprod_add_sort_number_error'));
            if ($_POST['islimit'] == 1){
                $validate_arr[] = array('input'=>$_POST['limitnum'],'validator'=>'IntegerPositive','message'=>L('admin_pointprod_add_limitnum_digits_error'));
            }
            if ($_POST['islimittime']){
                $validate_arr[] = array('input'=>$_POST['starttime'],'require'=>'true','message'=>L('admin_pointprod_add_limittime_null_error'));
                $validate_arr[] = array('input'=>$_POST['endtime'],'require'=>'true','message'=>L('admin_pointprod_add_limittime_null_error'));
            }
            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog(L('error').$error,'','error');
            }
            //实例化店铺商品模型
            $model_pointprod    = Model('pointprod');

            $goods_array            = array();
            $goods_array['pgoods_name']     = trim($_POST['goodsname']);
            $goods_array['pgoods_tag']      = trim($_POST['goodstag']);
            $goods_array['pgoods_price']    = trim($_POST['goodsprice']);

            $goods_array['pgoods_points']   = trim($_POST['goodspoints']);
            $goods_array['pgoods_serial']   = trim($_POST['goodsserial']);
            $goods_array['pgoods_storage']  = intval($_POST['goodsstorage']);
            $goods_array['pgoods_islimit'] = intval($_POST['islimit']);
            if ($goods_array['pgoods_islimit'] == 1){
                $goods_array['pgoods_limitnum'] = intval($_POST['limitnum']);
            }else {
                $goods_array['pgoods_limitnum'] = 0;
            }
            $goods_array['pgoods_islimittime'] = intval($_POST['islimittime']);
            if ($goods_array['pgoods_islimittime'] == 1){
                //如果添加了开始时间
                if (trim($_POST['starttime'])){
                    $starttime = trim($_POST['starttime']);
                    $sdatearr = explode('-',$starttime);
                    $starttime = mktime(intval($_POST['starthour']),0,0,$sdatearr[1],$sdatearr[2],$sdatearr[0]);
                    unset($sdatearr);
                }
                if (trim($_POST['endtime'])){
                    $endtime = trim($_POST['endtime']);
                    $edatearr = explode('-',$endtime);
                    $endtime = mktime(intval($_POST['endhour']),0,0,$edatearr[1],$edatearr[2],$edatearr[0]);
                }
                $goods_array['pgoods_starttime'] = $starttime;
                $goods_array['pgoods_endtime'] = $endtime;
            }else {
                $goods_array['pgoods_starttime'] = '';
                $goods_array['pgoods_endtime'] = '';
            }
            $goods_array['pgoods_show']     = trim($_POST['showstate']);
            $goods_array['pgoods_commend']  = trim($_POST['commendstate']);
            $goods_array['pgoods_state']        = intval($_POST['forbidstate']);
            $goods_array['pgoods_close_reason']     = trim($_POST['forbidreason']);
            $goods_array['pgoods_keywords']     = trim($_POST['keywords']);
            $goods_array['pgoods_description']   = trim($_POST['description']);
            $goods_array['pgoods_body']   = trim($_POST['pgoods_body']);
            $goods_array['pgoods_sort']   = intval($_POST['sort']);
            $goods_array['pgoods_limitmgrade']   = intval($_POST['limitgrade']);
            //添加礼品代表图片
            $indeximg_succ = false;
            if (!empty($_FILES['goods_image']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_POINTPROD);
                $upload->set('thumb_width', '60,240');
                $upload->set('thumb_height','60,240');
                $upload->set('thumb_ext',   '_small,_mid');
                $result = $upload->upfile('goods_image');
                if ($result){
                    $indeximg_succ = true;
                    $goods_array['pgoods_image'] = $upload->file_name;
                }else {
                    showDialog($upload->error,'','error');
                }
            }
            $state = $model_pointprod->editPointProd($goods_array,array('pgoods_id'=>$prod_info['pgoods_id']));
            if($state){
                //礼品代表图片数据入库
                if ($indeximg_succ){
                    //删除原有图片
                    $upload_list = $upload_model->getUploadList(array('upload_type'=>5,'item_id'=>$prod_info['pgoods_id']));

                    if (is_array($upload_list) && count($upload_list)>0){
                        $upload_idarr = array();
                        foreach ($upload_list as $v){
                            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_POINTPROD.DS.$v['file_name']);
                            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_POINTPROD.DS.$v['file_thumb']);
                            $upload_idarr[] = $v['upload_id'];
                        }
                        //删除图片
                        $upload_model->dropUploadById($upload_idarr);
                    }
                    $insert_array = array();
                    $insert_array['file_name'] = $upload->file_name;
                    $insert_array['file_thumb'] = $upload->thumb_image;
                    $insert_array['upload_type'] = 5;
                    $insert_array['file_size'] = filesize(BASE_UPLOAD_PATH.DS.DS.ATTACH_POINTPROD.DS.$upload->file_name);
                    $insert_array['item_id'] = $prod_info['pgoods_id'];
                    $insert_array['upload_time'] = time();
                    $upload_model->add($insert_array);
                }
                //更新积分礼品描述图片
                $file_idstr = '';
                if (is_array($_POST['file_id']) && count($_POST['file_id'])>0){
                    $file_idstr = "'".implode("','",$_POST['file_id'])."'";
                }
                $upload_model->updatebywhere(array('item_id'=>$prod_info['pgoods_id']),array('upload_type'=>6,'item_id'=>'0','upload_id_in'=>"{$file_idstr}"));

                $this->log(L('nc_edit,admin_pointprodp').'['.$_POST['goodsname'].']');
                showDialog(L('admin_pointprod_edit_success'),'index.php?act=pointprod&op=pointprod','succ');
            }
        }else {
            $where = array();
            $where['upload_type'] = '6';
            $where['item_id'] = $prod_info['pgoods_id'];
            $file_upload = $upload_model->getUploadList($where);
            if (is_array($file_upload)){
                foreach ($file_upload as $k => $v){
                    $file_upload[$k]['upload_path'] = UPLOAD_SITE_URL.DS.ATTACH_POINTPROD.DS.$file_upload[$k]['file_name'];
                }
            }
            //会员级别
            $member_grade = Model('member')->getMemberGradeArr();
            Tpl::output('member_grade',$member_grade);
            Tpl::output('file_upload',$file_upload);
            Tpl::output('PHPSESSID',session_id());
            Tpl::output('hourarr',$hourarr);
            Tpl::output('prod_info',$prod_info);
			Tpl::setDirquna('shop');
            Tpl::showpage('pointprod.edit');
        }
    }

    /**
     * 删除积分礼品
     */
    public function prod_dropOp(){
        $pg_id = intval($_GET['pg_id']);
        if (!$pg_id){
            showDialog(L('admin_pointprod_parameter_error'),'index.php?act=pointprod&op=pointprod','error');
        }
        $model_pointprod = Model('pointprod');
        //查询礼品是否存在
        $prod_info = $model_pointprod->getPointProdInfo(array('pgoods_id'=>$pg_id));
        if (!is_array($prod_info) || count($prod_info)<=0){
            showDialog(L('admin_pointprod_record_error'),'index.php?act=pointprod&op=pointprod','error');
        }
        //查询积分礼品的下属信息（比如兑换信息）
        //删除操作
        $result = $model_pointprod->delPointProdById($pg_id);
        if($result) {
            $this->log(L('nc_del,admin_pointprodp').'[ID:'.$pg_id.']');
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 批量删除积分礼品
     */
    public function prod_dropallOp()
    {
        $pg_id = array();
        foreach (explode(',', (string) $_REQUEST['pg_id']) as $i) {
            $pg_id[(int) $i] = null;
        }
        unset($pg_id[0]);
        $pg_id = array_keys($pg_id);

        if (!$pg_id){
            showDialog(L('admin_pointprod_parameter_error'),'index.php?act=pointprod&op=pointprod','','error');
        }
        $result = Model('pointprod')->delPointProdById($pg_id);
        if($result) {
            $this->log(L('nc_del,admin_pointprodp').'[ID:'.implode(',',$pg_id).']');
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 积分礼品异步状态修改
     */
    public function ajaxOp()
    {
        //礼品上架,礼品推荐,礼品禁售
        $id = intval($_GET['id']);
        if ($id <= 0){
            echo 'false'; exit;
        }
        $model_pointprod = Model('pointprod');
        $update_array = array();
        $update_array[$_GET['column']] = trim($_GET['value']);
        $model_pointprod->editPointProd($update_array,array('pgoods_id'=>$id));
        echo 'true';exit;
    }
    /**
     * 积分礼品上传
     */
    public function pointprod_pic_uploadOp(){
        /**
         * 上传图片
         */
        $upload = new UploadFile();
        $upload->set('default_dir',ATTACH_POINTPROD);

        $result = $upload->upfile('fileupload');
        if ($result){
            $_POST['pic'] = $upload->file_name;
        }else {
            echo 'error';exit;
        }
        /**
         * 模型实例化
         */
        $model_upload = Model('upload');
        /**
         * 图片数据入库
        */
        $insert_array = array();
        $insert_array['file_name'] = $_POST['pic'];
        $insert_array['upload_type'] = '6';
        $insert_array['file_size'] = $_FILES['fileupload']['size'];
        $insert_array['upload_time'] = time();
        $insert_array['item_id'] = intval($_POST['item_id']);
        $result = $model_upload->add($insert_array);
        if ($result){
            $data = array();
            $data['file_id'] = $result;
            $data['file_name'] = $_POST['pic'];
            $data['file_path'] = $_POST['pic'];
            /**
             * 整理为json格式
             */
            $output = json_encode($data);
            echo $output;
        }
    }
    /**
     * ajax操作删除已上传图片
     */
    public function ajaxdeluploadOp(){
        //删除文章图片
        if (intval($_GET['file_id']) > 0){
            $model_upload = Model('upload');
            /**
             * 删除图片
             */
            $file_array = $model_upload->getOneUpload(intval($_GET['file_id']));
            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_POINTPROD.DS.$file_array['file_name']);
            /**
             * 删除信息
             */
            $model_upload->del(intval($_GET['file_id']));
            echo 'true';exit;
        }else {
            echo 'false';exit;
        }
    }

    /**
     * 积分兑换列表
     */
    public function pointorder_listOp()
    {
        $states = Model('pointorder')->getPointOrderStateBySign();
        Tpl::output('states', $states);
Tpl::setDirquna('shop');
        Tpl::showpage('pointorder.list');
    }

    /**
     * 积分兑换列表XML
     */
    public function pointorder_list_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['point_ordersn']))) {
                $condition['point_ordersn'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['point_buyername']))) {
                $condition['point_buyername'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['point_orderstate']))) {
                $condition['point_orderstate'] = (int) $q;
            }
        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'point_ordersn':
                        $condition['point_ordersn'] = array('like', '%' . $q . '%');
                        break;
                    case 'point_buyername':
                        $condition['point_buyername'] = $q;
                        break;
                }
            }
        }

        $model_pointorder = Model('pointorder');
        $list = (array) $model_pointorder->getPointOrderList($condition, '*', $_REQUEST['rp'], 0, 'point_orderid desc');

        $data = array();
        $data['now_page'] = $model_pointorder->shownowpage();
        $data['total_num'] = $model_pointorder->gettotalnum();

        foreach ($list as $val) {
            $o = '<a class="btn green" href="' . urlAdminShop('pointprod', 'order_info', array(
                'order_id' => $val['point_orderid'],
            )) . '"><i class="fa fa-list-alt"></i>查看</a>';

            if (
                $val['point_orderallowship']
                || $val['point_orderalloweditship']
                || $val['point_orderallowcancel']
                || $val['point_orderallowdelete']
            ) {
                $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

                if ($val['point_orderallowship']) {
                    // 发货（已确认付款，待发货）
                    $o .= '<li><a href="' . urlAdminShop('pointprod', 'order_ship', array(
                        'id' => $val['point_orderid'],
                    )) . '">设置发货</a></li>';
                }

                if ($val['point_orderalloweditship']) {
                    // 修改物流（已发货，待收货）
                    $o .= '<li><a href="' . urlAdminShop('pointprod', 'order_ship', array(
                        'id' => $val['point_orderid'],
                    )) . '">修改物流</a></li>';
                }

                if ($val['point_orderallowcancel']) {
                    // 取消订单（未发货）
                    $o .= '<li><a class="confirm-on-click" href="' . urlAdminShop('pointprod', 'order_cancel', array(
                        'id' => $val['point_orderid'],
                    )) . '">取消订单</a></li>';
                }

                if ($val['point_orderallowdelete']) {
                    // 删除订单
                    $o .= '<li><a class="confirm-on-click" href="' . urlAdminShop('pointprod', 'order_drop', array(
                        'order_id' => $val['point_orderid'],
                    )) . '">删除订单</a></li>';
                }

                $o .= '</ul></span>';
            }

            $i = array();
            $i['operation'] = $o;

            $i['point_ordersn'] = $val['point_ordersn'];
            $i['point_buyername'] = $val['point_buyername'];
            $i['point_allpoint'] = $val['point_allpoint'];
            $i['point_addtime_text'] = date('Y-m-d H:i:s', $val['point_addtime']);
            $i['point_orderstatetext'] = $val['point_orderstatetext'];

            $data['list'][$val['point_orderid']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 删除兑换订单信息
     */
    public function order_dropOp(){
        $data = Model('pointorder')->delPointOrderByOrderID($_GET['order_id']);
        if ($data['state']){
            showDialog(L('admin_pointorder_del_success'),'index.php?act=pointprod&op=pointorder_list','succ');
        } else {
            showDialog($data['msg'],'index.php?act=pointprod&op=pointorder_list','error');
        }
    }

    /**
     * 取消兑换
     */
    public function order_cancelOp(){
        $model_pointorder = Model('pointorder');
        //取消订单
        $data = $model_pointorder->cancelPointOrder($_GET['id']);
        if ($data['state']){
            showDialog(L('admin_pointorder_cancel_success'),'index.php?act=pointprod&op=pointorder_list','succ');
        }else {
            showDialog($data['msg'],'index.php?act=pointprod&op=pointorder_list','error');
        }
    }

    /**
     * 发货
     */
    public function order_shipOp(){
        $order_id = intval($_GET['id']);
        if ($order_id <= 0){
            showDialog(L('admin_pointorder_parameter_error'),'index.php?act=pointprod&op=pointorder_list','error');
        }
        $model_pointorder = Model('pointorder');
        //获取订单状态
        $pointorderstate_arr = $model_pointorder->getPointOrderStateBySign();

        //查询订单信息
        $where = array();
        $where['point_orderid'] = $order_id;
        $where['point_orderstate'] = array('in',array($pointorderstate_arr['waitship'][0],$pointorderstate_arr['waitreceiving'][0]));//待发货和已经发货状态
        $order_info = $model_pointorder->getPointOrderInfo($where);
        if (!$order_info){
            showDialog(L('admin_pointorderd_record_error'),'index.php?act=pointprod&op=pointorder_list','error');
        }
        if (chksubmit()){
            $obj_validate = new Validate();
            $validate_arr[] = array("input"=>$_POST["shippingcode"],"require"=>"true","message"=>L('admin_pointorder_ship_code_nullerror'));
            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog(L('error').$error,'index.php?act=pointprod&op=pointorder_list','error');
            }
            //发货
            $data = $model_pointorder->shippingPointOrder($order_id, $_POST, $order_info);
            if ($data['state']){
                showDialog('发货修改成功','index.php?act=pointprod&op=pointorder_list','succ');
            }else {
                showDialog($data['msg'],'index.php?act=pointprod&op=pointorder_list','error');
            }
        } else {
            $express_list = Model('express')->getExpressList();
            Tpl::output('express_list',$express_list);
            Tpl::output('order_info',$order_info);
			Tpl::setDirquna('shop');
            Tpl::showpage('pointorder.ship');
        }
    }
    /**
     * 兑换信息详细
     */
    public function order_infoOp(){
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0){
            showDialog(L('admin_pointorder_parameter_error'),'index.php?act=pointprod&op=pointorder_list','error');
        }
        //查询订单信息
        $model_pointorder = Model('pointorder');
        $order_info = $model_pointorder->getPointOrderInfo(array('point_orderid'=>$order_id));
        if (!$order_info){
            showDialog(L('admin_pointorderd_record_error'),'index.php?act=pointprod&op=pointorder_list','error');
        }
        $orderstate_arr = $model_pointorder->getPointOrderState($order_info['point_orderstate']);
        $order_info['point_orderstatetext'] = $orderstate_arr[1];

        //查询兑换订单收货人地址
        $orderaddress_info = $model_pointorder->getPointOrderAddressInfo(array('point_orderid'=>$order_id));
        Tpl::output('orderaddress_info',$orderaddress_info);

        //兑换商品信息
        $prod_list = $model_pointorder->getPointOrderGoodsList(array('point_orderid'=>$order_id));
        Tpl::output('prod_list',$prod_list);

        //物流公司信息
        if ($order_info['point_shipping_ecode'] != ''){
            $data = Model('express')->getExpressInfoByECode($order_info['point_shipping_ecode']);
            if ($data['state']){
                $express_info = $data['data']['express_info'];
            }
            Tpl::output('express_info',$express_info);
        }

        Tpl::output('order_info',$order_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('pointorder.info');
    }
}
