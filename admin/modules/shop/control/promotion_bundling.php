<?php
/**
 * 优惠套装管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class promotion_bundlingControl extends SystemControl{

    public function __construct(){
        parent::__construct();

        //读取语言包
        Language::read('promotion_bundling');

        //检查审核功能是否开启
        if (intval($_GET['promotion_allow']) !== 1 && intval(C('promotion_allow')) !== 1){
            $url = array(
                array(
                    'url'=>'index.php?act=setting',
                    'msg'=>Language::get('close'),
                ),
                array(
                    'url'=>'index.php?act=promotion_bundling&promotion_allow=1',
                    'msg'=>Language::get('open'),
                ),
            );
            showMessage(Language::get('promotion_unavailable'),$url,'html','succ',1,6000);
        }

        //自动开启优惠套装
        if (intval($_GET['promotion_allow']) === 1){
            $model_setting = Model('setting');
            $update_array = array();
            $update_array['promotion_allow'] = 1;
            $model_setting->updateSetting($update_array);
        }
    }

    /**
     * 默认Op
     */
    public function indexOp() {
        $this->bundling_listOp();
    }

    /**
     * 套餐管理
     */
    public function bundling_quotaOp()
    {
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_bundling_quota.list');
    }

    /**
     * 套餐管理XML
     */
    public function bundling_quota_xmlOp()
    {
        $condition = array();
        if (strlen($q = trim($_REQUEST['query']))) {
            switch ($_REQUEST['qtype']) {
                case 'store_name':
                    $condition['store_name'] = array('like', '%'.$q.'%');
                    break;
            }
        }

        $model_bundling = Model('p_bundling');
        $list = (array) $model_bundling->getBundlingQuotaList($condition, $_REQUEST['rp']);

        $data = array();
        $data['now_page'] = $model_bundling->shownowpage();
        $data['total_num'] = $model_bundling->gettotalnum();

        foreach ($list as $val) {
            $i = array();
            $i['operation'] = '<span>--</span>';

            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id' => $val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            $i['bl_quota_month'] = $val['bl_quota_month'];

            $i['start_time_text'] = date("Y-m-d", $val['bl_quota_starttime']);
            $i['end_time_text'] = date("Y-m-d", $val['bl_quota_endtime']);

            $i['state_text'] = $val['bl_state'] == '1' ? '开启' : '关闭';

            $data['list'][$val['bl_quota_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }


    /**
     * 活动管理
     */
    public function bundling_listOp()
    {
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_bundling.list');
    }

    /**
     * 活动管理XML
     */
    public function bundling_list_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['bl_name']))) {
                $condition['bl_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['store_name']))) {
                $condition['store_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['bl_state']))) {
                $condition['bl_state'] = (int) $q;
            }
        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'bl_name':
                        $condition['bl_name'] = array('like', '%'.$q.'%');
                        break;
                    case 'store_name':
                        $condition['store_name'] = array('like', '%'.$q.'%');
                        break;
                }
            }
        }

        switch ($_REQUEST['sortname']) {
            case 'bl_discount_price':
                $sort = $_REQUEST['sortname'];
                break;
            default:
                $sort = 'bl_id';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }

        $model_bundling = Model('p_bundling');
        $bundling_list = (array) $model_bundling->getBundlingList($condition, '*', $sort, $_REQUEST['rp']);
        $bundling_list = array_under_reset($bundling_list, 'bl_id');

        $data = array();
        $data['now_page'] = $model_bundling->shownowpage();
        $data['total_num'] = $model_bundling->gettotalnum();

        if (!empty($bundling_list)) {
            $blid_array = array_keys($bundling_list);
            $bgoods_array = $model_bundling->getBundlingGoodsList(array(
                'bl_id' => array('in', $blid_array),
            ), 'bl_id,min(goods_id) as goods_id,min(bl_appoint) as bl_appoint,count(*) as count', 'bl_appoint desc', 'bl_id');

            $bgoods_array = array_under_reset($bgoods_array, 'bl_id');
            foreach ($bundling_list as $key => $val) {
                $bundling_list[$key]['goods_id'] = $bgoods_array[$val['bl_id']]['goods_id'];
                $bundling_list[$key]['count'] = $bgoods_array[$val['bl_id']]['count'];
            }
        }

        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        foreach ($bundling_list as $val) {
            $o = '<a class="btn red confirm-del-on-click" href="javascript:;" data-href="'
                . urlAdminShop('promotion_bundling', 'del_bundling', array(
                'bl_id' => $val['bl_id'],
            )) . '"><i class="fa fa-trash"></i>删除</a>';

            $o .= '<a class="btn green" target="_blank" href="' . urlShop('goods', 'index', array(
                'goods_id' => $val['goods_id'],
            )) . '"><i class="fa fa-list-alt"></i>查看</a>';


            $i = array();
            $i['operation'] = $o;
            $i['bl_name'] = $val['bl_name'];

            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id' => $val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            if (isset($flippedOwnShopIds[$val['store_id']])) {
                $i['store_name'] .= '<span class="ownshop">[自营]</span>';
            }

            $i['bl_discount_price'] = $val['bl_discount_price'];
            $i['count'] = $val['count'];
            $i['bl_state_text'] = $val['bl_state'] == '1' ? '开启' : '关闭';

            $data['list'][$val['bl_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 设置
     */
    public function bundling_settingOp() {
        // 实例化模型
        $model_setting = Model('setting');

        if (chksubmit()){
            // 验证
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["promotion_bundling_price"], "require"=>"true", 'validator'=>'Number', "message"=>Language::get('bundling_price_error')),
                array("input"=>$_POST["promotion_bundling_sum"], "require"=>"true", 'validator'=>'Number', "message"=>Language::get('bundling_sum_error')),
                array("input"=>$_POST["promotion_bundling_goods_sum"], "require"=>"true", 'validator'=>'Number', "message"=>Language::get('bundling_goods_sum_error')),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }

            $data['promotion_bundling_price']       = intval($_POST['promotion_bundling_price']);
            $data['promotion_bundling_sum']         = intval($_POST['promotion_bundling_sum']);
            $data['promotion_bundling_goods_sum']   = intval($_POST['promotion_bundling_goods_sum']);

            $return = $model_setting->updateSetting($data);
            if($return){
                $this->log(L('nc_set,nc_promotion_bundling'));
                showMessage(L('nc_common_op_succ'));
            }else{
                showMessage(L('nc_common_op_fail'));
            }
        }

        // 查询setting列表
        $setting = $model_setting->GetListSetting();
        Tpl::output('setting',$setting);

        Tpl::setDirquna('shop');
        Tpl::showpage('promotion_bundling.setting');
    }

    /**
     * 删除套餐活动
     */
    public function del_bundlingOp() {
        $bl_id = intval($_GET['bl_id']);
        if ($bl_id <= 0) {
            showMessage(L('param_error'), '', 'html', 'error');
        }
        $rs = Model('p_bundling')->delBundlingForAdmin(array('bl_id' => $bl_id));
        if ($rs) {
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }
}
