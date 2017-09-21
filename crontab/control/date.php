<?php
/**
 * 任务计划 - 天执行的任务
 *
 * 
 * @好商城 提供技术支持 授权请购买shopnc授权
 * @license    http://www.33h ao.com
 * @link       交流群号：138 182 377
 */
defined('In33hao') or exit('Access Invalid!');

class dateControl extends BaseCronControl {

    /**
     * 该文件中所有任务执行频率，默认1天，单位：秒
     * @var int
     */
    const EXE_TIMES = 86400;
    
    /**
     * 优惠券即将到期提醒时间，单位：天
     * @var int
     */
    const VOUCHER_INTERVAL = 5;
    /**
     * 兑换码即将到期提醒时间，单位：天
     * @var int
     */
    const VR_CODE_INTERVAL = 5;
    /**
     * 订单结束后可评论时间，15天，60*60*24*15
     * @var int
     */
    const ORDER_EVALUATE_TIME = 1296000;
    /**
     * 订单结束后可追加评价时间，182.5， 60*60*24*182.5
     */
    const ORDER_EVALUATE_AGAIN_TIME = 15768000;

    /**
     * 每次到货通知消息数量
     * @var int
     */
    const ARRIVAL_NOTICE_NUM = 100;

    private $_model_store;
    private $_model_store_ext;
    private $_model_bill;
    private $_model_order;
    private $_model_store_cost;
    private $_model_vr_bill;
    private $_model_vr_order;

    /**
     * 默认方法
     */
    public function indexOp() {

        //更新订单商品佣金值
        $this->_order_commis_rate_update();

        //订单超期后不允许评价
        $this->_order_eval_expire_update();

        //订单超期后不允许追加评价
        $this->_order_eval_again_expire_update();

        //门店自提订单、支付方式为门店支付的订单7天内未自提自动取消
        $this->_order_chain_timeout_cancel();

         //预定订单及时支付尾款提醒
        $this->_order_book_end_pay_notice();

        //订单自动完成
        $this->_order_auto_complete();

        //预定订单超时未付尾款取消订单
        $this->_order_book_timeout_cancel();

        //自提点中，已经关闭的订单删除
        $this->_order_delivery_cancel_del();

        //更新订单扩展表收货人所在省份ID
        $this->_order_reciver_provinceid_update();

        //更新退款申请超时处理
        Model('trade')->editRefundConfirm();

        //代金券即将过期提醒
        $this->_voucher_will_expire();

        //虚拟兑换码即将过期提醒
        $this->_vr_code_will_expire();

        //更新商品访问量
        $this->_goods_click_update();

        //更新商品促销到期状态
        $this->_goods_promotion_state_update();

        //商品到货通知提醒
        $this->_arrival_notice();

        //缓存订单及订单商品相关数据
        $this->_order_goods_cache();

        //会员相关数据统计
        $this->_member_stat();

        // 取消无货后门店商品的标记
        $this->_delivery_goods_sign_update();

        //生成结算
        $this->_create_bill();
    }

    /**
     * 预定订单及时支付尾款提醒
     */
    private function _order_book_end_pay_notice() {
        $model_order = Model('order');
        $model_order_book = Model('order_book');
        $logic_order_book = Logic('order_book');
        $condition = array();
        $condition['book_step'] = 2;
        $condition['book_pay_time'] = 0;
        $condition['book_pay_notice'] = 0;
        $condition['book_end_time'] = array('lt',TIMESTAMP + BOOK_AUTO_END_TIME * 3600);
        //最多处理1000个订单
        $order_book_list = $model_order_book->getOrderBookList($condition,'','','*',1000);
        if (empty($order_book_list)) return;
        foreach ($order_book_list as $order_book_info) {
            $mobile = $order_book_info['book_buyer_phone'];
            $condition = array();
            $condition['book_step'] = 1;
            $condition['book_order_id'] = $order_book_info['book_order_id'];
            $order_book_info = $model_order_book->getOrderBookInfo($condition);

            //如果已经支付，发送通知
            if (!empty($order_book_info['book_pay_time'])) {
                $order_info = $model_order->getOrderInfo(array('order_id'=>$order_book_info['book_order_id']),array(),'order_sn,buyer_id');
                // 发送买家消息
                $param = array();
                $param['code'] = 'order_book_end_pay';
                $param['member_id'] = $order_info['buyer_id'];
                $param['number'] = array('mobile'=>$mobile);
                $param['param'] = array(
                    'order_sn' => $order_info['order_sn'],
                    'order_url' => urlShop('member_order', 'index')
                );
                QueueClient::push('sendMemberMsg', $param);
            }
            //更新通知状态
            $condition = array();
            $condition['book_step'] = 2;
            $condition['book_order_id'] = $order_book_info['book_order_id'];
            $update = $model_order_book->editOrderBook(array('book_pay_notice'=>1),$condition);
            if (!$update) {
                $this->log('更新预定订单尾款支付提醒状态失败order_id:'.$order_book_info['book_order_id']); break;
            }
        }
    }

    /**
     * 预定订单超时未付尾款取消订单
     */
    private function _order_book_timeout_cancel() {
        $model_order = Model('order');
        $model_order_book = Model('order_book');
        $logic_order_book = Logic('order_book');
        $condition = array();
        $condition['book_step'] = 2;
        $condition['book_pay_time'] = 0;
        $condition['book_end_time'] = array('lt', TIMESTAMP);
        //最多处理1000个订单
        $order_book_list = $model_order_book->getOrderBookList($condition,'','','*',1000);
        if (empty($order_book_list)) return;
        foreach ($order_book_list as $order_book_info) {
            $condition = array();
            $condition['book_step'] = 1;
            $condition['book_order_id'] = $order_book_info['book_order_id'];
            $condition['book_cancel_time'] = 0;
            $order_book_info = $model_order_book->getOrderBookInfo($condition);

            //如果已经支付定金
            if (!empty($order_book_info['book_pay_time'])) {
                //取消订单
                $order_info = $model_order->getOrderInfo(array('order_id'=>$order_book_info['book_order_id']));
                $result = $logic_order_book->changeOrderStateCancel($order_info,'system','系统','超期未支付尾款系统自动关闭订单');
                if (!$result['state']) {
                    $this->log('预定订单超期未支付尾款关闭失败order_id:'.$order_book_info['book_order_id']); break;
                }
            }
        }
    }

    /**
     * 订单自动完成
     */
    private function _order_auto_complete() {

        //虚拟订单过使用期自动完成
        $_break = false;
        $model_order = Model('vr_order');
        $logic_order = Logic('vr_order');
        $condition = array();
        $condition['order_state'] = ORDER_STATE_PAY;
        $condition['vr_indate'] = array('lt',TIMESTAMP);
        //分批，每批处理100个订单，最多处理5W个订单
        for ($i = 0; $i < 500; $i++){
            if ($_break) {
                break;
            }
            $order_list = $model_order->getOrderList($condition, '', 'order_id,order_sn', 'vr_indate asc', 100);
            if (empty($order_list)) break;
            foreach ($order_list as $order_info) {
                $result = $logic_order->changeOrderStateSuccess($order_info['order_id']);
                if (!$result['state']) {
                    $this->log('虚拟订单过使用期自动完成失败SN:'.$order_info['order_sn']); $_break = true; break;
                }
            }
        }

        //实物订单发货后，超期自动收货完成
        $_break = false;
        $model_order = Model('order');
        $logic_order = Logic('order');
        $condition = array();
        $condition['order_state'] = ORDER_STATE_SEND;
        $condition['lock_state'] = 0;
        $condition['delay_time'] = array('lt',TIMESTAMP - ORDER_AUTO_RECEIVE_DAY * 86400);
        //分批，每批处理100个订单，最多处理5W个订单
        for ($i = 0; $i < 500; $i++){
            if ($_break) {
                break;
            }
            $order_list = $model_order->getOrderList($condition, '', '*', 'delay_time asc', 100);
            if (empty($order_list)) break;
            foreach ($order_list as $order_info) {
                $result = $logic_order->changeOrderStateReceive($order_info,'system','系统','超期未收货系统自动完成订单');
                if (!$result['state']) {
                    $this->log('实物订单超期未收货自动完成订单失败SN:'.$order_info['order_sn']); $_break = true; break;
                }
            }
        }
    }

