<?php
/**
 * 订单打印
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 * 
 ***/


defined('In33hao') or exit('Access Invalid!');

class order_call_printControl extends BaseSellerControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_printorder');
	}

	/**
	 * 查看订单
	 */
	public function indexOp() {
		 $model_order = Model('order');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if ($_GET['order_sn'] != '') {
            $condition['order_sn'] = $_GET['order_sn'];
        }
        if ($_GET['buyer_name'] != '') {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
       
   	    /*$allow_state_array = array('state_new','state_pay','state_send','state_success','state_cancel');
        if (in_array($_GET['state_type'],$allow_state_array)) {
            $condition['order_state'] = str_replace($allow_state_array,
                    array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS,ORDER_STATE_CANCEL), $_GET['state_type']);
        } else {
            $_GET['state_type'] = 'store_order';
        }*/
		
		$condition['order_state'] = ORDER_STATE_PAY;
		
		
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

        if ($_GET['skip_off'] == 1) {
            $condition['order_state'] = array('neq',ORDER_STATE_CANCEL);
        }
		
		//$condition['is_print'] = 0;

        $order_list = $model_order->getOrderList($condition, 20, '*', 'order_id desc','', array('order_goods','order_common','member'));

        //页面中显示那些操作
        foreach ($order_list as $key => $order_info) {

        	//显示取消订单
        	$order_info['if_cancel'] = $model_order->getOrderOperateState('store_cancel',$order_info);

        	//显示调整运费
        	$order_info['if_modify_price'] = $model_order->getOrderOperateState('modify_price',$order_info);
			
		    //显示修改价格
        	$order_info['if_spay_price'] = $model_order->getOrderOperateState('spay_price',$order_info);

        	//显示发货
        	$order_info['if_send'] = $model_order->getOrderOperateState('send',$order_info);

        	//显示锁定中
        	$order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        	//显示物流跟踪
        	$order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

			$goods_all_num = 0;
			$goods_total_price = 0;
        	foreach ($order_info['extend_order_goods'] as $value) {
        	    $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
        	    $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
        	    $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
        	    $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
				
				$value['goods_name'] = str_cut($value['goods_name'],100);
				$value['goods_all_price'] = ncPriceFormat($value['goods_num'] * $value['goods_price']);
				$goods_all_num += $value['goods_num'];
				$goods_total_price += $value['goods_all_price'];
				
				
        	    if ($value['goods_type'] == 5) {
        	        $order_info['zengpin_list'][] = $value;
        	    } else {
        	        $order_info['goods_list'][] = $value;
        	    }
        	}
			
			$order_info['goods_all_num'] = $goods_all_num;
			$order_info['goods_total_price'] = $goods_total_price;
			
			//优惠金额
		    $order_info['promotion_amount'] = $order_info['goods_total_price'] - $order_info['goods_amount'];
			
			
			//卖家信息
			$model_store	= Model('store');
			$store_info		= $model_store->getStoreInfoByID($order_info['store_id']);
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
			$order_info['store_info'] = $store_info;
			

        	if (empty($order_info['zengpin_list'])) {
        	    $order_info['goods_count'] = count($order_info['goods_list']);
        	} else {
        	    $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        	}
        	$order_list[$key] = $order_info;

        }
		

		$order_list = array_values($order_list);
        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        //self::profile_menu('list',$_GET['state_type']);

		Tpl::showpage('order_call.print',"null_layout");
        //Tpl::showpage('order_call.index');
		 
	}
	
	/**
	 * 查看订单
	 */
	public function smallOp() {
		 $model_order = Model('order');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if ($_GET['order_sn'] != '') {
            $condition['order_sn'] = $_GET['order_sn'];
        }
        if ($_GET['buyer_name'] != '') {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
       
   	    /*$allow_state_array = array('state_new','state_pay','state_send','state_success','state_cancel');
        if (in_array($_GET['state_type'],$allow_state_array)) {
            $condition['order_state'] = str_replace($allow_state_array,
                    array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS,ORDER_STATE_CANCEL), $_GET['state_type']);
        } else {
            $_GET['state_type'] = 'store_order';
        }*/
		
		$condition['order_state'] = ORDER_STATE_PAY;
		
		
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

        if ($_GET['skip_off'] == 1) {
            $condition['order_state'] = array('neq',ORDER_STATE_CANCEL);
        }

		//$condition['is_print'] = 0;
		
        $order_list = $model_order->getOrderList($condition, 20, '*', 'order_id desc','', array('order_goods','order_common','member'));

        //页面中显示那些操作
        foreach ($order_list as $key => $order_info) {

        	//显示取消订单
        	$order_info['if_cancel'] = $model_order->getOrderOperateState('store_cancel',$order_info);

        	//显示调整运费
        	$order_info['if_modify_price'] = $model_order->getOrderOperateState('modify_price',$order_info);
			
		    //显示修改价格
        	$order_info['if_spay_price'] = $model_order->getOrderOperateState('spay_price',$order_info);

        	//显示发货
        	$order_info['if_send'] = $model_order->getOrderOperateState('send',$order_info);

        	//显示锁定中
        	$order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        	//显示物流跟踪
        	$order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

			$goods_all_num = 0;
			$goods_total_price = 0;
        	foreach ($order_info['extend_order_goods'] as $value) {
        	    $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
        	    $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
        	    $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
        	    $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
				
				$value['goods_name'] = str_cut($value['goods_name'],100);
				$value['goods_all_price'] = ncPriceFormat($value['goods_num'] * $value['goods_price']);
				$goods_all_num += $value['goods_num'];
				$goods_total_price += $value['goods_all_price'];
				
				
        	    if ($value['goods_type'] == 5) {
        	        $order_info['zengpin_list'][] = $value;
        	    } else {
        	        $order_info['goods_list'][] = $value;
        	    }
        	}
			
			$order_info['goods_all_num'] = $goods_all_num;
			$order_info['goods_total_price'] = $goods_total_price;
			
			//优惠金额
		    $order_info['promotion_amount'] = $order_info['goods_total_price'] - $order_info['goods_amount'];
			
			
			//卖家信息
			$model_store	= Model('store');
			$store_info		= $model_store->getStoreInfoByID($order_info['store_id']);
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
			$order_info['store_info'] = $store_info;
			

        	if (empty($order_info['zengpin_list'])) {
        	    $order_info['goods_count'] = count($order_info['goods_list']);
        	} else {
        	    $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        	}
        	$order_list[$key] = $order_info;

        }
		

		$order_list = array_values($order_list);
        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        //self::profile_menu('list',$_GET['state_type']);

		Tpl::showpage('order_small.print',"null_layout");
        //Tpl::showpage('order_call.index');
		 
	}
	
	
	public function indexoldOp() {
		$order_id	= intval($_GET['order_id']);
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
		$model_store	= Model('store');
		$store_info		= $model_store->getStoreInfoByID($order_info['store_id']);
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
				$goods_new_list[ceil($i/15)][$i] = $v;
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
}
