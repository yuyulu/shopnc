<?php
/**
 * 限时折扣管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class promotion_fcodeControl extends SystemControl{
    private $links = array(
        array('url'=>'act=promotion_fcode&op=index','text'=>'商品列表'),
        array('url'=>'act=promotion_fcode&op=fcode_quota_list','text'=>'套餐列表'),
        array('url'=>'act=promotion_fcode&op=fcode_setting','text'=>'设置')
    );
    public function __construct(){
        parent::__construct();
        //检查审核功能是否开启
        if (intval($_GET['promotion_allow']) !== 1 && intval(C('promotion_allow')) !== 1){
            $url = array(
                array(
                    'url'=>'index.php?act=setting',
                    'msg'=>L('close'),
                ),
                array(
                    'url'=>'index.php?act=promotion_fcode&promotion_allow=1',
                    'msg'=>L('open'),
                )
            );
            showMessage('商品促销功能尚未开启', $url, 'html', 'succ', 1, 6000);
        }
    }

    /**
     * 默认Op
     */
    public function indexOp() {
        //自动开启优惠套装
        if (intval($_GET['promotion_allow']) === 1){
            $model_setting = Model('setting');
            $update_array = array();
            $update_array['promotion_allow'] = 1;
            $model_setting->updateSetting($update_array);
        }
        $this->goods_listOp();
    }

    /**
     * 活动商品列表
     */
    public function goods_listOp() {
        Tpl::output('top_link',$this->sublink($this->links,'index'));
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_fcode.goods');
    }

    /**
     * 活动商品管理XML
     */
    public function get_goods_xmlOp() {
        $model_fcode = Model('p_fcode');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('goods_id', 'goods_name', 'fcode_down_payment', 'fcode_final_payment', 'fcode_down_time', 'goods_price', 'store_id', 'store_name', 'is_own_shop');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $goods_list = $model_fcode->getFCodeGoodsList($condition, '*', $page, $order);

        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        $data = array();
        $data['now_page'] = $model_fcode->shownowpage();
        $data['total_num'] = $model_fcode->gettotalnum();
        foreach ($goods_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del('". $value['goods_id'] ."')\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>设置 <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='" . urlAdminShop('promotion_fcode', 'download_f_code_excel', array('gid' => $value['goods_id'])) . "' target=\"_blank\">下载Ｆ码</a></li>";
            $operation .= "<li><a href='" . urlShop('goods', 'index', array('goods_id' => $value['goods_id'])) . "' target='_blank'>查看商品</a></li>";
            $operation .= "</ul>";
            $param['operation'] = $operation;
            $param['goods_id'] = $value['goods_id'];
            $param['goods_name'] = $value['goods_name'];
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array('store_id'=>$value['store_id'])) . '">' .$value['store_name'] . '</a>';
            if (isset($flippedOwnShopIds[$value['store_id']])) {
                $param['store_name'] .= '<span class="ownshop">[自营]</span>';
            }
            $data['list'][$value['goods_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 删除F码商品活动
     */
    public function del_goodsOp() {
        $id = intval($_GET['id']);
        if ($id > 0) {
            $state = Model('p_fcode')->delFCodeGoodsByGoodsId($id);
            $this->log('删除F码商品活动，商品ID'.$id);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 下载F码
     */
    public function download_f_code_excelOp() {
        $goods_id = $_GET['gid'];
        if ($goods_id <= 0) {
            showMessage(L('wrong_argument'), '', '', 'error');
        }
        $goods_info = Model('goods')->getGoodsInfoByID($goods_id);
        if (empty($goods_info)) {
            showMessage(L('wrong_argument'), '', '', 'error');
        }

        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'号码');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'使用状态');
        $data = Model('goods_fcode')->getGoodsFCodeList(array('goods_id' => $goods_id));
        foreach ($data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['fc_code']);
            $tmp[] = array('data'=>$v['fc_state'] ? '已使用' : '未使用');
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset($goods_info['goods_name'],CHARSET));
        $excel_obj->generateXML($excel_obj->charset($goods_info['goods_name'],CHARSET).'-'.date('Y-m-d-H',time()));
    }

    /**
     * 套餐列表
     */
    public function fcode_quota_listOp() {
        Tpl::output('top_link',$this->sublink($this->links,'fcode_quota_list'));
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_fcode.quota');
    }

    /**
     * 套餐列表XML
     */
    public function get_quota_xmlOp() {
        $model_fcode = Model('p_fcode');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('store_id', 'store_name', 'fcq_starttime', 'fcq_endtime');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $quota_list = $model_fcode->getfcodeQuotaList($condition, '*', $page, $order);

        $data = array();
        $data['now_page'] = $model_fcode->shownowpage();
        $data['total_num'] = $model_fcode->gettotalnum();
        foreach ($quota_list as $value) {
            $param = array();
            $param['operation'] = '--';
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $value['store_name'];
            $param['fcq_starttime'] = date('Y-m-d H:i:s', $value['fcq_starttime']);
            $param['fcq_endtime'] = date('Y-m-d H:i:s', $value['fcq_endtime']);
            $data['list'][$value['fcq_id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 设置
     */
    public function fcode_settingOp() {
        // 实例化模型
        $model_setting = Model('setting');

        if (chksubmit()){
            // 验证
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["promotion_fcode_price"], "require"=>"true", 'validator'=>'Number', "message"=>'请填写展位价格'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }

            $data['promotion_fcode_price'] = intval($_POST['promotion_fcode_price']);

            $return = $model_setting->updateSetting($data);
            if($return){
                $this->log(L('nc_set').' 推荐展位');
                showMessage(L('nc_common_op_succ'));
            }else{
                showMessage(L('nc_common_op_fail'));
            }
        }

        // 查询setting列表
        $setting = $model_setting->GetListSetting();
        Tpl::output('setting',$setting);

        Tpl::output('top_link',$this->sublink($this->links,'fcode_setting'));
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_fcode.setting');
    }
}