    /**
     * 自提订单中，已经关闭订单的，删除
     */
    private function _order_delivery_cancel_del() {
        $model_delivery = Model('delivery_order');
        $model_order = Model('order');

        for($i = 0; $i < 10; $i++) {
            $delivery_list = $model_delivery->getDeliveryOrderDefaultList(array(), '*', 0, 'order_id asc', 100);
            if (!empty($delivery_list)) {
                $order_ids = array();
                foreach ($delivery_list as $k => $v) {
                    $order_ids[] = $v['order_id'];
                }
                $condition = array();
                $condition['order_state'] = ORDER_STATE_CANCEL;
                $condition['order_id'] = array('in',$order_ids);
                $order_list = $model_order->getOrderList($condition,'','order_id');
                if (!empty($order_list)) {
                    $order_ids = array();
                    foreach ($order_list as $k => $v) {
                        $order_ids[] = $v['order_id'];
                    }
                    $del = $model_delivery->delDeliveryOrder(array('order_id'=>array('in',$order_ids)));
                    if (!del) {
                        $this->log('删除自提点订单失败');
                    }
                } else {
                    break;
                }
            } else {
                break;
            }
        }
    }
    
    /**
     * 更新订单扩展表中收货人所在省份ID
     */
    private function _order_reciver_provinceid_update() {
        $model_order = Model('order');
        $model_area = Model('area');

        //每次最多处理5W个订单
        $condition = array();
        $condition['reciver_province_id'] = 0;
        $condition['reciver_city_id'] = array('neq',0);
        for($i = 0; $i < 500; $i++) {
            $order_list = $model_order->getOrderCommonList($condition, 'reciver_city_id','order_id desc', 100);
            if (!empty($order_list)) {
                $city_ids = array();
                foreach ($order_list as $v) {
                    if (!in_array($v['reciver_city_id'],$city_ids)) {
                        $city_ids[] = $v['reciver_city_id'];
                    }
                }
                $area_list = $model_area->getAreaList(array('area_id'=>array('in',$city_ids)),'area_parent_id,area_id');
                if (!empty($area_list)) {
                    foreach ($area_list as $v) {
                        $update = $model_order->editOrderCommon(array('reciver_province_id'=>$v['area_parent_id']),array('reciver_city_id'=>$v['area_id']));
                        if (!$update) {
                            $this->log('更新订单扩展表中收货人所在省份ID失败');break;
                        }
                    }
                }
            } else {
                break;
            }
        }
    }

