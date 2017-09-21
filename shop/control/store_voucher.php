<?php
/**
 * 代金券
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class store_voucherControl extends BaseSellerControl{
    //定义代金券类常量
    const SECONDS_OF_30DAY = 2592000;
    private $applystate_arr;
    private $quotastate_arr;
    private $templatestate_arr;
    //每次导出订单数量
    const EXPORT_SIZE = 1000;

    public function __construct() {
        parent::__construct() ;
        //读取语言包
        Language::read('member_layout,member_voucher');
        //判断系统是否开启代金券功能
        if (C('voucher_allow') != 1){
            showMessage(Language::get('voucher_unavailable'),'index.php?act=store','html','error');
        }
        //申请记录状态
        $this->applystate_arr = array('new'=>array(1,Language::get('voucher_applystate_new')),'verify'=>array(2,Language::get('voucher_applystate_verify')),'cancel'=>array(3,Language::get('voucher_applystate_cancel')));
        //套餐状态
        $this->quotastate_arr = array('activity'=>array(1,Language::get('voucher_quotastate_activity')),'cancel'=>array(2,Language::get('voucher_quotastate_cancel')),'expire'=>array(3,Language::get('voucher_quotastate_expire')));
        //代金券模板状态
        $this->templatestate_arr = array('usable'=>array(1,Language::get('voucher_templatestate_usable')),'disabled'=>array(2,Language::get('voucher_templatestate_disabled')));
        Tpl::output('applystate_arr',$this->applystate_arr);
        Tpl::output('quotastate_arr',$this->quotastate_arr);
        Tpl::output('templatestate_arr',$this->templatestate_arr);
    }
    /*
     * 默认显示代金券模版列表
     */
    public function indexOp() {
        $this->templatelistOp();
    }
    /*
     * 代金券模版列表
     */
    public function templatelistOp(){
        //检查过期的代金券模板状态设为失效
        $this->check_voucher_template_expire();
        $model_voucher = Model('voucher');

        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            //查询是否存在可用套餐
            $current_quota = $model_voucher->getCurrentQuota($_SESSION['store_id']);
            Tpl::output('current_quota',$current_quota);
        }
        //领取方式
        $gettype_arr = $model_voucher->getVoucherGettypeArray();
        //查询列表
        $param = array();
        //领取方式查询
        $gettype_sel = trim($_GET['gettype_sel']);
        if($gettype_sel){
            $param['voucher_t_gettype'] = $gettype_arr[$gettype_sel]['sign'];
        }
        $param['voucher_t_store_id'] = $_SESSION['store_id'];
        if(trim($_GET['txt_keyword'])){
            $param['voucher_t_title'] = array('like','%'.trim($_GET['txt_keyword']).'%');
        }
        $select_state = intval($_GET['select_state']);
        if($select_state){
            $param['voucher_t_state'] = $select_state;
        }
        if($_GET['txt_startdate']){
            $param['voucher_t_end_date'] = array('egt',strtotime($_GET['txt_startdate']));
        }
        if($_GET['txt_enddate']){
            $param['voucher_t_start_date'] = array('elt',strtotime($_GET['txt_enddate']));
        }
        $list = $model_voucher->getVoucherTemplateList($param, '*', 0, 10, 'voucher_t_id desc');
        //领取方式
        TPL::output('gettype_arr',$gettype_arr);
        Tpl::output('list',$list);
        Tpl::output('show_page',$model_voucher->showpage(2));
        $this->profile_menu('voucher','templatelist');
        Tpl::showpage('store_voucher_template.index') ;
    }

    /*
     * 代金券模版列表
     */
    public function voucherlistOp(){
        $t_id = intval($_GET['tid']);
        if($t_id <= 0){
            Tpl::showpage('store_voucher.list','null_layout');
        }
        $model_voucher = Model('voucher');
        //查询代金券模板
        $where = array();
        $where['voucher_t_id'] = $t_id;
        $where['voucher_t_store_id'] = $_SESSION['store_id'];
        $t_info = $model_voucher->getVoucherTemplateInfo($where);
        if(!$t_info){
            Tpl::showpage('store_voucher.list','null_layout');
        }
        TPL::output('t_info',$t_info);

        $voucher_list = array();
        $where = array();
        $where['voucher_t_id'] = $t_id;
        $voucher_list = $model_voucher->getVoucherList($where, '*', 0, 10, 'voucher_owner_id asc,voucher_state asc,voucher_id asc');
        if($voucher_list){
            $voucherstate_arr = $model_voucher->getVoucherStateArray();
            foreach($voucher_list as $k=>$v){
                //卡密
                $voucher_list[$k]['voucher_pwd'] = $model_voucher->get_voucher_pwd($v['voucher_pwd2']);
                //代金券状态文字
                $voucher_list[$k]['voucher_state_text'] = $voucherstate_arr[$v['voucher_state']];
                //领取时间
                $voucher_list[$k]['voucher_active_date'] = $v['voucher_owner_id'] > 0?@date('Y-m-d H:i:s',$v['voucher_active_date']):'';
            }
        }
        TPL::output('voucher_list',$voucher_list);
        Tpl::output('show_page',$model_voucher->showpage(2));
        Tpl::showpage('store_voucher.list','null_layout');
    }

    /**
     * 导出
     */
    public function voucher_exportOp(){
        $t_id = intval($_GET['tid']);
        if($t_id <= 0){
            showDialog('参数错误');
        }
        $model_voucher = Model('voucher');
        //查询代金券模板
        $where = array();
        $where['voucher_t_id'] = $t_id;
        $where['voucher_t_store_id'] = $_SESSION['store_id'];
        $t_info = $model_voucher->getVoucherTemplateInfo($where);
        if(!$t_info){
            showDialog('参数错误');
        }
        $where = array();
        $where['voucher_t_id'] = $t_id;
        if (!is_numeric($_GET['curpage'])){
            $count = $model_voucher->getVoucherCount($where);
            $array = array();
            if ($count > self::EXPORT_SIZE ){//显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=store_voucher&op=voucher_export');
                Tpl::showpage('store_export.excel');
            }else{  //如果数量小，直接下载
                $voucher_list = $model_voucher->getVoucherList($where, '*', self::EXPORT_SIZE, 0, 'voucher_owner_id asc,voucher_state asc,voucher_id asc');
                $this->createExcel($voucher_list, $t_info);
            }
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $voucher_list = $model_voucher->getVoucherList($where, '*', "{$limit1},{$limit2}", 0, 'voucher_owner_id asc,voucher_state asc,voucher_id asc');
            $this->createExcel($voucher_list, $t_info);
        }
    }
    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array(), $t_info){
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'代金券编码');
        if($t_info['voucher_t_gettype'] == 2){
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'卡密');
        }
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'代金券名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'有效期');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'面额');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单限额');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'所属会员');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'领取时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'使用状态');
        //代金券模板名称
        $voucher_t_title = '';
        //data
        $model_voucher = Model('voucher');
        $voucherstate_arr = $model_voucher->getVoucherStateArray();
        foreach ((array)$data as $k=>$info){
            $voucher_t_title = $info['voucher_title'];
            $info['voucher_pwd'] = $model_voucher->get_voucher_pwd($info['voucher_pwd2']);
            $tmp = array();
            $tmp[] = array('data'=>$info['voucher_code']);
            if($t_info['voucher_t_gettype'] == 2){
                $tmp[] = array('data' => $info['voucher_pwd']);
            }
            $tmp[] = array('data'=>$info['voucher_title']);
            $info['expirydatetext'] = date('Y-m-d',$info['voucher_start_date']).'~'.date('Y-m-d',$info['voucher_end_date']);
            $tmp[] = array('data'=>$info['expirydatetext']);
            $tmp[] = array('data'=>$info['voucher_price']);
            $tmp[] = array('data'=>$info['voucher_limit']);
            $tmp[] = array('data'=>($info['voucher_owner_name']?$info['voucher_owner_name']:''));
            if($info['voucher_owner_id'] > 0){
                $info['voucher_active_date'] = @date('Y-m-d H:i:s',$info['voucher_active_date']);
            } else {
                $info['voucher_active_date'] = '';
            }
            $tmp[] = array('data'=>$info['voucher_active_date']);
            $info['voucher_state_text'] = $voucherstate_arr[$info['voucher_state']];;
            $tmp[] = array('data'=>$info['voucher_state_text']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset("代金券模板($voucher_t_title)",CHARSET));
        $excel_obj->generateXML($excel_obj->charset("代金券模板($voucher_t_title)的代金券及其卡密",CHARSET).$_GET['curpage']);
    }

    /**
     * 购买套餐
     */
    public function quotaaddOp(){
        if (chksubmit()){
            $quota_quantity = intval($_POST['quota_quantity']);
            if($quota_quantity <= 0 || $quota_quantity > 12) {
                showDialog(Language::get('voucher_apply_num_error'));
            }
            //获取当前价格
            $current_price = intval(C('promotion_voucher_price'));

            $model = Model();
            $model_voucher = Model('voucher');

            //获取该用户已有套餐
            $current_quota = $model_voucher->getCurrentQuota($_SESSION['store_id']);
            $add_time = 86400 *30 * $quota_quantity;
            if(empty($current_quota)) {
                //生成套餐
                $param = array();
                $param['quota_memberid'] = $_SESSION['member_id'];
                $param['quota_membername'] = $_SESSION['member_name'];
                $param['quota_storeid'] = $_SESSION['store_id'];
                $param['quota_storename'] = $_SESSION['store_name'];
                $param['quota_starttime'] = TIMESTAMP;
                $param['quota_endtime'] = TIMESTAMP + $add_time;
                $param['quota_state'] = 1;
                $reault = $model->table('voucher_quota')->insert($param);
            } else {
                $param = array();
                $param['quota_endtime'] = array('exp', 'quota_endtime + ' . $add_time);
                $reault = $model->table('voucher_quota')->where(array('quota_id'=>$current_quota['quota_id']))->update($param);
            }

            //记录店铺费用
            $this->recordStoreCost($current_price * $quota_quantity, '购买代金券套餐');

            $this->recordSellerLog('购买'.$quota_quantity.'份代金券套餐，单价'.$current_price.L('nc_yuan'));

            if($reault){
                showDialog(Language::get('voucher_apply_buy_succ'),'index.php?act=store_voucher&op=quotalist','succ');
            } else {
                showDialog(Language::get('nc_common_op_fail'),'index.php?act=store_voucher&op=quotalist');
            }
        }else {
            //输出导航
            self::profile_menu('quota_add','quotaadd');
            Tpl::showpage('store_voucher_quota.add');
        }
    }
    /*
     * 代金券模版添加
     */
    public function templateaddOp(){
        $model = Model('voucher');
        if ($isOwnShop = checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            //查询当前可以套餐
            $quotainfo = $model->getCurrentQuota($_SESSION['store_id']);
            if(empty($quotainfo)){
                showMessage(Language::get('voucher_template_quotanull'),'index.php?act=store_voucher&op=quotaadd','html','error');
            }

            //查询该套餐下代金券模板列表
            $count = $model->table('voucher_template')->where(array('voucher_t_quotaid'=>$quotainfo['quota_id'],'voucher_t_state'=>$this->templatestate_arr['usable'][0]))->count();
            if ($count >= C('promotion_voucher_storetimes_limit')){
                $message = sprintf(Language::get('voucher_template_noresidual'),C('promotion_voucher_storetimes_limit'));
                showMessage($message,'index.php?act=store_voucher&op=templatelist','html','error');
            }
        }

        //查询面额列表
        $pricelist =  $model->table('voucher_price')->order('voucher_price asc')->select();
        if(empty($pricelist)){
            showMessage(Language::get('voucher_template_pricelisterror'),'index.php?act=store_voucher&op=templatelist','html','error');
        }
        //获取领取方式
        $gettype_array = $model->getVoucherGettypeArray();
        //会员级别
        $member_grade = Model('member')->getMemberGradeArr();
        
        if(chksubmit()){
            //验证提交的内容面额不能大于限额
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST['txt_template_title'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('voucher_template_title_error')),
                array("input"=>$_POST['txt_template_total'], "require"=>"true","validator"=>"Number","min"=>"1","message"=>Language::get('voucher_template_total_error')),
                array("input"=>$_POST['select_template_price'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_price_error')),
                array("input"=>$_POST['txt_template_limit'], "require"=>"true","validator"=>"Double","message"=>Language::get('voucher_template_limit_error')),
                array("input"=>$_POST['txt_template_describe'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"255","message"=>Language::get('voucher_template_describe_error')),
                array("input"=>$_POST['gettype_sel'], "require"=>"true","message"=>'请选择领取方式'),
            );
            $error = $obj_validate->validate();
            //金额验证
            $price = intval($_POST['select_template_price'])>0?intval($_POST['select_template_price']):0;
            foreach($pricelist as $k=>$v){
                if($v['voucher_price'] == $price){
                    $chooseprice = $v;//取得当前选择的面额记录
                }
            }
            if(empty($chooseprice)){
                $error.=Language::get('voucher_template_pricelisterror');
            }
            $limit = floatval($_POST['txt_template_limit'])>0?floatval($_POST['txt_template_limit']):0;
            if($price>=$limit) $error.=Language::get('voucher_template_price_error');
            //验证卡密代金券发放数量
            $gettype = trim($_POST['gettype_sel']);
            if($gettype == 'pwd'){
                if (intval($_POST['txt_template_total']) > 1000){
                    $error.= '领取方式为卡密兑换的代金券，发放总数不能超过1000张';
                }
            }
            if ($error){
                showDialog($error,'','error');
            }else {
                $insert_arr = array();
                $insert_arr['voucher_t_title'] = trim($_POST['txt_template_title']);
                $insert_arr['voucher_t_desc'] = trim($_POST['txt_template_describe']);
                $insert_arr['voucher_t_start_date'] = time();//默认代金券模板的有效期为当前时间
                if ($_POST['txt_template_enddate']){
                    $enddate = strtotime($_POST['txt_template_enddate']);
                    if (!$isOwnShop && $enddate > $quotainfo['quota_endtime']){
                        $enddate = $quotainfo['quota_endtime'];
                    }
                    $insert_arr['voucher_t_end_date'] = $enddate;
                }else {//如果没有添加有效期则默认为套餐的结束时间
                    if ($isOwnShop)
                        $insert_arr['voucher_t_end_date'] = time() + 2592000; // 自营店 默认30天到期
                    else
                        $insert_arr['voucher_t_end_date'] = $quotainfo['quota_endtime'];
                }
                $insert_arr['voucher_t_price'] = $price;
                $insert_arr['voucher_t_limit'] = $limit;
                $insert_arr['voucher_t_store_id'] = $_SESSION['store_id'];
                $insert_arr['voucher_t_storename'] = $_SESSION['store_name'];
                $insert_arr['voucher_t_sc_id'] = intval($_POST['sc_id']);
                $insert_arr['voucher_t_creator_id'] = $_SESSION['member_id'];
                $insert_arr['voucher_t_state'] = $this->templatestate_arr['usable'][0];
                $insert_arr['voucher_t_total'] = intval($_POST['txt_template_total'])>0?intval($_POST['txt_template_total']):0;
                $insert_arr['voucher_t_giveout'] = 0;
                $insert_arr['voucher_t_used'] = 0;
                $insert_arr['voucher_t_add_date'] = time();
                $insert_arr['voucher_t_quotaid'] = $quotainfo['quota_id'] ? $quotainfo['quota_id'] : 0;
                $insert_arr['voucher_t_points'] = ($gettype == 'points'?$chooseprice['voucher_defaultpoints']:0);
                $insert_arr['voucher_t_eachlimit'] = intval($_POST['eachlimit'])>0?intval($_POST['eachlimit']):0;
                //自定义图片
                if (!empty($_FILES['customimg']['name'])){
                    $upload = new UploadFile();
                    $upload->set('default_dir', ATTACH_VOUCHER.DS.$_SESSION['store_id']);
                    $upload->set('thumb_width','160');
                    $upload->set('thumb_height','160');
                    $upload->set('thumb_ext','_small');
                    $result = $upload->upfile('customimg');
                    if ($result){
                        $insert_arr['voucher_t_customimg'] =  $upload->file_name;
                    }
                }
                //领取方式
                $insert_arr['voucher_t_gettype'] = in_array($gettype,array_keys($gettype_array))?$gettype_array[$gettype]['sign']:$gettype_array[$model::VOUCHER_GETTYPE_DEFAULT]['sign'];
                $insert_arr['voucher_t_isbuild'] = 0;
                //会员级别
                $mgrade_limit = intval($_POST['mgrade_limit']);
                $insert_arr['voucher_t_mgradelimit'] = in_array($mgrade_limit,array_keys($member_grade))?$mgrade_limit:$member_grade[0]['level'];
                
                $rs = $model->table('voucher_template')->insert($insert_arr);
                if($rs){
                    //生成卡密代金券
                    if($gettype == 'pwd'){
                        QueueClient::push('buildPwdvoucher', $rs);
                    }
                    showDialog(Language::get('nc_common_save_succ'),'index.php?act=store_voucher&op=templatelist','succ');
                }else{
                    showDialog(Language::get('nc_common_save_fail'),'index.php?act=store_voucher&op=templatelist','error');
                }
            }
        }else{
            //店铺分类
            $store_class = rkcache('store_class', true);
            Tpl::output('store_class', $store_class);
            //查询店铺详情
            $store_info = Model('store')->getStoreInfoByID($_SESSION['store_id']);
            TPL::output('store_info',$store_info);

            //领取方式
            TPL::output('gettype_arr',$gettype_array);
            //会员级别
            Tpl::output('member_grade',$member_grade);
            TPL::output('type','add');
            TPL::output('quotainfo',$quotainfo);
            TPL::output('pricelist',$pricelist);
            $this->profile_menu('template','templateadd');
            Tpl::showpage('store_voucher_template.add');
        }
    }
    /*
     * 代金券模版编辑
     */
    public function templateeditOp(){
        $t_id = intval($_GET['tid']);
        if ($t_id <= 0){
            $t_id = intval($_POST['tid']);
        }
        if ($t_id <= 0){
            showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');
        }
        $model = Model('voucher');
        //查询模板信息
        $param = array();
        $param['voucher_t_id'] = $t_id;
        $param['voucher_t_store_id'] = $_SESSION['store_id'];
        $param['voucher_t_state'] = $this->templatestate_arr['usable'][0];
        $param['voucher_t_giveout'] = array('elt','0');
        $param['voucher_t_end_date'] = array('gt',time());
        $t_info = $model->table('voucher_template')->where($param)->find();
        if (empty($t_info)){
            showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');
        }

        if ($isOwnShop = checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            //查询套餐信息
            $quotainfo = $model->table('voucher_quota')->where(array('quota_id'=>$t_info['voucher_t_quotaid'],'quota_storeid'=>$_SESSION['store_id']))->find();
            if(empty($quotainfo)){
                showMessage(Language::get('voucher_template_quotanull'),'index.php?act=store_voucher&op=quotaadd','html','error');
            }
        }

        //查询面额列表
        $pricelist =  $model->table('voucher_price')->order('voucher_price asc')->select();
        if(empty($pricelist)){
            showMessage(Language::get('voucher_template_pricelisterror'),'index.php?act=store_voucher&op=templatelist','html','error');
        }
        //获取领取方式
        $gettype_array = $model->getVoucherGettypeArray();
        //会员级别
        $member_grade = Model('member')->getMemberGradeArr();
        
        if(chksubmit()){
            //验证提交的内容面额不能大于限额
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST['txt_template_title'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>Language::get('voucher_template_title_error')),
                array("input"=>$_POST['txt_template_total'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_total_error')),
                array("input"=>$_POST['select_template_price'], "require"=>"true","validator"=>"Number","message"=>Language::get('voucher_template_price_error')),
                array("input"=>$_POST['txt_template_limit'], "require"=>"true","validator"=>"Double","message"=>Language::get('voucher_template_limit_error')),
                array("input"=>$_POST['txt_template_describe'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"255","message"=>Language::get('voucher_template_describe_error')),
                array("input"=>$_POST['gettype_sel'], "require"=>"true","message"=>'请选择领取方式'),
            );
            $error = $obj_validate->validate();
            //金额验证
            $price = intval($_POST['select_template_price'])>0?intval($_POST['select_template_price']):0;
            foreach($pricelist as $k=>$v){
                if($v['voucher_price'] == $price){
                    $chooseprice = $v;//取得当前选择的面额记录
                }
            }
            if(empty($chooseprice)){
                $error.=Language::get('voucher_template_pricelisterror');
            }
            $limit = floatval($_POST['txt_template_limit'])>0?floatval($_POST['txt_template_limit']):0;
            if($price>=$limit) $error.=Language::get('voucher_template_price_error');
            //验证卡密代金券发放数量
            $gettype = trim($_POST['gettype_sel']);
            if($gettype == 'pwd'){
                if (intval($_POST['txt_template_total']) > 1000){
                    $error.= '领取方式为卡密兑换的代金券，发放总数不能超过1000张';
                }
            }
            if ($error){
                showDialog($error,'reload','error');
            }else {
                $update_arr = array();
                $update_arr['voucher_t_title'] = trim($_POST['txt_template_title']);
                $update_arr['voucher_t_desc'] = trim($_POST['txt_template_describe']);
                if ($_POST['txt_template_enddate']){
                    $enddate = strtotime($_POST['txt_template_enddate']);
                    if (!$isOwnShop && $enddate > $quotainfo['quota_endtime']){
                        $enddate = $quotainfo['quota_endtime'];
                    }
                    $update_arr['voucher_t_end_date'] = $enddate;
                }else {//如果没有添加有效期则默认为套餐的结束时间
                    if ($isOwnShop)
                        $update_arr['voucher_t_end_date'] = time() + 2592000; // 自营店 默认30天到期
                    else
                        $update_arr['voucher_t_end_date'] = $quotainfo['quota_endtime'];
                }
                $update_arr['voucher_t_price'] = $price;
                $update_arr['voucher_t_limit'] = $limit;
                $update_arr['voucher_t_sc_id'] = intval($_POST['sc_id']);
                $update_arr['voucher_t_state'] = intval($_POST['tstate']) == $this->templatestate_arr['usable'][0]?$this->templatestate_arr['usable'][0]:$this->templatestate_arr['disabled'][0];
                $update_arr['voucher_t_total'] = intval($_POST['txt_template_total'])>0?intval($_POST['txt_template_total']):0;
                $update_arr['voucher_t_add_date'] = time();
                $update_arr['voucher_t_points'] = $gettype == 'points'?$chooseprice['voucher_defaultpoints']:0;
                $update_arr['voucher_t_eachlimit'] = intval($_POST['eachlimit'])>0?intval($_POST['eachlimit']):0;
                //自定义图片
                if (!empty($_FILES['customimg']['name'])){
                    $upload = new UploadFile();
                    $upload->set('default_dir', ATTACH_VOUCHER.DS.$_SESSION['store_id']);
                    $upload->set('thumb_width','160');
                    $upload->set('thumb_height','160');
                    $upload->set('thumb_ext','_small');
                    $result = $upload->upfile('customimg');
                    if ($result){
                        //删除原图
                        if (!empty($t_info['voucher_t_customimg'])){//如果模板存在，则删除原模板图片
                            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg']);
                            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.str_ireplace('.', '_small.', $t_info['voucher_t_customimg']));
                        }
                        $update_arr['voucher_t_customimg'] =  $upload->file_name;
                    }
                }
                //领取方式
                $update_arr['voucher_t_gettype'] = in_array($gettype,array_keys($gettype_array))?$gettype_array[$gettype]['sign']:$gettype_array[$model::VOUCHER_GETTYPE_DEFAULT]['sign'];
                //会员级别
                $mgrade_limit = intval($_POST['mgrade_limit']);
                $update_arr['voucher_t_mgradelimit'] = in_array($mgrade_limit,array_keys($member_grade))?$mgrade_limit:$member_grade[0]['level'];
                
                $rs = $model->table('voucher_template')->where(array('voucher_t_id'=>$t_info['voucher_t_id']))->update($update_arr);
                if($rs){
                    //生成卡密代金券
                    if($gettype == 'pwd'){
                        QueueClient::push('buildPwdvoucher', $t_info['voucher_t_id']);
                    }
                    showDialog(Language::get('nc_common_op_succ'),'index.php?act=store_voucher&op=templatelist','succ');
                }else{
                    showDialog(Language::get('nc_common_op_fail'),'index.php?act=store_voucher&op=templatelist','error');
                }
            }
        }else{
            if (!$t_info['voucher_t_customimg'] || !file_exists(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg'])){
                $t_info['voucher_t_customimg'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
            }else{
                $t_info['voucher_t_customimg'] = UPLOAD_SITE_URL.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.str_ireplace('.', '_small.', $t_info['voucher_t_customimg']);
            }
            TPL::output('type','edit');
            TPL::output('t_info',$t_info);

            //店铺分类
            $store_class = rkcache('store_class', true);
            Tpl::output('store_class', $store_class);
            //查询店铺详情
            $store_info = Model('store')->getStoreInfoByID($_SESSION['store_id']);
            TPL::output('store_info',$store_info);
            TPL::output('gettype_arr',$gettype_array);
            Tpl::output('member_grade',$member_grade);
            TPL::output('quotainfo',$quotainfo);
            TPL::output('pricelist',$pricelist);
            $this->profile_menu('templateedit','templateedit');
            Tpl::showpage('store_voucher_template.add');
        }
    }
    /**
     * 删除代金券
     */
    public function templatedelOp(){
        $t_id = intval($_GET['tid']);
        if ($t_id <= 0){
            showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');
        }
        $model = Model();
        //查询模板信息
        $param = array();
        $param['voucher_t_id'] = $t_id;
        $param['voucher_t_store_id'] = $_SESSION['store_id'];
        $param['voucher_t_giveout'] = array('elt','0');//会员没领取过代金券才可删除
        $t_info = $model->table('voucher_template')->where($param)->find();
        if (empty($t_info)){
            showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');
        }
        $rs = $model->table('voucher_template')->where(array('voucher_t_id'=>$t_info['voucher_t_id']))->delete();
        if ($rs){
            //删除自定义的图片
            if (trim($t_info['voucher_t_customimg'])){
                @unlink(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.$t_info['voucher_t_customimg']);
                @unlink(BASE_UPLOAD_PATH.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.str_ireplace('.', '_small.', $t_info['voucher_t_customimg']));
            }
            showDialog(Language::get('nc_common_del_succ'),'reload','succ');
        }else {
            showDialog(Language::get('nc_common_del_fail'));
        }
    }
    /**
     * 查看代金券详细
     */
    public function templateinfoOp(){
        $t_id = intval($_GET['tid']);
        if ($t_id <= 0){
            showMessage(Language::get('wrong_argument'),'index.php?act=store_voucher&op=templatelist','html','error');
        }
        $model_voucher = Model('voucher');
        //查询模板信息
        $where = array();
        $where['voucher_t_id'] = $t_id;
        $where['voucher_t_store_id'] = $_SESSION['store_id'];
        $t_info = $model_voucher->getVoucherTemplateInfo($where);
        TPL::output('t_info',$t_info);
        $this->profile_menu('templateinfo','templateinfo');
        Tpl::showpage('store_voucher_template.info');
    }
    /*
     * 把代金券模版设为失效
     */
    private function check_voucher_template_expire($voucher_template_id=''){
        $where_array = array();
        if(empty($voucher_template_id)) {
            $where_array['voucher_t_end_date'] = array('lt',time());
        } else {
            $where_array['voucher_t_id'] = $voucher_template_id;
        }
        $where_array['voucher_t_state'] = $this->templatestate_arr['usable'][0];
        $model = Model();
        $model->table('voucher_template')->where($where_array)->update(array('voucher_t_state'=>$this->templatestate_arr['disabled'][0]));
    }
    /**
     * 生成卡密代金券
     */
    public function bulidvoucherOp(){
        $t_id = intval($_GET['tid']);
    	if ($t_id <= 0){
    	    showDialog('代金券生成失败','','error');
    	}
    	//生成卡密代金券队列
    	QueueClient::push('buildPwdvoucher', $t_id);
    	showDialog('生成代金券卡密任务已建立，稍后将生成','reload','succ');
    }
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        Language::read('member_layout');
        $menu_array = array();
        switch ($menu_type) {
            case 'voucher':
                $menu_array = array(
                1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'), 'menu_url'=>'index.php?act=store_voucher&op=templatelist'),
                );
                break;
            case 'quota_add':
                $menu_array = array(
                1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'), 'menu_url'=>'index.php?act=store_voucher&op=templatelist'),
                4=>array('menu_key'=>'quotaadd','menu_name'=>Language::get('voucher_applyadd'), 'menu_url'=>'index.php?act=store_voucher&op=quotaadd')
                );
                break;
            case 'template':
                $menu_array = array(
                1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'), 'menu_url'=>'index.php?act=store_voucher&op=templatelist'),
                2=>array('menu_key'=>'templateadd','menu_name'=>Language::get('voucher_templateadd'),   'menu_url'=>'index.php?act=store_voucher&op=templateadd'),
                );
                break;
            case 'templateedit':
                $menu_array = array(
                1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'), 'menu_url'=>'index.php?act=store_voucher&op=templatelist'),
                2=>array('menu_key'=>'templateedit','menu_name'=>Language::get('voucher_templateedit'), 'menu_url'=>''),
                );
                break;
            case 'templateinfo':
                $menu_array = array(
                1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('nc_member_path_store_voucher'), 'menu_url'=>'index.php?act=store_voucher&op=templatelist'),
                2=>array('menu_key'=>'templateinfo','menu_name'=>Language::get('voucher_templateinfo'), 'menu_url'=>''),
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
