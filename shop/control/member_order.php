<?php
/**
 * 买家 我的实物订单
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_orderControl extends BaseMemberControl {

    public function __construct() {
        parent::__construct();
        Language::read('member_member_index');
    }

    /**
     * 买家我的订单，以总订单pay_sn来分组显示
     *
     */
    public function indexOp() {
        $model_order = Model('order');

        //搜索
        $condition = array();
        $condition['buyer_id'] = $_SESSION['member_id'];
        if (preg_match('/^\d{10,20}$/',$_GET['keyword'])) {
            $condition['order_sn'] = $_GET['keyword'];
        } elseif ($_GET['keyword'] != '') {
            $condition['order_id'] = array('in',$this->_getOrderIdByKeyword($_GET['keyword']));
        }
        if (preg_match('/^\d{10,20}$/',$_GET['pay_sn'])) {
            $condition['pay_sn'] = $_GET['pay_sn'];
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if ($_GET['state_type'] != '') {
            $condition['order_state'] = str_replace(
                    array('state_new','state_pay','state_send','state_success','state_noeval','state_cancel'),
                    array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS,ORDER_STATE_SUCCESS,ORDER_STATE_CANCEL), $_GET['state_type']);
        }
        if ($_GET['state_type'] == 'state_new') {
            $condition['chain_code'] = 0;
        }
        if ($_GET['state_type'] == 'state_noeval') {
            $condition['evaluation_state'] = 0;
            $condition['order_state'] = ORDER_STATE_SUCCESS;
        }
        if ($_GET['state_type'] == 'state_notakes') {
            $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
            $condition['chain_code'] = array('gt',0);
        }

        //回收站
        if ($_GET['recycle']) {
            $condition['delete_state'] = 1;
        } else {
            $condition['delete_state'] = 0;
        }
        $order_list = $model_order->getOrderList($condition, 20, '*', 'order_id desc','', array('order_common','order_goods','store'));

        $model_refund_return = Model('refund_return');
        $order_list = $model_refund_return->getGoodsRefundList($order_list,1);//订单商品的退款退货显示

        //查询消费者保障服务
        if (C('contract_allow') == 1) {
            $contract_item = Model('contract')->getContractItemByCache();
        }

        //订单列表以支付单pay_sn分组显示
        $order_group_list = array();

        foreach ($order_list as $order_id => $order_info) {

            //显示取消订单
            $order_info['if_buyer_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$order_info);

            //显示退款取消订单
            $order_info['if_refund_cancel'] = $model_order->getOrderOperateState('refund_cancel',$order_info);

            //显示投诉
            $order_info['if_complain'] = $model_order->getOrderOperateState('complain',$order_info);

            //显示收货
            $order_info['if_receive'] = $model_order->getOrderOperateState('receive',$order_info);

            //显示锁定中
            $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

            //显示物流跟踪
            $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

            //显示评价
            $order_info['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$order_info);

            // 显示追加评价
            $order_info['if_evaluation_again'] = $model_order->getOrderOperateState('evaluation_again',$order_info);

            //显示删除订单(放入回收站)
            $order_info['if_delete'] = $model_order->getOrderOperateState('delete',$order_info);

            //显示永久删除
            $order_info['if_drop'] = $model_order->getOrderOperateState('drop',$order_info);

            //显示还原订单
            $order_info['if_restore'] = $model_order->getOrderOperateState('restore',$order_info);

            $refund_all = $order_info['refund_list'][0];
            if (!empty($refund_all) && $refund_all['seller_state'] < 3) {//订单全部退款商家审核状态:1为待审核,2为同意,3为不同意
                $order_info['refund_all'] = $refund_all;
            }
            if (is_array($order_info['extend_order_goods'])) {
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
            }

            if (empty($order_info['zengpin_list'])) {
                $order_info['goods_count'] = count($order_info['goods_list']);
            } else {
                $order_info['goods_count'] = count($order_info['goods_list']) + 1;
            }

            //取得其它订单类型的信息
            $model_order->getOrderExtendInfo($order_info);

            //如果有在线支付且未付款的订单则显示合并付款链接
            $_flag = ($order_info['order_state'] == ORDER_STATE_NEW && $order_info['order_type'] == 1) ||
            ($order_info['order_state'] == ORDER_STATE_NEW && $order_info['order_type'] == 3 && $order_info['payment_code'] == 'online');
            if ($_flag) {
                $order_group_list[$order_info['pay_sn']]['pay_amount'] += $order_info['order_amount']-$order_info['pd_amount']-$order_info['rcb_amount'];
            }

            $order_group_list[$order_info['pay_sn']]['order_list'][] = $order_info;
        }
        Tpl::output('order_group_list',$order_group_list);
        Tpl::output('show_page',$model_order->showpage());

        self::profile_menu($_GET['recycle'] ? 'member_order_recycle' : 'member_order');
        Tpl::showpage('member_order.index');
    }

    /**
     * 物流跟踪
     */
    public function search_deliverOp(){
        Language::read('member_member_index');
        $lang   = Language::getLangContent();
        $order_id   = intval($_GET['order_id']);
        if ($order_id <= 0) {
            showMessage(Language::get('wrong_argument'),'','html','error');
        }

        $model_order    = Model('order');
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $_SESSION['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS))) {
            showMessage('未找到信息','','html','error');
        }
        Tpl::output('order_info',$order_info);

        //卖家信息
        $model_store    = Model('store');
        $store_info     = $model_store->getStoreInfoByID($order_info['store_id']);
        Tpl::output('store_info',$store_info);

        //卖家发货信息
        $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
        Tpl::output('daddress_info',$daddress_info);

        //取得配送公司代码
        $express = rkcache('express',true);
        Tpl::output('e_code',$express[$order_info['extend_order_common']['shipping_express_id']]['e_code']);
        Tpl::output('e_name',$express[$order_info['extend_order_common']['shipping_express_id']]['e_name']);
        Tpl::output('e_url',$express[$order_info['extend_order_common']['shipping_express_id']]['e_url']);
        Tpl::output('shipping_code',$order_info['shipping_code']);

        self::profile_menu('search','search');
        Tpl::output('left_show','order_view');
        Tpl::showpage('member_order_deliver.detail');
    }

    /**
     * 从第三方取快递信息
     *
     */
    public function get_expressOp(){

        $content = Model('express')->get_express($_GET['e_code'], $_GET['shipping_code']);

        $output = array();
        foreach ($content as $k=>$v) {
            if ($v['time'] == '') continue;
            $output[]= $v['time'].'&nbsp;&nbsp;'.$v['context'];
        }
        if (empty($output)) exit(json_encode(false));
        echo json_encode($output);
    }

    /**
     * 订单详细
     *
     */
    public function show_orderOp() {
        $logic_order = logic('order');
        $result = $logic_order->getMemberOrderInfo($_GET['order_id'],$_SESSION['member_id']);
        if (!$result['state']) {
            showMessage($result['msg'],'','html','error');
        }

        Tpl::output('refund_all',$result['data']['refund_all']);
        Tpl::output('order_info',$result['data']['order_info']);
        Tpl::output('daddress_info',$result['data']['daddress_info']);

        Tpl::showpage('member_order.show');
    }

    /**
     * 买家订单状态操作
     *
     */
    public function change_stateOp() {
        $state_type = $_GET['state_type'];
        $order_id   = intval($_GET['order_id']);

        $model_order = Model('order');

        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $_SESSION['member_id'];
        $order_info = $model_order->getOrderInfo($condition);

        //取得其它订单类型的信息
        $model_order->getOrderExtendInfo($order_info);

        if($_GET['state_type'] == 'order_cancel') {
            $result = $this->_order_cancel($order_info, $_POST);
        } else if ($_GET['state_type'] == 'order_receive') {
            $result = $this->_order_receive($order_info, $_POST);
        } else if (in_array($_GET['state_type'],array('order_delete','order_drop','order_restore'))){
            $result = $this->_order_recycle($order_info, $_GET);
        } else {
            exit();
        }

        if(!$result['state']) {
            showDialog($result['msg'],'','error','',5);
        } else {
            showDialog($result['msg'],'reload','js');
        }
    }

    /**
     * 取消订单
     */
    private function _order_cancel($order_info, $post) {
        if (!chksubmit()) {
            Tpl::output('order_info', $order_info);
            Tpl::showpage('member_order.cancel','null_layout');
            exit();
        } else {
            $model_order = Model('order');
            $logic_order = Logic('order');
            $if_allow = $model_order->getOrderOperateState('buyer_cancel',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }
            if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
                $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
                return callback(false,'该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');
            }

            $msg = $post['state_info1'] != '' ? $post['state_info1'] : $post['state_info'];
            if ($order_info['order_type'] != 2) {
                $cancel_condition = array();
                if ($order_info['payment_code'] != 'offline') {
                    $cancel_condition['order_state'] = ORDER_STATE_NEW;
                }
                $result = $logic_order->changeOrderStateCancel($order_info,'buyer', $_SESSION['member_name'], $msg,true,$cancel_condition);
            } else {
                //取消预定订单
                $result = Logic('order_book')->changeOrderStateCancel($order_info,'buyer', $_SESSION['member_name'], $msg);
            }
            return $result;
        }
    }

    /**
     * 收货
     */
    private function _order_receive($order_info, $post) {
        if (!chksubmit()) {
            Tpl::output('order_info', $order_info);
            Tpl::showpage('member_order.receive','null_layout');
            exit();
        } else {
            $model_order = Model('order');
            $logic_order = Logic('order');
            $if_allow = $model_order->getOrderOperateState('receive',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }

            return $logic_order->changeOrderStateReceive($order_info,'buyer',$_SESSION['member_name'],'签收了货物');
        }
    }

    /**
     * 回收站
     */
    private function _order_recycle($order_info, $get) {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $state_type = str_replace(array('order_delete','order_drop','order_restore'), array('delete','drop','restore'), $_GET['state_type']);
        $if_allow = $model_order->getOrderOperateState($state_type,$order_info);
        if (!$if_allow) {
            return callback(false,'无权操作');
        }

        return $logic_order->changeOrderStateRecycle($order_info,'buyer',$state_type);
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key='') {
        Language::read('member_layout');
        $menu_array = array(
            array('menu_key'=>'member_order','menu_name'=>Language::get('nc_member_path_order_list'), 'menu_url'=>'index.php?act=member_order'),
            array('menu_key'=>'member_order_recycle','menu_name'=>'回收站', 'menu_url'=>'index.php?act=member_order&recycle=1'),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

    private function _getOrderIdByKeyword($keyword) {
        $goods_list = Model('order')->getOrderGoodsList(array('goods_name'=>array('like','%'.$keyword.'%')),'order_id',100,null,'', null,'order_id');
        return array_keys($goods_list);
    }
}