    /**
     * 增加会员积分和经验值
     */
    private function _add_points() {
        return;
        $model_points = Model('points');
        $model_exppoints = Model('exppoints');

        //24小时之内登录的会员送积分和经验值,每次最多处理5W个会员
        $model_member = Model('member');
        $condition = array();
        $condition['member_login_time'] = array('gt',TIMESTAMP - self::EXE_TIMES);
        for($i = 0; $i < 50000; $i=$i+100) {
            $member_list = $model_member->getMemberList($condition, 'member_name,member_id',0,'', "{$i},100");
            if (!empty($member_list)) {
                foreach ($member_list as $member_info) {
                    if (C('points_isuse')) {
                        $model_points->savePointsLog('login',array('pl_memberid'=>$member_info['member_id'],'pl_membername'=>$member_info['member_name']),true);
                    }
                    $model_exppoints->saveExppointsLog('login',array('exp_memberid'=>$member_info['member_id'],'exp_membername'=>$member_info['member_name']),true);
                    
                }
            } else {
                break;
            }
        }

       //24小时之内注册的会员送积分,每次最多处理5W个会员
       if (C('points_isuse')) {
           $condition = array();
           $condition['member_time'] = array('gt',TIMESTAMP - self::EXE_TIMES);
           for($i = 0; $i < 50000; $i=$i+100) {
               $member_list = $model_member->getMemberList($condition, 'member_name,member_id',0,'member_id desc', "{$i},100");
               if (!empty($member_list)) {
                   foreach ($member_list as $member_info) {
                       $model_points->savePointsLog('regist',array('pl_memberid'=>$member_info['member_id'],'pl_membername'=>$member_info['member_name']),true);
                   }
               } else {
                   break;
               }
           }
       }

        //24小时之内完成了实物订单送积分和经验值,每次最多处理5W个订单
        $model_order = Model('order');
        $condition = array();
        $condition['finnshed_time'] = array('gt',TIMESTAMP - self::EXE_TIMES);
        for($i = 0; $i < 50000; $i=$i+100) {
            $order_list = $model_order->getOrderList($condition,'','buyer_name,buyer_id,order_amount,order_sn,order_id','', "{$i},100");
            if (!empty($order_list)) {
                foreach ($order_list as $order_info) {
                    if (C('points_isuse')) {
                        $model_points->savePointsLog('order',array('pl_memberid'=>$order_info['buyer_id'],'pl_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
                    }
                    $model_exppoints->saveExppointsLog('order',array('exp_memberid'=>$order_info['buyer_id'],'exp_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
                }
            } else {
                break;
            }
        }

        //24小时之内完成了实物订单送积分和经验值,每次最多处理5W个订单
        $model_order = Model('vr_order');
        $condition = array();
        $condition['finnshed_time'] = array('gt',TIMESTAMP - self::EXE_TIMES);
        for($i = 0; $i < 50000; $i=$i+100) {
            $order_list = $model_order->getOrderList($condition,'','buyer_name,buyer_id,order_amount,order_sn,order_id','', "{$i},100");
            if (!empty($order_list)) {
                foreach ($order_list as $order_info) {
                    if (C('points_isuse')) {
                        $model_points->savePointsLog('order',array('pl_memberid'=>$order_info['buyer_id'],'pl_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
                    }
                    $model_exppoints->saveExppointsLog('order',array('exp_memberid'=>$order_info['buyer_id'],'exp_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
                }
            } else {
                break;
            }
        }
    }
    
    /**
     * 代金券即将过期提醒
     */
    private function _voucher_will_expire() {
        $time_start = mktime(0, 0, 0, date("m")  , date("d")+self::VOUCHER_INTERVAL, date("Y"));
        $time_stop = $time_start + self::EXE_TIMES - 1;
        $where = array();
        $where['voucher_end_date'] = array(array('egt', $time_start), array('elt', $time_stop), 'and');
        $list = Model('voucher')->getVoucherUnusedList($where);
        if (!empty($list)) {
            foreach ($list as $val) {
                $param = array();
                $param['code'] = 'voucher_will_expire';
                $param['member_id'] = $val['voucher_owner_id'];
                $param['param'] = array(
                    'indate' => date('Y-m-d H:i:s', $val['voucher_end_date']),
                    'voucher_url' => urlMember('member_voucher', 'index')
                );
                QueueClient::push('sendMemberMsg', $param);
            }
        }
    }
    
    /**
     * 虚拟兑换码即将过期提醒
     */
    private function _vr_code_will_expire() {
        $time_start = mktime(0, 0, 0, date("m")  , date("d")+self::VR_CODE_INTERVAL, date("Y"));
        $time_stop = $time_start + self::EXE_TIMES - 1;
        $where = array();
        $where['vr_indate'] = array(array('egt', $time_start), array('elt', $time_stop), 'and');
        $list = Model('vr_order')->getCodeUnusedList($where,'order_id,min(buyer_id) as buyer_id,min(rec_id) as rec_id,min(vr_indate) as vr_indate');
        if (!empty($list)) {
            foreach ($list as $val) {
                $param = array();
                $param['code'] = 'vr_code_will_expire';
                $param['member_id'] = $val['buyer_id'];
                $param['param'] = array(
                    'indate' => date('Y-m-d H:i:s', $val['vr_indate']),
                    'vr_order_url' => urlShop('member_vr_order', 'index')
                );
                QueueClient::push('sendMemberMsg', $param);
            }
        }
    }

    /**
     * 订单超期后不允许评价
     */
    private function _order_eval_expire_update() {

        //实物订单超期未评价自动更新状态，每次最多更新1000个订单
        $model_order = Model('order');
        $condition = array();
        $condition['order_state'] = ORDER_STATE_SUCCESS;
        $condition['evaluation_state'] = 0;
        $condition['finnshed_time'] = array('lt',TIMESTAMP - self::ORDER_EVALUATE_TIME);
        $update = array();
        $update['evaluation_state'] = 2;
        $update['evaluation_again_state'] = 2;
        $update = $model_order->editOrder($update,$condition,1000);
        if (!$update) {
            $this->log('更新实物订单超期不能评价失败');
        }

        //虚拟订单超期未评价自动更新状态，每次最多更新1000个订单
        $model_order = Model('vr_order');
        $condition = array();
        $condition['order_state'] = ORDER_STATE_SUCCESS;
        $condition['evaluation_state'] = 0;
        $condition['use_state'] = 1;
        $condition['finnshed_time'] = array('lt',TIMESTAMP - self::ORDER_EVALUATE_TIME);
        $update = array();
        $update['evaluation_state'] = 2;
        $update = $model_order->editOrder($update,$condition,1000);
        if (!$update) {
            $this->log('更新虚拟订单超期不能评价失败');
        }
    }

    /**
     * 订单超期后不允许追加评价
     */
    private function _order_eval_again_expire_update() {
        //实物订单超期未评价自动更新状态，每次最多更新1000个订单
        $model_order = Model('order');
        $condition = array();
        $condition['order_state'] = ORDER_STATE_SUCCESS;
        $condition['evaluation_again_state'] = 0;
        $condition['finnshed_time'] = array('lt',TIMESTAMP - self::ORDER_EVALUATE_AGAIN_TIME);
        $update = array();
        $update['evaluation_again_state'] = 2;
        $update = $model_order->editOrder($update,$condition,1000);
        if (!$update) {
            $this->log('更新实物订单超期不能评价失败');
        }
    }

    /**
     * 门店自提订单、支付方式为门店支付的订单7天内未自提自动取消
     */
    private function _order_chain_timeout_cancel() {

        $_break = false;
        $model_order = Model('order');
        $logic_order = Logic('order');
        $condition = array();
        $condition['order_state'] = ORDER_STATE_NEW;
        $condition['payment_code'] = 'chain';
        $condition['add_time'] = array('lt',TIMESTAMP - 60*60*24*CHAIN_ORDER_PAYPUT_DAY);
        //分批，每批处理100个订单，最多处理1W个订单
        for ($i = 0; $i < 100; $i++){
            if ($_break) {
                break;
            }
            $order_list = $model_order->getOrderList($condition, '', '*', '', 100);
            if (empty($order_list)) break;
            foreach ($order_list as $order_info) {
                $result = $logic_order->changeOrderStateCancel($order_info,'system','系统','超期未提货系统自动关闭订单',false,array('order_state'=>ORDER_STATE_NEW));
                if (!$result['state']) {
                    $this->log('门店自提订单订单超期未提货关闭失败SN:'.$order_info['order_sn']); $_break = true; break;
                }
            }
        }
    }

    /**
     * 更新商品访问量(redis)
     */
    private function _goods_click_update() {
        $data = rcache('updateRedisDate', 'goodsClick');
        foreach ($data as $key => $val) {
            Model('goods')->editGoodsById(array('goods_click' => array('exp', 'goods_click +'.$val)), $key);
        }
        dcache('updateRedisDate', 'goodsClick');
    }

    /**
     * 更新商品促销到期状态(目前只有满即送)
     */
    private function _goods_promotion_state_update() {
        //满即送过期
        Model('p_mansong')->editExpireMansong();
    }

    /**
     * 商品到货通知提醒
     */
    private function _arrival_notice() {
        $strat_time = strtotime("-30 day"); // 只通知最近30天的记录
    
        $model_arrtivalnotice = Model('arrival_notice');
        // 删除30天之前的记录
        $model_arrtivalnotice->delArrivalNotice(array('an_addtime' => array('lt', $strat_time), 'an_type' => 1));
    
        $count = $model_arrtivalnotice->getArrivalNoticeCount(array());
        $times = ceil($count/self::ARRIVAL_NOTICE_NUM);
        if ($times == 0) return false;
        for ($i = 0; $i <= $times; $i++) {
    
            $notice_list = $model_arrtivalnotice->getArrivalNoticeList(array(), '*', $i.','.self::ARRIVAL_NOTICE_NUM);
            if (empty($notice_list)) continue;
    
            // 查询商品是否已经上架
            $goodsid_array = array();
            foreach ($notice_list as $val) {
                $goodsid_array[] = $val['goods_id'];
            }
            $goodsid_array = array_unique($goodsid_array);
            $goods_list = Model('goods')->getGoodsOnlineList(array('goods_id' => array('in', $goodsid_array), 'goods_storage' => array('gt', 0)), 'goods_id');
            if (empty($goods_list)) continue;
    
            // 需要通知到货的商品
            $goodsid_array = array();
            foreach ($goods_list as $val) {
                $goodsid_array[] = $val['goods_id'];
            }
    
            // 根据商品id重新查询需要通知的列表
            $notice_list = $model_arrtivalnotice->getArrivalNoticeList(array('goods_id' => array('in', $goodsid_array)), '*');
            if (empty($notice_list)) continue;
    
            foreach ($notice_list as $val) {
                $param = array();
                $param['code'] = 'arrival_notice';
                $param['member_id'] = $val['member_id'];
                $param['param'] = array(
                        'goods_name' => $val['goods_name'],
                        'goods_url' => urlShop('goods', 'index', array('goods_id' => $val['goods_id']))
                );
                $param['number'] = array('mobile' => $val['an_mobile'], 'email' => $val['an_email']);
                QueueClient::push('sendMemberMsg', $param);
            }
    
            // 清楚发送成功的数据
            $model_arrtivalnotice->delArrivalNotice(array('goods_id' => array('in', $goodsid_array)));
        }
    }

    /**
     * 缓存订单及订单商品相关数据
     */
    private function _order_goods_cache(){
        $model = Model('stat');
        //查询最后统计的记录
        $latest_record = $model->table('stat_ordergoods')->order('stat_updatetime desc,rec_id desc')->find();
        $stime = 0;
        if ($latest_record){
            $start_time = strtotime(date('Y-m-d',$latest_record['stat_updatetime']));
        } else {
            $start_time = strtotime(date('Y-m-d',strtotime(C('setup_date'))));//从系统的安装时间开始统计
        }
        for ($stime = $start_time; $stime < time(); $stime = $stime+86400){
            $etime = $stime + 86400 - 1;
            //避免重复统计，开始时间必须大于最后一条记录的记录时间
            $search_stime = $latest_record['stat_updatetime'] > $stime?$latest_record['stat_updatetime']:$stime;
            //统计一天的数据，如果结束时间大于当前时间，则结束时间为当前时间，避免因为查询时间的延迟造成数据遗落
            $search_etime = ($t = ($stime + 86400 - 1)) > time() ? time() : ($stime + 86400 - 1);
    
            //查询时间段内新订单或者更新过的订单，在缓存表中需要将新订单和更新过的订单进行重新缓存
            $where = array();
            $where['log_time'] = array('between',array($search_stime,$search_etime));
    
            //查询记录总条数
            $countnum_arr = $model->table('order_log')->field('COUNT(DISTINCT order_id) as countnum')->where($where)->find();
            $countnum = intval($countnum_arr['countnum']);

            for ($i=0; $i<$countnum; $i+=100){//每次查询100条
                $orderlog_list = array();
                $orderlog_list = $model->table('order_log')->field('DISTINCT order_id')->where($where)->limit($i.',100')->select();
                if ($orderlog_list){
                    //店铺ID数组
                    $storeid_arr = array();
    
                    //商品ID数组
                    $goodsid_arr = array();
    
                    //商品公共表ID数组
                    $goods_commonid_arr = array();
    
                    //订单ID数组
                    $orderid_arr = array();
    
                    //整理需要缓存的订单ID
                    foreach ((array)$orderlog_list as $k=>$v){
                        $orderid_arr[] = $v['order_id'];
                    }
                    unset($orderlog_list);
    
                    //查询订单数据
                    $field = 'order_id,order_sn,store_id,buyer_id,buyer_name,add_time,payment_code,order_amount,shipping_fee,evaluation_state,order_state,refund_state,refund_amount,order_from';
                    $order_list_tmp = $model->table('orders')->field($field)->where(array('order_id'=>array('in',$orderid_arr)))->select();
                    $order_list = array();
                    foreach ((array)$order_list_tmp as $k=>$v){
                        //判读订单是否计入统计（在线支付订单已支付或者经过退款的取消订单或者货到付款订单订单已成功）
                        $v['order_isvalid'] = 0;
                        if ($v['payment_code'] != 'offline' && $v['order_state'] != ORDER_STATE_NEW && $v['order_state'] != ORDER_STATE_CANCEL){//在线支付并且已支付并且未取消
                            $v['order_isvalid'] = 1;
                        } elseif ($v['order_state'] == ORDER_STATE_CANCEL && $v['refund_state'] != 0) {//经过退款的取消订单
                            $v['order_isvalid'] = 1;
                        } elseif ($v['payment_code'] == 'offline' && $v['order_state'] == ORDER_STATE_SUCCESS) {//货到付款订单，订单成功之后才计入统计
                            $v['order_isvalid'] = 1;
                        }
                        $order_list[$v['order_id']] = $v;
                        $storeid_arr[] = $v['store_id'];
                    }
                    unset($order_list_tmp);
    
                    //查询订单扩展数据
                    $field = 'order_id,reciver_province_id';
                    $order_common_list_tmp = $model->table('order_common')->field($field)->where(array('order_id'=>array('in',$orderid_arr)))->select();
                    $order_common_list = array();
                    foreach ((array)$order_common_list_tmp as $k=>$v){
                        $order_common_list[$v['order_id']] = $v;
                    }
                    unset($order_common_list_tmp);
    
                    //查询店铺信息
                    $field = 'store_id,store_name,grade_id,sc_id';
                    $store_list_tmp = $model->table('store')->field($field)->where(array('store_id'=>array('in',$storeid_arr)))->select();
                    $store_list = array();
                    foreach ((array)$store_list_tmp as $k=>$v){
                        $store_list[$v['store_id']] = $v;
                    }
                    unset($store_list_tmp);
    
                    //查询订单商品
                    $field = 'rec_id,order_id,goods_id,goods_name,goods_price,goods_num,goods_image,goods_pay_price,store_id,buyer_id,goods_type,promotions_id,commis_rate,gc_id';
                    $ordergoods_list = $model->table('order_goods')->field($field)->where(array('order_id'=>array('in',$orderid_arr)))->select();
                    foreach ((array)$ordergoods_list as $k=>$v){
                        $goodsid_arr[] = $v['goods_id'];
                    }
    
                    //查询商品信息
                    $field = 'goods_id,goods_commonid,goods_price,goods_serial,gc_id,gc_id_1,gc_id_2,gc_id_3,goods_image';
                    $goods_list_tmp = $model->table('goods')->field($field)->where(array('goods_id'=>array('in',$goodsid_arr)))->select();
                    foreach ((array)$goods_list_tmp as $k=>$v){
                        $goods_commonid_arr[] = $v['goods_commonid'];
                    }
    
                    //查询商品公共信息
                    $field = 'goods_commonid,goods_name,brand_id,brand_name';
                    $goods_common_list_tmp = $model->table('goods_common')->field($field)->where(array('goods_commonid'=>array('in',$goods_commonid_arr)))->select();
                    $goods_common_list = array();
                    foreach ((array)$goods_common_list_tmp as $k=>$v){
                        $goods_common_list[$v['goods_commonid']] = $v;
                    }
                    unset($goods_common_list_tmp);
    
                    //处理商品数组
                    $goods_list = array();
    
                    foreach ((array)$goods_list_tmp as $k=>$v){
                        $v['goods_commonname'] = $goods_common_list[$v['goods_commonid']]['goods_name'];
                        $v['brand_id'] = $goods_common_list[$v['goods_commonid']]['brand_id'];
                        $v['brand_name'] = $goods_common_list[$v['goods_commonid']]['brand_name'];
                        $goods_list[$v['goods_id']] = $v;
                    }
                    unset($goods_list_tmp);
    
                    //查询订单缓存是否存在，存在则删除
                    $model->table('stat_ordergoods')->where(array('order_id'=>array('in',$orderid_arr)))->delete();
                    //查询订单缓存是否存在，存在则删除
                    $model->table('stat_order')->where(array('order_id'=>array('in',$orderid_arr)))->delete();
    
                    //整理新增数据
                    $ordergoods_insert_arr = array();
                    foreach ((array)$ordergoods_list as $k=>$v){
                        $tmp = array();
                        $tmp['rec_id'] = $v['rec_id'];
                        $tmp['stat_updatetime'] = $search_etime;
                        $tmp['order_id'] = $v['order_id'];
                        $tmp['order_sn'] = $order_list[$v['order_id']]['order_sn'];
                        $tmp['order_add_time'] = $order_list[$v['order_id']]['add_time'];
                        $tmp['payment_code'] = $order_list[$v['order_id']]['payment_code'];
                        $tmp['order_amount'] = $order_list[$v['order_id']]['order_amount'];
                        $tmp['shipping_fee'] = $order_list[$v['order_id']]['shipping_fee'];
                        $tmp['evaluation_state'] = $order_list[$v['order_id']]['evaluation_state'];
                        $tmp['order_state'] = $order_list[$v['order_id']]['order_state'];
                        $tmp['refund_state'] = $order_list[$v['order_id']]['refund_state'];
                        $tmp['refund_amount'] = $order_list[$v['order_id']]['refund_amount'];
                        $tmp['order_from'] = $order_list[$v['order_id']]['order_from'];
                        $tmp['order_isvalid'] = $order_list[$v['order_id']]['order_isvalid'];
                        $tmp['reciver_province_id'] = $order_common_list[$v['order_id']]['reciver_province_id'];
                        $tmp['store_id'] = $v['store_id'];
                        $tmp['store_name'] = $store_list[$v['store_id']]['store_name'];
                        $tmp['grade_id'] = $store_list[$v['store_id']]['grade_id'];
                        $tmp['sc_id'] = $store_list[$v['store_id']]['sc_id'];
                        $tmp['buyer_id'] = $order_list[$v['order_id']]['buyer_id'];
                        $tmp['buyer_name'] = $order_list[$v['order_id']]['buyer_name'];
                        $tmp['goods_id'] = $v['goods_id'];
                        $tmp['goods_name'] = $v['goods_name'];
                        $tmp['goods_commonid'] = intval($goods_list[$v['goods_id']]['goods_commonid']);
                        $tmp['goods_commonname'] = ($t = $goods_list[$v['goods_id']]['goods_commonname'])?$t:$v['goods_name'];
                        $tmp['gc_id'] = intval($goods_list[$v['goods_id']]['gc_id']);
                        $tmp['gc_parentid_1'] = intval($goods_list[$v['goods_id']]['gc_id_1']);
                        $tmp['gc_parentid_2'] = intval($goods_list[$v['goods_id']]['gc_id_2']);
                        $tmp['gc_parentid_3'] = intval($goods_list[$v['goods_id']]['gc_id_3']);
                        $tmp['brand_id'] = intval($goods_list[$v['goods_id']]['brand_id']);
                        $tmp['brand_name'] = ($t = $goods_list[$v['goods_id']]['brand_name'])?$t:'';
                        $tmp['goods_serial'] = ($t = $goods_list[$v['goods_id']]['goods_serial'])?$t:'';
                        $tmp['goods_price'] = $v['goods_price'];
                        $tmp['goods_num'] = $v['goods_num'];
                        $tmp['goods_image'] = $goods_list[$v['goods_id']]['goods_image'];
                        $tmp['goods_pay_price'] = $v['goods_pay_price'];
                        $tmp['goods_type'] = $v['goods_type'];
                        $tmp['promotions_id'] = $v['promotions_id'];
                        $tmp['commis_rate'] = $v['commis_rate'];
                        $ordergoods_insert_arr[] = $tmp;
                    }
                    $model->table('stat_ordergoods')->insertAll($ordergoods_insert_arr);
                    $order_insert_arr = array();
    
                    foreach ((array)$order_list as $k=>$v){
                        $tmp = array();
                        $tmp['order_id'] = $v['order_id'];
                        $tmp['order_sn'] = $v['order_sn'];
                        $tmp['order_add_time'] = $v['add_time'];
                        $tmp['payment_code'] = $v['payment_code'];
                        $tmp['order_amount'] = $v['order_amount'];
                        $tmp['shipping_fee'] = $v['shipping_fee'];
                        $tmp['evaluation_state'] = $v['evaluation_state'];
                        $tmp['order_state'] = $v['order_state'];
                        $tmp['refund_state'] = $v['refund_state'];
                        $tmp['refund_amount'] = $v['refund_amount'];
                        $tmp['order_from'] = $v['order_from'];
                        $tmp['order_isvalid'] = $v['order_isvalid'];
                        $tmp['reciver_province_id'] = $order_common_list[$v['order_id']]['reciver_province_id'];
                        $tmp['store_id'] = $v['store_id'];
                        $tmp['store_name'] = $store_list[$v['store_id']]['store_name'];
                        $tmp['grade_id'] = $store_list[$v['store_id']]['grade_id'];
                        $tmp['sc_id'] = $store_list[$v['store_id']]['sc_id'];
                        $tmp['buyer_id'] = $v['buyer_id'];
                        $tmp['buyer_name'] = $v['buyer_name'];
                        $order_insert_arr[] = $tmp;
                    }
                    $model->table('stat_order')->insertAll($order_insert_arr);
                }
            }
        }
    }
    
    /**
     * 会员相关数据统计
     */
    private function _member_stat(){
        $model = Model('stat');
        //查询最后统计的记录
        $latest_record = $model->getOneStatmember(array(), '', 'statm_id desc');
        $stime = 0;
        if ($latest_record){
            $start_time = strtotime(date('Y-m-d',$latest_record['statm_updatetime']));
        } else {
            $start_time = strtotime(date('Y-m-d',strtotime(C('setup_date'))));//从系统的安装时间开始统计
        }
        $j = 1;
        for ($stime = $start_time; $stime < time(); $stime = $stime+86400){
            //数据库更新数据数组
            $insert_arr = array();
            $update_arr = array();
        
            //结束时间
            $etime = $stime + 86400 - 1;
        
            //统计订单下单量和下单金额
            $field = ' orders.order_id,orders.add_time,orders.buyer_id,orders.buyer_name,orders.order_amount,order_log.log_orderstate,orders.payment_code';
            $where = array();
            $where['orders.order_state'] = array(array('neq',ORDER_STATE_NEW), array('neq',ORDER_STATE_CANCEL),'and');//去除未支付和已取消订单
            $where['order_log.log_time'] = array('between',array($stime,$etime));//按照订单付款的操作时间统计
            //货到付款当交易成功进入统计，非货到付款当付款后进入统计
            $where['payment_code'] = array('exp',"(orders.payment_code='offline' and order_log.log_orderstate = '".ORDER_STATE_SUCCESS."') or (orders.payment_code<>'offline' and order_log.log_orderstate = '".ORDER_STATE_PAY."' )");
            $orderlist_tmp = array();
            $orderlist_tmp = $model->statByOrderLog($where, $field, 0, 0, 'order_id');//此处由于底层的限制，仅能查询1000条，如果日下单量大于1000，则需要limit的支持
        
            $order_list = array();
            $orderid_list = array();
            foreach ((array)$orderlist_tmp as $k=>$v){
                if (($v['payment_code']<>'offline' && $v['log_orderstate'] == ORDER_STATE_PAY) || ($v['payment_code'] == 'offline' && $v['log_orderstate'] == ORDER_STATE_SUCCESS)){
                    $addtime = strtotime(date('Y-m-d',$v['add_time']));
                    if ($addtime != $stime){//订单如果隔天支付的话，需要进行统计数据更新
                        if (!$update_arr[$addtime][$v['buyer_id']]){
                            $update_arr[$addtime][$v['buyer_id']] = $v['buyer_name'];
                        }
                    } else {
                        $order_list[$v['buyer_id']]['buyer_name'] = $v['buyer_name'];
                        $order_list[$v['buyer_id']]['ordernum'] = intval($order_list[$v['buyer_id']]['ordernum']) + 1;
                        $order_list[$v['buyer_id']]['orderamount'] = floatval($order_list[$v['buyer_id']]['orderamount']) + (($t = floatval($v['order_amount'])) > 0?$t:0);
                    }
                    //记录订单ID数组
                    $orderid_list[] = $v['order_id'];
                }
            }
        
            //统计下单商品件数
            $ordergoods_tmp = array();
            $ordergoods_list = array();
            if ($orderid_list && count($orderid_list) > 0){
                $field = ' orders.add_time,orders.buyer_id,orders.buyer_name,order_goods.goods_num ';
                $where = array();
                $where['orders.order_id'] = array('in',$orderid_list);
                $ordergoods_tmp = $model->statByOrderGoods($where, $field, 0, 0, 'orders.order_id');
                foreach ((array)$ordergoods_tmp as $k=>$v){
                    $addtime = strtotime(date('Y-m-d',$v['add_time']));
                    if ($addtime != $stime){//订单如果隔天支付的话，需要进行统计数据更新
        
                    } else {
                        $ordergoods_list[$v['buyer_id']]['goodsnum'] = $ordergoods_list[$v['buyer_id']]['goodsnum'] + (($t = floatval($v['goods_num'])) > 0?$t:0);
                    }
                }
            }
        
            //统计的预存款记录
            if (C('dbdriver') == 'mysqli') {
                $field = ' lg_member_id,min(lg_member_name) as lg_member_name,SUM(IF(lg_av_amount>=0,lg_av_amount,0)) as predincrease, SUM(IF(lg_av_amount<=0,lg_av_amount,0)) as predreduce ';
            } elseif (C('dbdriver') == 'oracle') {
                $field = ' lg_member_id,min(lg_member_name) as lg_member_name,SUM((case when lg_av_amount>=0 then lg_av_amount else 0 end)) as predincrease, SUM((case when lg_av_amount<=0 then lg_av_amount else 0 end)) as predreduce ';
            }
            
            $where = array();
            $where['lg_add_time'] = array('between',array($stime,$etime));
            $predeposit_tmp = $model->getPredepositInfo($where, $field, 0, 'lg_member_id', 0, 'lg_member_id');
            $predeposit_list = array();
            foreach ((array)$predeposit_tmp as $k=>$v){
                $predeposit_list[$v['lg_member_id']] = $v;
            }

            //统计的积分记录
            if (C('dbdriver') == 'mysqli') {
                $field = ' pl_memberid,min(pl_membername) as pl_membername,SUM(IF(pl_points>=0,pl_points,0)) as pointsincrease, SUM(IF(pl_points<=0,pl_points,0)) as pointsreduce ';
            } elseif (C('dbdriver') == 'oracle') {
                $field = ' pl_memberid,min(pl_membername) as pl_membername,SUM((case when pl_points>=0 then pl_points else 0 end)) as pointsincrease, SUM((case when pl_points<=0 then pl_points else 0 end)) as pointsreduce ';
            }

            $where = array();
            $where['pl_addtime'] = array('between',array($stime,$etime));
            $points_tmp = $model->statByPointslog($where, $field, 0, 0, '', 'pl_memberid');
            $points_list = array();
            foreach ((array)$points_tmp as $k=>$v){
                $points_list[$v['pl_memberid']] = $v;
            }
        
            //处理需要更新的数据
            foreach ((array)$update_arr as $k=>$v){
                foreach ($v as $m_k=>$m_v){
                    //查询的时间段
                    $up_stime = $k;
                    $up_etime = $up_stime + 86400 - 1;
        
                    //查询时间时间段内的订单总数和订单总金额
                    $where = array();
                    $where['order_state'] = array(array('neq',ORDER_STATE_NEW), array('neq',ORDER_STATE_CANCEL),'and');//去除未支付和已取消订单
                    $where['add_time'] = array('between',array($up_stime,$up_etime));
                    //货到付款当交易成功进入统计，非货到付款当付款后进入统计
                    $where['payment_code'] = array('exp',"(payment_code='offline' and order_state = '".ORDER_STATE_SUCCESS."') or (payment_code<>'offline')");
                    $orderlist_amount = $model->statByOrder($where, 'SUM(order_amount) as amount,COUNT(*) as num', 0, 0, 'order_id');
        
                    //查询时间时间段内的下单商品件数
                    $where = array();
                    $where['orders.order_state'] = array(array('neq',ORDER_STATE_NEW), array('neq',ORDER_STATE_CANCEL),'and');//去除未支付和已取消订单
                    $where['orders.add_time'] = array('between',array($up_stime,$up_etime));
                    //货到付款当交易成功进入统计，非货到付款当付款后进入统计
                    $where['orders.payment_code'] = array('exp',"(orders.payment_code='offline' and orders.order_state = '".ORDER_STATE_SUCCESS."') or (orders.payment_code<>'offline')");
                    $ordergoods_amount = $model->statByOrderGoods($where, 'SUM(order_goods.goods_num) as gnum');
        
                    //查询记录是否存在
                    $statmember_info = $model->getOneStatmember(array('statm_time'=>$k,'statm_memberid'=>$m_k));
        
                    if ($statmember_info){
                        //构造更新数组
                        $m_v = array();
                        $m_v['statm_ordernum'] = $orderlist_amount[0]['num'];
                        $m_v['statm_orderamount'] = floatval($orderlist_amount[0]['amount']);
                        $m_v['statm_goodsnum'] = floatval($ordergoods_amount[0]['gnum']);
                        $m_v['statm_updatetime'] = $stime;
                        $model->updateStatmember(array('statm_time'=>$k,'statm_memberid'=>$m_k),$m_v);
                    } else {
                        $tmp = array();
                        $tmp['statm_memberid'] = $m_k;
                        $tmp['statm_membername'] = $m_v;
                        $tmp['statm_time'] = $k;
                        $tmp['statm_updatetime'] = $stime;
                        $tmp['statm_ordernum'] = intval($orderlist_amount[0]['num']);
                        $tmp['statm_orderamount'] = floatval($orderlist_amount[0]['amount']);
                        $tmp['statm_goodsnum'] = intval($ordergoods_amount[0]['gnum']);
                        $tmp['statm_predincrease'] = 0;
                        $tmp['statm_predreduce'] = 0;
                        $tmp['statm_pointsincrease'] = 0;
                        $tmp['statm_pointsreduce'] = 0;
                        $insert_arr[] = $tmp;
                    }
                    unset($statmember_info);
                }
            }
        
            //处理获得所有会员ID数组
            $memberidarr_order = $order_list?array_keys($order_list):array();
            $memberidarr_ordergoods = $ordergoods_list?array_keys($ordergoods_list):array();
            $memberidarr_predeposit = $predeposit_list?array_keys($predeposit_list):array();
            $memberidarr_points = $points_list?array_keys($points_list):array();
            $memberid_arr = array_merge($memberidarr_order,$memberidarr_ordergoods,$memberidarr_predeposit,$memberidarr_points);
            //查询会员信息
            $memberid_list = Model('member')->getMemberList(array('member_id'=>array('in',$memberid_arr)), '', 0);
        
            foreach ((array)$memberid_list as $k=>$v){
                $tmp = array();
                $tmp['statm_memberid'] = $v['member_id'];
                $tmp['statm_membername'] = $v['member_name'];
                $tmp['statm_time'] = $stime;
                $tmp['statm_updatetime'] = $stime;
                //因为记录可能已经存在，所以加上之前的统计记录
                $tmp['statm_ordernum'] = (($t = intval($order_list[$tmp['statm_memberid']]['ordernum'])) > 0?$t:0);
                $tmp['statm_orderamount'] = (($t = floatval($order_list[$tmp['statm_memberid']]['orderamount']))>0?$t:0);
                $tmp['statm_goodsnum'] = (($t = intval($ordergoods_list[$tmp['statm_memberid']]['goodsnum']))?$t:0);
                $tmp['statm_predincrease'] = (($t = floatval($predeposit_list[$tmp['statm_memberid']]['predincrease']))?$t:0);
                $tmp['statm_predreduce'] = (($t = floatval($predeposit_list[$tmp['statm_memberid']]['predreduce']))?$t:0);
                $tmp['statm_pointsincrease'] = (($t = intval($points_list[$tmp['statm_memberid']]['pointsincrease']))?$t:0);
                $tmp['statm_pointsreduce'] = (($t = intval($points_list[$tmp['statm_memberid']]['pointsreduce']))?$t:0);
                $insert_arr[] = $tmp;
            }
        
            //删除旧的统计数据
            $model->delByStatmember(array('statm_time'=>$stime));
            $model->table('stat_member')->insertAll($insert_arr);
        }
    }
    
    /**
     * 取消无货后门店商品的标记
     * @return boolean
     */
    private function _delivery_goods_sign_update() {
        $list = Model('chain_stock')->getChainStockList(array(''), 'sum(stock) as stock_sum,goods_id', 0, 'stock_sum asc', 'goods_id');
        if (empty($list)) {
            return true;
        }
        $goods_ids = array();
        foreach ($list as $val) {
            if ($val['stock_sum'] <= 0) {
                $goods_ids[] = $val['goods_id'];
            } else {
                break;
            }
        }
        
        Model('goods')->editGoodsById(array('is_chain' => 0), $goods_ids);
        return true;
    }

    private function _create_bill() {
        $this->_model_store = Model('store');
        $this->_model_store_ext = Model('store_extend');
        $this->_model_bill = Model('bill');
        $this->_model_order = Model('order');
        $this->_model_store_cost = Model('store_cost');
        $this->_model_vr_bill = Model('vr_bill');
        $this->_model_vr_order = Model('vr_order');
    
        //更新订单商品佣金值
        $this->_order_commis_rate_update();
    
        //实物订单结算
        $this->_real_order();
    
        //虚拟订单结算
        $this->_vr_order();
    
    }
    
    /**
     * 生成上月账单[实物订单]
     * 考虑到老版本，判断 一下有没有ID为1的店铺，如果没有，则向表中插入一条ID:1的记录。
     * 从店铺扩展表中得取所有店铺结算周期设置，循环逐个生成每个店铺结算单。
     * 如果值为0，则还是按月结算流程，如果值大于0，则按 X天周期结算。
     */
    private function _real_order() {
    
        //向前兼容
        $this->_model_store_ext = Model('store_extend');
        if (!$this->_model_store_ext->getStoreExtendInfo(array('store_id'=>1))) {
            $this->_model_store_ext->addStoreExtend(array('store_id'=>1));
        }
    
        $count = $this->_model_store_ext->getStoreExtendCount();
    
        $step_num = 100;
        for ($i = 0; $i <= $count; $i = $i + $step_num){
            //每次取出100个店铺信息
            $store_list = $this->_model_store_ext->getStoreExendList(array(),"{$i},{$step_num}");
            if (is_array($store_list) && $store_list) {
                foreach ($store_list as $kk => $store_info) {
                    $start_time = $this->_get_start_date($store_info['store_id']);
                    if ($start_time !== 0) {
                        if ($store_info['bill_cycle']) {
                            $this->_create_bill_cycle_by_day($start_time, $store_info);
                        } else {
                            $this->_create_bill_cycle_by_month($start_time, $store_info);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * 结算周期为月结
     * @param unknown $start_time
     * @param unknown $store_info
     */
    private function _create_bill_cycle_by_month($start_unixtime,$store_info) {
        $i = 1;
        $start_unixtime = strtotime(date('Y-m-01 00:00:00', $start_unixtime));
        $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));
        while (($time = strtotime('-'.$i.' month',$current_time)) >= $start_unixtime) {
            if (date('Ym',$start_unixtime) == date('Ym',$time)) {
                //如果两个月份相等检查库是里否存在
                $order_statis = Model()->cls()->table('bill_create')->where(array('os_month'=>date('Ym',$start_unixtime),'store_id'=>$store_info['store_id'],'os_type'=>0))->find();
                if ($order_statis) {
                    break;
                }
            }
            //该月第一天0时unix时间戳
            $first_day_unixtime = strtotime(date('Y-m-01 00:00:00', $time));
            //该月最后一天最后一秒时unix时间戳
            $last_day_unixtime = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");
            $os_month = date('Ym',$first_day_unixtime);
    
            try {
                $this->_model_order->beginTransaction();
                //生成单个店铺月订单出账单
                $data = array();
                $data['ob_store_id'] = $store_info['store_id'];
                $data['ob_start_date'] = $first_day_unixtime;
                $data['ob_end_date'] = $last_day_unixtime;
                $this->_create_real_order_bill($data);
    
                $data = array();
                $data['os_month'] = $os_month;
                $data['os_type'] = 0;
                $data['store_id'] = $store_info['store_id'];
                Model()->cls()->table('bill_create')->insert($data);
    
                $this->_model_order->commit();
            } catch (Exception $e) {
                $this->log('实物账单:'.$e->getMessage());
                $this->_model_order->rollback();
            }
            $i++;
        }
    }
    
    /**
     * 结算周期为X天结算
     * @param unknown $start_time
     * @param unknown $store_info
     */
    private function _create_bill_cycle_by_day($start_unixtime,$store_info) {
        $i = $store_info['bill_cycle']-1;
        $start_unixtime = strtotime(date('Y-m-d 00:00:00', $start_unixtime));
        $current_time = strtotime(date('Y-m-d 00:00:00',TIMESTAMP));
        while (($time = strtotime('+'.$i.' day',$start_unixtime)) < $current_time) {
            $first_day_unixtime = strtotime(date('Y-m-d 00:00:00', $start_unixtime));    //开始那天0时unix时间戳
            $last_day_unixtime = strtotime(date('Y-m-d 23:59:59', $time)); //结束那天最后一秒时unix时间戳
            $data = array();
            $data['os_start_date'] = $first_day_unixtime;
            $data['os_end_date'] = $last_day_unixtime;
    
            try {
                $this->_model_order->beginTransaction();
                //生成单个店铺订单出账单
                $data = array();
                $data['ob_store_id'] = $store_info['store_id'];
                $data['ob_start_date'] = $first_day_unixtime;
                $data['ob_end_date'] = $last_day_unixtime;
                $this->_create_real_order_bill($data);
    
                $this->_model_order->commit();
            } catch (Exception $e) {
                $this->log('实物账单:'.$e->getMessage());
                $this->_model_order->rollback();
            }
            $start_unixtime = strtotime(date('Y-m-d 00:00:00', $last_day_unixtime+86400));
        }
    }
    
    /**
     * 结算周期为X天结算
     * @param unknown $start_time
     * @param unknown $store_info
     */
    private function _create_vr_bill_cycle_by_day($start_unixtime,$store_info) {
        $i = $store_info['bill_cycle']-1;
        $start_unixtime = strtotime(date('Y-m-d 00:00:00', $start_unixtime));
        $current_time = strtotime(date('Y-m-d 00:00:00',TIMESTAMP));
        while (($time = strtotime('+'.$i.' day',$start_unixtime)) < $current_time) {
            $first_day_unixtime = strtotime(date('Y-m-d 00:00:00', $start_unixtime));    //开始那天0时unix时间戳
            $last_day_unixtime = strtotime(date('Y-m-d 23:59:59', $time)); //结束那天最后一秒时unix时间戳
            $data = array();
            $data['os_start_date'] = $first_day_unixtime;
            $data['os_end_date'] = $last_day_unixtime;
    
            try {
                $this->_model_vr_order->beginTransaction();
                //生成单个店铺订单出账单
                $data = array();
                $data['ob_store_id'] = $store_info['store_id'];
                $data['ob_start_date'] = $first_day_unixtime;
                $data['ob_end_date'] = $last_day_unixtime;
                $this->_create_vr_order_bill($data);
    
                $this->_model_vr_order->commit();
            } catch (Exception $e) {
                $this->log('虚拟账单:'.$e->getMessage());
                $this->_model_vr_order->rollback();
            }
            $start_unixtime = strtotime(date('Y-m-d 00:00:00', $last_day_unixtime+86400));
        }
    }
    
    /**
     * 取得结算开始时间
     * 从order_bill表中取该店铺结算单中最大的ob_end_date作为本次结算开始时间
     * 如果未找到结算单，则查询该店铺订单表(已经完成状态)和店铺费用表，把里面时间较早的那个作为本次结算开始时间
     * @param int $store_id
     */
    private function _get_start_date($store_id) {
        $bill_info = $this->_model_bill->getOrderBillInfo(array('ob_store_id'=>$store_id),'max(ob_end_date) as stime');
        $start_unixtime = 0;
        if ($bill_info['stime']){
            $start_unixtime = $bill_info['stime']+1;
        } else {
            $condition = array();
            $condition['order_state'] = ORDER_STATE_SUCCESS;
            $condition['store_id'] = $store_id;
            $condition['finnshed_time'] = array('gt',0);
            $order_info = $this->_model_order->getOrderInfo($condition,array(),'min(finnshed_time) as stime');
            $condition = array();
            $condition['cost_store_id'] = $store_id;
            $condition['cost_state'] = 0;
            $condition['cost_time'] = array('gt',0);
            $cost_info = $this->_model_store_cost->getStoreCostInfo($condition,'min(cost_time) as stime');
            if ($order_info['stime']) {
                if ($cost_info['stime']) {
                    $start_unixtime = $order_info['stime'] < $cost_info['stime'] ? $order_info['stime'] : $cost_info['stime'];
                } else {
                    $start_unixtime = $order_info['stime'];
                }
            } else {
                if ($cost_info['stime']) {
                    $start_unixtime = $cost_info['stime'];
                }
            }
            if ($start_unixtime) {
                $start_unixtime = strtotime(date('Y-m-d 00:00:00', $start_unixtime));
            }
        }
        return $start_unixtime;
    }
    
    /**
     * 取得结算开始时间
     * 从vr_order_bill表中取该店铺结算单中最大的ob_end_date作为本次结算开始时间
     * 如果未找到结算单，则查询该店铺订单表(已经完成状态)的订单最小时间作为本次结算开始时间
     * @param int $store_id
     */
    private function _get_vr_start_date($store_id) {
        $bill_info = $this->_model_vr_bill->getOrderBillInfo(array('ob_store_id'=>$store_id),'max(ob_end_date) as stime');
        $start_unixtime = 0;
        if ($bill_info['stime']){
            $start_unixtime = $bill_info['stime']+1;
        } else {
            $condition = array();
            $condition['order_state'] = array('in',array(ORDER_STATE_PAY,ORDER_STATE_SUCCESS));
            $condition['store_id'] = $store_id;
            $order_info = $this->_model_vr_order->getOrderInfo($condition,'min(add_time) as stime');
            if ($order_info['stime']) {
                $start_unixtime = $order_info['stime'];
            }
            if ($start_unixtime) {
                $start_unixtime = strtotime(date('Y-m-d 00:00:00', $start_unixtime));
            }
        }
        return $start_unixtime;
    }
    
    /**
     * 生成单个店铺订单出账单[实物订单]
     *
     * @param int $data
     */
    private function _create_real_order_bill($data){
        $data_bill['ob_start_date'] = $data['ob_start_date'];
        $data_bill['ob_end_date'] = $data['ob_end_date'];
        $data_bill['ob_state'] = 0;
        $data_bill['ob_store_id'] = $data['ob_store_id'];
        if (!$this->_model_bill->getOrderBillInfo(array('ob_store_id'=>$data['ob_store_id'],'ob_start_date'=>$data['ob_start_date']))) {
            $insert = $this->_model_bill->addOrderBill($data_bill);
            if (!$insert) {
                throw new Exception('生成账单失败');
            }
            //对已生成空账单进行销量、退单、佣金统计
            $data_bill['ob_id'] = $insert;
            $update = $this->_calc_real_order_bill($data_bill);
            if (!$update){
                throw new Exception('更新账单失败');
            }

            // 发送店铺消息
            $param = array();
            $param['code'] = 'store_bill_affirm';
            $param['store_id'] = $data_bill['ob_store_id'];
            $param['param'] = array(
                    'state_time' => date('Y-m-d H:i:s', $data_bill['ob_start_date']),
                    'end_time' => date('Y-m-d H:i:s', $data_bill['ob_end_date']),
                    'bill_no' => $data_bill['ob_id']
            );
            QueueClient::push('sendStoreMsg', $param);
        }
    }
    
    /**
     * 计算某月内，某店铺的销量，退单量，佣金[实物订单]
     *
     * @param array $data_bill
     */
    private function _calc_real_order_bill($data_bill){
    
        $order_condition = array();
        $order_condition['order_state'] = ORDER_STATE_SUCCESS;
        $order_condition['store_id'] = $data_bill['ob_store_id'];
        $order_condition['finnshed_time'] = array('between',"{$data_bill['ob_start_date']},{$data_bill['ob_end_date']}");
    
        $update = array();
    
        //订单金额
        $fields = 'sum(order_amount) as order_amount,sum(rpt_amount) as rpt_amount,sum(shipping_fee) as shipping_amount,min(store_name) as store_name';
        $order_info =  $this->_model_order->getOrderInfo($order_condition,array(),$fields);
        $update['ob_order_totals'] = floatval($order_info['order_amount']);
    
        //红包
        $update['ob_rpt_amount'] = floatval($order_info['rpt_amount']);
    
        //运费
        $update['ob_shipping_totals'] = floatval($order_info['shipping_amount']);
        //店铺名字
        $store_info = $this->_model_store->getStoreInfoByID($data_bill['ob_store_id']);
        $update['ob_store_name'] = $store_info['store_name'];
    
        //佣金金额
        $order_info =  $this->_model_order->getOrderInfo($order_condition,array(),'count(DISTINCT order_id) as count');
        $order_count = $order_info['count'];
        $commis_rate_totals_array = array();
        //分批计算佣金，最后取总和
        for ($i = 0; $i <= $order_count; $i = $i + 300){
            $order_list = $this->_model_order->getOrderList($order_condition,'','order_id','',"{$i},300");
            $order_id_array = array();
            foreach ($order_list as $order_info) {
                $order_id_array[] = $order_info['order_id'];
            }
            if (!empty($order_id_array)){
                $order_goods_condition = array();
                $order_goods_condition['order_id'] = array('in',$order_id_array);
                $field = 'SUM(ROUND(goods_pay_price*commis_rate/100,2)) as commis_amount';
                $order_goods_info = $this->_model_order->getOrderGoodsInfo($order_goods_condition,$field);
                $commis_rate_totals_array[] = $order_goods_info['commis_amount'];
            }else{
                $commis_rate_totals_array[] = 0;
            }
        }
        $update['ob_commis_totals'] = floatval(array_sum($commis_rate_totals_array));
    
        //退款总额
        $model_refund = Model('refund_return');
        $refund_condition = array();
        $refund_condition['seller_state'] = 2;
        $refund_condition['store_id'] = $data_bill['ob_store_id'];
        $refund_condition['goods_id'] = array('gt',0);
        $refund_condition['admin_time'] = array(array('egt',$data_bill['ob_start_date']),array('elt',$data_bill['ob_end_date']),'and');
        $refund_info = $model_refund->getRefundReturnInfo($refund_condition,'sum(refund_amount) as refund_amount,sum(rpt_amount) as rpt_amount');
        $update['ob_order_return_totals'] = floatval($refund_info['refund_amount']);
    
        //全部退款时的红包
        $update['ob_rf_rpt_amount'] = floatval($refund_info['rpt_amount']);
    
        //退款佣金
        $refund  =  $model_refund->getRefundReturnInfo($refund_condition,'sum(ROUND(refund_amount*commis_rate/100,2)) as amount');
        if ($refund) {
            $update['ob_commis_return_totals'] = floatval($refund['amount']);
        } else {
            $update['ob_commis_return_totals'] = 0;
        }
    
        //店铺活动费用
        $model_store_cost = Model('store_cost');
        $cost_condition = array();
        $cost_condition['cost_store_id'] = $data_bill['ob_store_id'];
        $cost_condition['cost_state'] = 0;
        $cost_condition['cost_time'] = array(array('egt',$data_bill['ob_start_date']),array('elt',$data_bill['ob_end_date']),'and');
        $cost_info = $model_store_cost->getStoreCostInfo($cost_condition,'sum(cost_price) as cost_amount');
        $update['ob_store_cost_totals'] = floatval($cost_info['cost_amount']);
    
        //已经被取消的预定订单但未退还定金金额
        $model_order_book = Model('order_book');
        $condition = array();
        $condition['book_store_id'] = $data_bill['ob_store_id'];
        $condition['book_cancel_time'] = array('between',"{$data_bill['ob_start_date']},{$data_bill['ob_end_date']}");
        $order_book_info = $model_order_book->getOrderBookInfo($condition,'sum(book_real_pay) as pay_amount');
        $update['ob_order_book_totals'] = floatval($order_book_info['pay_amount']);
    
        //本期应结
        $update['ob_result_totals'] = $update['ob_order_totals'] + $update['ob_rpt_amount'] + $update['ob_order_book_totals'] - $update['ob_order_return_totals'] -
        $update['ob_commis_totals'] + $update['ob_commis_return_totals']- $update['ob_rf_rpt_amount'] - $update['ob_store_cost_totals'];
        $update['ob_store_cost_totals'] ;
        $update['ob_create_date'] = TIMESTAMP;
        $update['ob_state'] = 1;
        $update['os_month'] = date('Ym',$data_bill['ob_end_date']+1);
        return $this->_model_bill->editOrderBill($update,array('ob_id'=>$data_bill['ob_id']));
    }
    
    /**
     * 生成账单[虚拟订单]
     */
    private function _vr_order() {
        $count = $this->_model_store_ext->getStoreExtendCount();
    
        $step_num = 100;
        for ($i = 0; $i <= $count; $i = $i + $step_num){
            //每次取出100个店铺信息
            $store_list = $this->_model_store_ext->getStoreExendList(array(),"{$i},{$step_num}");
            if (is_array($store_list) && $store_list) {
                foreach ($store_list as $kk => $store_info) {
                    $start_time = $this->_get_vr_start_date($store_info['store_id']);
                    if ($start_time !== 0) {
                        if ($store_info['bill_cycle']) {
                            $this->_create_vr_bill_cycle_by_day($start_time, $store_info);
                        } else {
                            $this->_create_vr_bill_cycle_by_month($start_time, $store_info);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * 结算周期为月结
     * @param unknown $start_time
     * @param unknown $store_info
     */
    private function _create_vr_bill_cycle_by_month($start_unixtime,$store_info) {
        $i = 1;
        $start_unixtime = strtotime(date('Y-m-01 00:00:00', $start_unixtime));
        $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));
        while (($time = strtotime('-'.$i.' month',$current_time)) >= $start_unixtime) {
            if (date('Ym',$start_unixtime) == date('Ym',$time)) {
                //如果两个月份相等检查库是里否存在
                $order_statis = Model()->cls()->table('bill_create')->where(array('os_month'=>date('Ym',$start_unixtime),'store_id'=>$store_info['store_id'],'os_type'=>1))->find();
                if ($order_statis) {
                    break;
                }
            }
            //该月第一天0时unix时间戳
            $first_day_unixtime = strtotime(date('Y-m-01 00:00:00', $time));
            //该月最后一天最后一秒时unix时间戳
            $last_day_unixtime = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");
            $os_month = date('Ym',$first_day_unixtime);
    
            try {
                $this->_model_vr_order->beginTransaction();
                //生成单个店铺月订单出账单
                $data = array();
                $data['ob_store_id'] = $store_info['store_id'];
                $data['ob_start_date'] = $first_day_unixtime;
                $data['ob_end_date'] = $last_day_unixtime;
                $this->_create_vr_order_bill($data);
    
                $data = array();
                $data['os_month'] = $os_month;
                $data['os_type'] = 1;
                $data['store_id'] = $store_info['store_id'];
                Model()->cls()->table('bill_create')->insert($data);
    
                $this->_model_vr_order->commit();
            } catch (Exception $e) {
                $this->log('虚拟账单:'.$e->getMessage());
                $this->_model_vr_order->rollback();
            }
            $i++;
        }
    }
    
    /**
     * 生成所有店铺订单出账单[虚拟订单]
     *
     * @param int $data
     */
    private function _create_vr_order_bill($data){
        $data_bill['ob_start_date'] = $data['ob_start_date'];
        $data_bill['ob_end_date'] = $data['ob_end_date'];
        $data_bill['ob_state'] = 0;
        $data_bill['ob_store_id'] = $data['ob_store_id'];
        if (!$this->_model_vr_bill->getOrderBillInfo(array('ob_store_id'=>$data['ob_store_id'],'ob_start_date'=>$data['ob_start_date']))) {
            $insert = $this->_model_vr_bill->addOrderBill($data_bill);
            if (!$insert) {
                throw new Exception('生成账单失败');
            }
            //对已生成空账单进行销量、退单、佣金统计
            $data_bill['ob_id'] = $insert;
            $update = $this->_calc_vr_order_bill($data_bill);
            if (!$update){
                throw new Exception('更新账单失败');
            }
    
            // 发送店铺消息
            $param = array();
            $param['code'] = 'store_bill_affirm';
            $param['store_id'] = $data_bill['ob_store_id'];
            $param['param'] = array(
                    'state_time' => date('Y-m-d H:i:s', $data_bill['ob_start_date']),
                    'end_time' => date('Y-m-d H:i:s', $data_bill['ob_end_date']),
                    'bill_no' => $data_bill['ob_id']
            );
            QueueClient::push('sendStoreMsg', $param);
        }
    }
    
    /**
     * 计算某月内，某店铺的销量，佣金
     *
     * @param array $data_bill
     */
    private function _calc_vr_order_bill($data_bill){
    
        //计算已使用兑换码
        $order_condition = array();
        $order_condition['vr_state'] = 1;
        $order_condition['store_id'] = $data_bill['ob_store_id'];
        $order_condition['vr_usetime'] = array('between',"{$data_bill['ob_start_date']},{$data_bill['ob_end_date']}");
    
        $update = array();
    
        //订单金额
        $fields = 'sum(pay_price) as order_amount,SUM(ROUND(pay_price*commis_rate/100,2)) as commis_amount';
        $order_info =  $this->_model_vr_order->getOrderCodeInfo($order_condition, $fields);
        $update['ob_order_totals'] = floatval($order_info['order_amount']);
    
        //佣金金额
        $update['ob_commis_totals'] = $order_info['commis_amount'];
    
        //计算已过期不退款兑换码
        $order_condition = array();
        $order_condition['vr_state'] = 0;
        $order_condition['store_id'] = $data_bill['ob_store_id'];
        $order_condition['vr_invalid_refund'] = 0;
        $order_condition['vr_indate'] = array('between',"{$data_bill['ob_start_date']},{$data_bill['ob_end_date']}");
    
        //订单金额
        $fields = 'sum(pay_price) as order_amount,SUM(ROUND(pay_price*commis_rate/100,2)) as commis_amount';
        $order_info = $this->_model_vr_order->getOrderCodeInfo($order_condition, $fields);
        $update['ob_order_totals'] += floatval($order_info['order_amount']);
    
        //佣金金额
        $update['ob_commis_totals'] += $order_info['commis_amount'];
    
        //店铺名
        $store_info = $this->_model_store->getStoreInfoByID($data_bill['ob_store_id']);
        $update['ob_store_name'] = $store_info['store_name'];
    
        //本期应结
        $update['ob_result_totals'] = $update['ob_order_totals'] - $update['ob_commis_totals'];
        $update['ob_create_date'] = TIMESTAMP;
        $update['ob_state'] = 1;
        $update['os_month'] = date('Ym',$data_bill['ob_end_date']+1);
        return $this->_model_vr_bill->editOrderBill($update,array('ob_id'=>$data_bill['ob_id']));
    }
}