<?php
/**
 * 卖家实物订单管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class store_orderControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_store_index');
    }

    /**
     * 订单列表
     *
     */
    public function indexOp() {
        $model_order = Model('order');
        if (!$_GET['state_type']) {
            $_GET['state_type'] = 'store_order';
        }
        $order_list = $model_order->getStoreOrderList($_SESSION['store_id'], $_GET['order_sn'], $_GET['buyer_name'], $_GET['state_type'], $_GET['query_start_date'], $_GET['query_end_date'], $_GET['skip_off'], '*', array('order_goods','order_common','member'));

        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        self::profile_menu('list',$_GET['state_type']);

        Tpl::showpage('store_order.index');
    }

    /**
     * 卖家订单详情
     *
     */
    public function show_orderOp() {
        Language::read('member_member_index');
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            showMessage(Language::get('wrong_argument'),'','html','error');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods','member'));
        if (empty($order_info)) {
            showMessage(Language::get('store_order_none_exist'),'','html','error');
        }

        //取得订单其它扩展信息
        $model_order->getOrderExtendInfo($order_info);

        $model_refund_return = Model('refund_return');
        $order_list = array();
        $order_list[$order_id] = $order_info;
        $order_list = $model_refund_return->getGoodsRefundList($order_list,1);//订单商品的退款退货显示
        $order_info = $order_list[$order_id];
        $refund_all = $order_info['refund_list'][0];
        if (!empty($refund_all) && $refund_all['seller_state'] < 3) {//订单全部退款商家审核状态:1为待审核,2为同意,3为不同意
            Tpl::output('refund_all',$refund_all);
        }

        //显示锁定中
        $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        //显示调整费用
        $order_info['if_modify_price'] = $model_order->getOrderOperateState('modify_price',$order_info);

        //显示取消订单
        $order_info['if_store_cancel'] = $model_order->getOrderOperateState('store_cancel',$order_info);

        //显示发货
        $order_info['if_store_send'] = $model_order->getOrderOperateState('store_send',$order_info);

        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_TIME * 3600;
        }

        //显示快递信息
        if ($order_info['shipping_code'] != '') {
            $express = rkcache('express',true);
            $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
            $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
            $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        }

        //显示系统自动收获时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
            $order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 24 * 3600;
        }

        //取得订单操作日志
        $order_log_list = $model_order->getOrderLogList(array('order_id'=>$order_info['order_id']),'log_id asc');
        Tpl::output('order_log_list',$order_log_list);

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $last_log = end($order_log_list);
            if ($last_log['log_orderstate'] == ORDER_STATE_CANCEL) {
                $order_info['close_info'] = $last_log;
            }
        }
        //查询消费者保障服务
        if (C('contract_allow') == 1) {
            $contract_item = Model('contract')->getContractItemByCache();
        }
        foreach ($order_info['extend_order_goods'] as $value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            //处理消费者保障服务
            if (trim($value['goods_contractid']) && $contract_item) {
                $goods_contractid_arr = explode(',',$value['goods_contractid']);
                foreach ((array)$goods_contractid_arr as $gcti_v) {
                    $value['contractlist'][] = $contract_item[$gcti_v];
                }
            }
            if ($value['goods_type'] == 5) {
                $order_info['zengpin_list'][] = $value;
            } else {
                $order_info['goods_list'][] = $value;
            }
        }

        if (empty($order_info['zengpin_list'])) {
            $order_info['goods_count'] = count($order_info['goods_list']);
        } else {
            $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        }

        Tpl::output('order_info',$order_info);

        //发货信息
        if (!empty($order_info['extend_order_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
            Tpl::output('daddress_info',$daddress_info);
        }

        Tpl::showpage('store_order.show');
    }

    /**
     * 卖家订单状态操作
     *
     */
    public function change_stateOp() {
        $state_type = $_GET['state_type'];
        $order_id   = intval($_GET['order_id']);

        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info = $model_order->getOrderInfo($condition);

        //取得其它订单类型的信息
        $model_order->getOrderExtendInfo($order_info);

        if ($_GET['state_type'] == 'order_cancel') {
            $result = $this->_order_cancel($order_info,$_POST);
        } elseif ($_GET['state_type'] == 'modify_price') {
            $result = $this->_order_ship_price($order_info,$_POST);
        } elseif ($_GET['state_type'] == 'spay_price') {
			$result = $this->_order_spay_price($order_info,$_POST);
    		}
	
        if (!$result['state']) {
            showDialog($result['msg'],'','error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();',5);
        } else {
            showDialog($result['msg'],'reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
        }
    }

    /**
     * 取消订单
     * @param unknown $order_info
     */
    private function _order_cancel($order_info, $post) {
        $model_order = Model('order');
        $logic_order = Logic('order');
        if(!chksubmit()) {
            Tpl::output('order_info',$order_info);
            Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.cancel','null_layout');
            exit();
         } else {
             $if_allow = $model_order->getOrderOperateState('store_cancel',$order_info);
             if (!$if_allow) {
                 return callback(false,'无权操作');
             }
             if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
                 $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
                 return callback(false,'该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');

             }
             $msg = $post['state_info1'] != '' ? $post['state_info1'] : $post['state_info'];
             if ($order_info['order_type'] == 2) {
                 //预定订单
                 return Logic('order_book')->changeOrderStateCancel($order_info,'seller',$_SESSION['seller_name'], $msg);
             } else {
                 $cancel_condition = array();
                 if ($order_info['payment_code'] != 'offline') {
                     $cancel_condition['order_state'] = ORDER_STATE_NEW;
                 }
                 return $logic_order->changeOrderStateCancel($order_info,'seller',$_SESSION['seller_name'], $msg,true,$cancel_condition);
             }
         }
    }

    /**
     * 修改运费
     * @param unknown $order_info
     */
    private function _order_ship_price($order_info, $post) {
        $model_order = Model('order');
        $logic_order = Logic('order');
        if(!chksubmit()) {
            Tpl::output('order_info',$order_info);
            Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.edit_price','null_layout');
            exit();
        } else {
            $if_allow = $model_order->getOrderOperateState('modify_price',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }
            return $logic_order->changeOrderShipPrice($order_info,'seller',$_SESSION['seller_name'],$post['shipping_fee']);
        }

    }
	/**
	 * 修改商品价格
	 * @param unknown $order_info
	 */
	private function _order_spay_price($order_info, $post) {
        $model_order = Model('order');
	    $logic_order = Logic('order');
	    if(!chksubmit()) {
	        Tpl::output('order_info',$order_info);
	        Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.edit_spay_price','null_layout');
            exit();
        } else {
            $if_allow = $model_order->getOrderOperateState('spay_price',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }
            return $logic_order->changeOrderSpayPrice($order_info,'seller',$_SESSION['member_name'],$post['goods_amount']); 
	    }
	}

    /**
     * 打印发货单
     */
    public function order_printOp() {
        Language::read('member_printorder');

        $order_id   = intval($_GET['order_id']);
        if ($order_id <= 0){
            showMessage(Language::get('wrong_argument'),'','html','error');
        }
        $order_model = Model('order');
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info = $order_model->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info)){
            showMessage(Language::get('member_printorder_ordererror'),'','html','error');
        }
        Tpl::output('order_info',$order_info);

        //卖家信息
        $model_store    = Model('store');
        $store_info     = $model_store->getStoreInfoByID($order_info['store_id']);
        if (!empty($store_info['store_label'])){
            if (file_exists(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.$store_info['store_label'])){
                $store_info['store_label'] = UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$store_info['store_label'];
            }else {
                $store_info['store_label'] = '';
            }
        }
        if (!empty($store_info['store_stamp'])){
            if (file_exists(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.$store_info['store_stamp'])){
                $store_info['store_stamp'] = UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$store_info['store_stamp'];
            }else {
                $store_info['store_stamp'] = '';
            }
        }
        Tpl::output('store_info',$store_info);

        //订单商品
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $goods_new_list = array();
        $goods_all_num = 0;
        $goods_total_price = 0;
        if (!empty($order_info['extend_order_goods'])){
            $goods_count = count($order_goods_list);
            $i = 1;
            foreach ($order_info['extend_order_goods'] as $k => $v){
                $v['goods_name'] = str_cut($v['goods_name'],100);
                $goods_all_num += $v['goods_num'];
                $v['goods_all_price'] = ncPriceFormat($v['goods_num'] * $v['goods_price']);
                $goods_total_price += $v['goods_all_price'];
                $goods_new_list[ceil($i/4)][$i] = $v;
                $i++;
            }
        }
        //优惠金额
        $promotion_amount = $goods_total_price - $order_info['goods_amount'];
        //运费
        $order_info['shipping_fee'] = $order_info['shipping_fee'];
        Tpl::output('promotion_amount',$promotion_amount);
        Tpl::output('goods_all_num',$goods_all_num);
        Tpl::output('goods_total_price',ncPriceFormat($goods_total_price));
        Tpl::output('goods_list',$goods_new_list);
        Tpl::showpage('store_order.print',"null_layout");
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type='',$menu_key='') {
        Language::read('member_layout');
        switch ($menu_type) {
            case 'list':
            $menu_array = array(
            array('menu_key'=>'store_order',        'menu_name'=>Language::get('nc_member_path_all_order'), 'menu_url'=>'index.php?act=store_order'),
            array('menu_key'=>'state_new',          'menu_name'=>Language::get('nc_member_path_wait_pay'),  'menu_url'=>'index.php?act=store_order&op=index&state_type=state_new'),
            array('menu_key'=>'state_pay',          'menu_name'=>Language::get('nc_member_path_wait_send'), 'menu_url'=>'index.php?act=store_order&op=store_order&state_type=state_pay'),
            array('menu_key'=>'state_notakes',      'menu_name'=>'待自提', 'menu_url'=>'index.php?act=store_order&op=store_order&state_type=state_notakes'),
            array('menu_key'=>'state_send',         'menu_name'=>Language::get('nc_member_path_sent'),      'menu_url'=>'index.php?act=store_order&op=index&state_type=state_send'),
            array('menu_key'=>'state_success',      'menu_name'=>Language::get('nc_member_path_finished'),  'menu_url'=>'index.php?act=store_order&op=index&state_type=state_success'),
            array('menu_key'=>'state_cancel',       'menu_name'=>Language::get('nc_member_path_canceled'),  'menu_url'=>'index.php?act=store_order&op=index&state_type=state_cancel'),
            );
            break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
