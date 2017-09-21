<?php
/**
 * 代金券管理
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class voucherControl extends SystemControl{
    const SECONDS_OF_30DAY = 2592000;
    private $applystate_arr;
    private $quotastate_arr;
    private $templatestate_arr;

    public function __construct(){
        parent::__construct();
        Language::read('voucher');
        if (C('voucher_allow') != 1 || C('points_isuse')!=1){
            showMessage(Language::get('admin_voucher_unavailable'),'index.php?act=operation&op=point','html','succ',1,4000);
        }
        $this->applystate_arr = array('new'=>array(1,Language::get('admin_voucher_applystate_new')),'verify'=>array(2,Language::get('admin_voucher_applystate_verify')),'cancel'=>array(3,Language::get('admin_voucher_applystate_cancel')));
        $this->quotastate_arr = array('activity'=>array(1,Language::get('admin_voucher_quotastate_activity')),'cancel'=>array(2,Language::get('admin_voucher_quotastate_cancel')),'expire'=>array(3,Language::get('admin_voucher_quotastate_expire')));
        //代金券模板状态
        $this->templatestate_arr = array('usable'=>array(1,Language::get('admin_voucher_templatestate_usable')),'disabled'=>array(2,Language::get('admin_voucher_templatestate_disabled')));
        Tpl::output('applystate_arr',$this->applystate_arr);
        Tpl::output('quotastate_arr',$this->quotastate_arr);
        Tpl::output('templatestate_arr',$this->templatestate_arr);
    }

    /*
     * 默认操作列出代金券
     */
    public function indexOp(){
        $this->templatelistOp();
    }

    /**
     * 代金券设置
     */
    public function settingOp(){
        $setting_model = Model('setting');
        if (chksubmit()){
            $obj_validate = new Validate();
            $validate_arr[] = array('input'=>$_POST['promotion_voucher_price'],'require'=>'true','validator'=>'IntegerPositive','message'=>Language::get('admin_voucher_setting_price_error'));
            $validate_arr[] = array('input'=>$_POST['promotion_voucher_storetimes_limit'],'require'=>'true','validator'=>'IntegerPositive','message'=>Language::get('admin_voucher_setting_storetimes_error'));
            $validate_arr[] = array('input'=>$_POST['promotion_voucher_buyertimes_limit'],'require'=>'true','validator'=>'IntegerPositive','message'=>Language::get('admin_voucher_setting_buyertimes_error'));

            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage(Language::get('error').$error,'','','error');
            }
            //每月代金劵软件服务单价
            $promotion_voucher_price = intval($_POST['promotion_voucher_price']);
            if($promotion_voucher_price < 0) {
                $promotion_voucher_price = 20;
            }
            //每月店铺可以发布的代金劵数量
            $promotion_voucher_storetimes_limit = intval($_POST['promotion_voucher_storetimes_limit']);
            if($promotion_voucher_storetimes_limit <= 0) {
                $promotion_voucher_storetimes_limit = 20;
            }
            //买家可以领取的代金劵总数
            $promotion_voucher_buyertimes_limit = intval($_POST['promotion_voucher_buyertimes_limit']);
            if($promotion_voucher_buyertimes_limit <= 0) {
                $promotion_voucher_buyertimes_limit = 5;
            }
            $update_array = array();
            $update_array['promotion_voucher_price'] = $promotion_voucher_price;
            $update_array['promotion_voucher_storetimes_limit'] = $promotion_voucher_storetimes_limit;
            $update_array['promotion_voucher_buyertimes_limit'] = $promotion_voucher_buyertimes_limit;
            $result = $setting_model->updateSetting($update_array);
            if ($result){
                $this->log(L('admin_voucher_setting,nc_voucher_price_manage'));
                showMessage(Language::get('nc_common_save_succ'),'');
            }else {
                showMessage(Language::get('nc_common_save_fail'),'');
            }
        } else {
            $setting = $setting_model->GetListSetting();
            $this->show_menu('voucher','setting');
            Tpl::output('setting',$setting);
			Tpl::setDirquna('shop');
            Tpl::showpage('voucher.setting');
        }
    }

    /**
     * 代金券面额列表
     */
    public function pricelistOp()
    {
        $this->show_menu('voucher','pricelist');
		Tpl::setDirquna('shop');
        Tpl::showpage('voucher.pricelist');
    }

    /**
     * 代金券面额列表XML
     */
    public function pricelist_xmlOp()
    {
        $model = Model();
        $list = (array) $model->table('voucher_price')
            ->order('voucher_price asc')
            ->page($_REQUEST['rp'])
            ->select();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($list as $val) {
            $o = '<a class="confirm-del-on-click btn red" href="' . urlAdminShop('voucher', 'pricedrop', array(
                'voucher_price_id' => $val['voucher_price_id'],
            )) . '"><i class="fa fa-trash"></i>删除</a>';
            $o .= '<a class="btn green" href="' . urlAdminShop('voucher', 'priceedit', array(
                'priceid' => $val['voucher_price_id'],
            )) . '"><i class="fa fa-list-alt"></i>编辑</a>';

            $i = array();
            $i['operation'] = $o;

            $i['voucher_price'] = $val['voucher_price'];
            $i['voucher_price_describe'] = $val['voucher_price_describe'];
            $i['voucher_defaultpoints'] = $val['voucher_defaultpoints'];

            $data['list'][$val['voucher_price_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /*
     * 添加代金券面额页面
     */
    public function priceaddOp(){
        if (chksubmit()){
            $obj_validate = new Validate();
            $validate_arr[] = array('input'=>$_POST['voucher_price'],'require'=>'true','validator'=>'IntegerPositive','message'=>Language::get('admin_voucher_price_error'));
            $validate_arr[] = array('input'=>$_POST['voucher_price_describe'],'require'=>'true','message'=>Language::get('admin_voucher_price_describe_error'));
            $validate_arr[] = array('input'=>$_POST['voucher_points'],'require'=>'true','validator'=>'IntegerPositive','message'=>Language::get('admin_voucher_price_points_error'));
            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            //验证面额是否存在
            $voucher_price = intval($_POST['voucher_price']);
            $voucher_points = intval($_POST['voucher_points']);
            $model = Model();
            $voucherprice_info = $model->table('voucher_price')->where(array('voucher_price'=>$voucher_price))->find();
            if(!empty($voucherprice_info)) {
                $error .= Language::get('admin_voucher_price_exist');
            }
            if ($error != ''){
                showMessage($error);
            }
            else {
                //保存
                $insert_arr = array(
                    'voucher_price_describe'=>trim($_POST['voucher_price_describe']),
                    'voucher_price'=>$voucher_price,
                    'voucher_defaultpoints'=>$voucher_points,
                );
                $rs = $model->table('voucher_price')->insert($insert_arr);
                if ($rs){
                    $this->log(L('nc_add,admin_voucher_priceadd').'['.$_POST['voucher_price'].']');
                    showMessage(Language::get('nc_common_save_succ'),'index.php?act=voucher&op=pricelist');
                }else {
                    showMessage(Language::get('nc_common_save_fail'),'index.php?act=voucher&op=priceadd');
                }
            }
        }else {
            $this->show_menu('voucher','priceadd');
			Tpl::setDirquna('shop');
            Tpl::showpage('voucher.priceadd') ;
        }
    }

    /*
     * 编辑代金券面额
     */
    public function priceeditOp(){
        $id = intval($_GET['priceid']);
        if ($id <= 0){
            $id = intval($_POST['priceid']);
        }
        if ($id <= 0){
            showMessage(Language::get('param_error'),'index.php?act=voucher&op=pricelist');
        }
        $model = Model();
        if (chksubmit()){
            $obj_validate = new Validate();
            $validate_arr[] = array('input'=>$_POST['voucher_price'],'require'=>'true','validator'=>'IntegerPositive','message'=>Language::get('admin_voucher_price_error'));
            $validate_arr[] = array('input'=>$_POST['voucher_price_describe'],'require'=>'true','message'=>Language::get('admin_voucher_price_describe_error'));
            $validate_arr[] = array('input'=>$_POST['voucher_points'],'require'=>'true','validator'=>'IntegerPositive','message'=>Language::get('admin_voucher_price_points_error'));
            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            //验证面额是否存在
            $voucher_price = intval($_POST['voucher_price']);
            $voucher_points = intval($_POST['voucher_points']);
            $voucherprice_info = $model->table('voucher_price')->where(array('voucher_price'=>$voucher_price,'voucher_price_id'=>array('neq',$id)))->find();
            if(!empty($voucherprice_info)) {
                $error .= Language::get('admin_voucher_price_exist');
            }
            if ($error != ''){
                showMessage($error,'','','error');
            }else {
                $update_arr = array();
                $update_arr['voucher_price_describe'] = trim($_POST['voucher_price_describe']);
                $update_arr['voucher_price'] = $voucher_price;
                $update_arr['voucher_defaultpoints'] = $voucher_points;
                $rs = $model->table('voucher_price')->where(array('voucher_price_id'=>$id))->update($update_arr);
                if ($rs){
                    $this->log(L('nc_edit,admin_voucher_priceadd').'['.$_POST['voucher_price'].']');
                    showMessage(Language::get('nc_common_save_succ'),'index.php?act=voucher&op=pricelist');
                }else {
                    showMessage(Language::get('nc_common_save_fail'),'index.php?act=voucher&op=pricelist');
                }
            }
        }else {
            $voucherprice_info = $model->table('voucher_price')->where(array('voucher_price_id'=>$id))->find();
            if (empty($voucherprice_info)){
                showMessage(Language::get('param_error'),'index.php?act=voucher&op=pricelist');
            }
            Tpl::output('info',$voucherprice_info);
            $this->show_menu('priceedit','priceedit');
			Tpl::setDirquna('shop');
            Tpl::showpage('voucher.priceadd');
        }
    }

    /*
     * 删除代金券面额
     */
    public function pricedropOp(){
        $voucher_price_id = trim($_REQUEST['voucher_price_id']);
        if(empty($voucher_price_id)) {
            showMessage(Language::get('param_error'),'index.php?act=voucher&op=pricelist');
        }
        $model = Model();
        $rs = $model->table('voucher_price')->where(array('voucher_price_id'=>array('in',$voucher_price_id)))->delete();
        if ($rs){
            $this->log(L('nc_del,admin_voucher_priceadd').'[ID:'.$voucher_price_id.']');
            showMessage(Language::get('nc_common_del_succ'),'index.php?act=voucher&op=pricelist');
        }else{
            showMessage(Language::get('nc_common_del_fail'),'index.php?act=voucher&op=pricelist');
        }
    }

    /**
     * 套餐管理
     */
    public function quotalistOp(){
        $model = Model();
        //更新过期套餐的状态
        $time = time();
        $model->table('voucher_quota')->where(array('quota_endtime'=>array('lt',$time),'quota_state'=>"{$this->quotastate_arr['activity'][0]}"))->update(array('quota_state'=>$this->quotastate_arr['expire'][0]));

        $this->show_menu('voucher', 'quotalist');
		Tpl::setDirquna('shop');
        Tpl::showpage('voucher.quotalist');
    }

    /**
     * 套餐管理XML
     */
    public function quotalist_xmlOp()
    {
        $condition = array();

        if (strlen($q = trim($_REQUEST['query']))) {
            switch ($_REQUEST['qtype']) {
                case 'quota_storename':
                    $condition['quota_storename'] = array('like', '%'.$q.'%');
                    break;
            }
        }

        $model = Model();
        $list = (array) $model->table('voucher_quota')
            ->where($condition)
            ->order('quota_id desc')
            ->page($_REQUEST['rp'])
            ->select();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($list as $val) {
            $i = array();
            $i['operation'] = '<span>--</span>';

            $i['quota_storename'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id' => $val['quota_storeid'],
            )) . '">' . $val['quota_storename'] . '</a>';

            $i['start_time_text'] = date("Y-m-d", $val['quota_starttime']);
            $i['end_time_text'] = date("Y-m-d", $val['quota_endtime']);

            $data['list'][$val['quota_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 代金券列表
     */
    public function templatelistOp()
    {
        $model_voucher = Model('voucher');
        //领取方式
        $gettype_arr = $model_voucher->getVoucherGettypeArray();
        //状态
        $templateState = $model_voucher->getTemplateState();
        TPL::output('gettype_arr',$gettype_arr);
        TPL::output('templateState',$templateState);
        $this->show_menu('voucher', 'templatelist');
		Tpl::setDirquna('shop');
        Tpl::showpage('voucher.templatelist');
    }

    /**
     * 代金券列表XML
     */
    public function templatelist_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['voucher_t_title']))) {
                $condition['voucher_t_title'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['voucher_t_storename']))) {
                $condition['voucher_t_storename'] = array('like', '%' . $q . '%');
            }
            if (($q = (int) $_REQUEST['voucher_t_gettype']) > 0) {
                $condition['voucher_t_gettype'] = $q;
            }
            if (($q = (int) $_REQUEST['voucher_t_state']) > 0) {
                $condition['voucher_t_state'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['voucher_t_recommend']))) {
                $condition['voucher_t_recommend'] = (int) $q;
            }

            if (trim($_GET['sdate']) && trim($_GET['edate'])) {
                $sdate = strtotime($_GET['sdate']);
                $edate = strtotime($_GET['edate']);
                $condition['voucher_t_add_date'] = array('between', "$sdate,$edate");
            } elseif (trim($_GET['sdate'])) {
                $sdate = strtotime($_GET['sdate']);
                $condition['voucher_t_add_date'] = array('egt', $sdate);
            } elseif (trim($_GET['edate'])) {
                $edate = strtotime($_GET['edate']);
                $condition['voucher_t_add_date'] = array('elt', $edate);
            }

            $pdates = array();
            if (strlen($q = trim((string) $_REQUEST['pdate1'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "voucher_t_end_date >= {$q}";
            }
            if (strlen($q = trim((string) $_REQUEST['pdate2'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "voucher_t_start_date <= {$q}";
            }
            if ($pdates) {
                $condition['pdates'] = array(
                    'exp',
                    implode(' and ', $pdates),
                );
            }

        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'voucher_t_title':
                        $condition['voucher_t_title'] = array('like', '%'.$q.'%');
                        break;
                    case 'voucher_t_storename':
                        $condition['voucher_t_storename'] = array('like', '%'.$q.'%');
                        break;
                }
            }
        }

        switch ($_REQUEST['sortname']) {
            case 'voucher_t_price':
            case 'voucher_t_limit':
                $sort = $_REQUEST['sortname'];
                break;
            case 'add_time_text':
                $sort = 'voucher_t_add_date';
                break;
            case 'start_time_text':
                $sort = 'voucher_t_start_date';
                break;
            case 'end_time_text':
                $sort = 'voucher_t_end_date';
                break;
            case 'voucher_t_mgradelimittext':
                $sort = 'voucher_t_mgradelimit';
                break;                
            default:
                $sort = 'voucher_t_id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }

        $model = Model('voucher');
        $list = $model->getVoucherTemplateList($condition, '*', 0, $_REQUEST['rp'], 'voucher_t_state asc, ' . $sort);
        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        $gettype_arr = Model('voucher')->getVoucherGettypeArray();
        //会员级别
        $member_grade = Model('member')->getMemberGradeArr();
        foreach ($list as $val) {
            $o = '<a class="btn blue" href="' . urlAdminShop('voucher', 'templateedit', array(
                'tid' => $val['voucher_t_id'],
            )) . '"><i class="fa fa-pencil-square-o"></i>编辑</a>';

            $i = array();
            $i['operation'] = $o;
            $i['voucher_t_title'] = $val['voucher_t_title'];

            $i['voucher_t_storename'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id' => $val['voucher_t_store_id'],
            )) . '">' . $val['voucher_t_storename'] . '</a>';

            if (isset($flippedOwnShopIds[$val['voucher_t_store_id']])) {
                $i['voucher_t_storename'] .= '<span class="ownshop">[自营]</span>';
            }
            //代金券店铺分类
            $i['voucher_t_sc_name'] = $val['voucher_t_sc_name'];
            $i['voucher_t_price'] = $val['voucher_t_price'];
            $i['voucher_t_limit'] = $val['voucher_t_limit'];
            //会员级别
            $i['voucher_t_mgradelimittext'] = $val['voucher_t_mgradelimittext'];
            $i['add_time_text'] = date('Y-m-d H:i', $val['voucher_t_add_date']);
            $i['start_time_text'] = date('Y-m-d H:i', $val['voucher_t_start_date']);
            $i['end_time_text'] = date('Y-m-d H:i', $val['voucher_t_end_date']);
            //代金券领取方式
            $i['voucher_t_gettype_text'] = $val['voucher_t_gettype_text'];
            $i['voucher_t_state_text'] = $val['voucher_t_state_text'];
            
            $i['recommend'] = $val['voucher_t_recommend'] == '1'
                ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>'
                : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $data['list'][$val['voucher_t_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
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
            showMessage(Language::get('param_error'),'index.php?act=voucher&op=templatelist','','error');
        }
        $model = Model('voucher');
        //查询模板信息
        $param = array();
        $param['voucher_t_id'] = $t_id;
        $t_info = $model->getVoucherTemplateInfo($param);
        if (empty($t_info)){
            showMessage(Language::get('param_error'),'index.php?act=voucher&op=templatelist','html','error');
        }
        if(chksubmit()){
            $points = intval($_POST['points']);
            if ($points < 0){
                showMessage(Language::get('admin_voucher_template_points_error'),'','html','error');
            }
            $update_arr = array();
            $update_arr['voucher_t_points'] = $points;
            $update_arr['voucher_t_state'] = intval($_POST['tstate']) == $this->templatestate_arr['usable'][0]?$this->templatestate_arr['usable'][0]:$this->templatestate_arr['disabled'][0];
            $update_arr['voucher_t_recommend'] = intval($_POST['recommend'])==1?1:0;
            $rs = $model->table('voucher_template')->where(array('voucher_t_id'=>$t_info['voucher_t_id']))->update($update_arr);
            if($rs){
                $this->log(L('nc_edit,nc_voucher_price_manage,admin_voucher_styletemplate').'[ID:'.$t_id.']');
                showMessage(Language::get('nc_common_save_succ'),'index.php?act=voucher&op=templatelist','succ');
            }else{
                showMessage(Language::get('nc_common_save_fail'),'index.php?act=voucher&op=templatelist','error');
            }
        }else{            
            TPL::output('t_info',$t_info);
            $this->show_menu('templateedit','templateedit');
			Tpl::setDirquna('shop');
            Tpl::showpage('voucher.templateedit');
        }
    }

    /**
     * ajax操作
     */
    public function ajaxOp(){
        $model_voucher = Model('voucher');
        switch ($_GET['branch']){
            case 'voucher_t_recommend':
                $model_voucher->editVoucherTemplate(array('voucher_t_id' => intval($_GET['id'])), array($_GET['column'] => intval($_GET['value'])));
                $logtext = '';
                if (intval($_GET['value']) == 1){//推荐代金券
                    $logtext = '推荐代金券';
                } else {
                    $logtext = '取消推荐代金券';
                }
                $this->log($logtext.'[ID:'.intval($_GET['id']).']',1);
                echo 'true';exit;
                break;
        }
    }

    /**
     * 页面内导航菜单
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function show_menu($menu_type,$menu_key='') {
        $menu_array     = array();
        switch ($menu_type) {
            case 'voucher':
                $menu_array = array(
                3=>array('menu_key'=>'templatelist','menu_name'=>Language::get('admin_voucher_template_manage'), 'menu_url'=>'index.php?act=voucher&op=templatelist'),
                2=>array('menu_key'=>'quotalist','menu_name'=>Language::get('admin_voucher_quota_manage'), 'menu_url'=>'index.php?act=voucher&op=quotalist'),
                5=>array('menu_key'=>'pricelist','menu_name'=>Language::get('admin_voucher_pricemanage'), 'menu_url'=>'index.php?act=voucher&op=pricelist'),
                4=>array('menu_key'=>'setting','menu_name'=>Language::get('admin_voucher_setting'), 'menu_url'=>'index.php?act=voucher&op=setting'),
                );
                break;
            case 'priceedit':
                $menu_array = array(
                1=>array('menu_key'=>'setting','menu_name'=>Language::get('admin_voucher_setting'), 'menu_url'=>'index.php?act=voucher&op=setting'),
                2=>array('menu_key'=>'pricelist','menu_name'=>Language::get('admin_voucher_pricemanage'), 'menu_url'=>'index.php?act=voucher&op=pricelist'),
                3=>array('menu_key'=>'priceedit','menu_name'=>Language::get('admin_voucher_priceedit'), 'menu_url'=>'')
                );
                break;
            case 'templateedit':
                $menu_array = array(
                1=>array('menu_key'=>'templatelist','menu_name'=>Language::get('admin_voucher_template_manage'), 'menu_url'=>'index.php?act=voucher&op=templatelist'),
                2=>array('menu_key'=>'templateedit','menu_name'=>Language::get('admin_voucher_template_edit'), 'menu_url'=>'')
                );
                break;
        }
        Tpl::output('menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
