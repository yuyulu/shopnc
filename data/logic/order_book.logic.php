<?php
/**
 * 预定行为
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');
class order_bookLogic {

    /**
     * 下单时，保存预定时段数据
     * @param unknown $order_data
     * @param unknown $post_data
     * @throws Exception
     */
    public function buy($order_data = array(), $post_data = array()) {
        $model_order_book = Model('order_book');
        $order_info = current($order_data['order_list']);
        $goods_info = current($order_data['goods_list']);
        //如果选择只付定金，产生两条时段记录
        if ($post_data['book_pay_type'] == 'part') {
            $data = array();
            $data['book_order_id'] = key($order_data['order_list']);
            $data['book_step'] = 1;
            $data['book_amount'] = $goods_info['book_down_payment'] * $goods_info['goods_num'];
            $data['book_end_time'] = TIMESTAMP + ORDER_AUTO_CANCEL_TIME * 3600;
            $data['book_store_id'] = $order_info['store_id'];
            $insert = $model_order_book->addOrderBook($data);
            if (!$insert) {
                throw new Exception('订单保存失败[预定时段出现异常]');
            }
            unset($data['book_store_id']);
            $data['book_step'] = 2;
            $data['book_amount'] = $order_info['order_amount'] - $data['book_amount'];
            $data['book_end_time'] = $goods_info['book_down_time'] + BOOK_AUTO_END_TIME * 3600;
            $data['book_buyer_phone'] = $post_data['buyer_phone'];
            $insert = $model_order_book->addOrderBook($data);
            if (!$insert) {
                throw new Exception('订单保存失败[预定时段出现异常]');
            }
        } else {
            //如果全款支付，产生一条时段记录
            $data = array();
            $data['book_order_id'] = key($order_data['order_list']);
            $data['book_step'] = 0;
            $data['book_amount'] = $order_info['order_amount'];
            $data['book_end_time'] = TIMESTAMP + ORDER_AUTO_CANCEL_TIME * 3600;
            $data['book_deposit_amount'] = $goods_info['book_down_payment'] * $goods_info['goods_num'];
            $data['book_store_id'] = $order_info['store_id'];
            $insert = $model_order_book->addOrderBook($data);
            if (!$insert) {
                throw new Exception('订单保存失败[预定时段出现异常]');
            }
        }
        return callback(true);
    }

    /**
     * 取得某预定订单的阶段内容、订单状态、订单操作项
     * @param unknown $order_info
     */
    public function getOrderBookInfo($order_info) {
        $book_list = Model('order_book')->getOrderBookList(array('book_order_id'=>$order_info['order_id']));
        $pay_sn = $order_info['pay_sn'];

        //如果全款支付
        if (!$book_list[0]['book_step']) {
            //全款支付
            $book_list[0]['book_step'] = '全款支付(定金+尾款+运费)';
            if (empty($book_list[0]['book_pay_time'])) {
                //如果未付全款
                if ($book_list[0]['book_end_time'] > TIMESTAMP) {
                    //如果还未过期,买家可以支付（订单被取消时除外）
                    if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
                        $book_list[0]['book_state'] = '已取消';
                        $book_list[0]['book_operate'] = '-';
                    } else {
                        $_fen = ceil(($book_list[0]['book_end_time'] - TIMESTAMP)/60);
                        $book_list[0]['book_state'] = '须在'.$_fen.'分钟内付款';
                        $book_list[0]['book_operate'] = '<a class="ncm-btn ncm-btn-orange fr mr15" href="index.php?act=buy&op=pay&pay_sn='.$pay_sn.'">付款</a>';
                        $order_info['if_buyer_pay'] = true;
                    }

                    //需要在线支付的总金额（包括站内余额）
                    $order_info['pay_amount'] = $book_list[0]['book_amount'];
                    $order_info['pd_amount'] = $book_list[0]['book_pd_amount'];
                    $order_info['rcb_amount'] = $book_list[0]['book_rcb_amount'];
                    //需要跳到三方API支付总金额（扣除了站内余额）
                    $order_info['api_pay_amount'] = $book_list[0]['book_amount'] - $book_list[0]['book_pd_amount'] - $book_list[0]['book_rcb_amount'];

                    //中止后继续支付标志(之前使用站内余额支付过款，但未在线API支付完成，站内款被锁定中)
                    if ($book_list[0]['book_rcb_amount'] || $book_list[0]['book_pd_amount']) {
                        $order_info['if_buyer_pay_lock'] = true;
                    }
                    $book_list[0]['book_amount_ext'] = '(含定金'.ncPriceFormat($book_list[0]['book_deposit_amount']).')';
                } else {
                    //已过期
                    $book_list[0]['book_state'] = '未完成';
                    $book_list[0]['book_operate'] = '未按时支付全款取消订单';
                    $order_info['state_desc'] = '已取消';
                    $order_info['if_buyer_cancel'] = false;
                    if ($order_info['order_state'] != ORDER_STATE_CANCEL) {
                        //马上更改订单状态为已取消
                        $this->changeOrderStateCancel($order_info,'system','系统','超期未支付全款系统自动关闭订单');
                    }
                }

                //如果此时支付成功，可以向商家发送支付成功站内信息
                $order_info['if_send_store_msg_pay_success'] = true;
            } else {
                //如果已支付全款
                $book_list[0]['book_state'] = $book_list[0]['book_end_time'] > TIMESTAMP ? '已支付' : '已完成';
                $book_list[0]['book_operate'] = '已于'.date('Y-m-d H:i',$book_list[0]['book_pay_time']).'付款';
                if ($order_info['order_state'] == ORDER_STATE_PAY) {
                    $order_info['state_desc'] = '待发货';                    
                }
                $order_info['if_buyer_cancel'] = false;
                $book_list[0]['book_amount_ext'] = '(含定金'.ncPriceFormat($book_list[0]['book_deposit_amount']).')';
            }
        } else {
            //如果分步支付
            $book_list[0]['book_step'] = '阶段1：付定金';
            $book_list[1]['book_step'] = '阶段2：付尾款';
            if (empty($book_list[0]['book_pay_time'])) {
                //如果未付定金
                if ($book_list[0]['book_end_time'] > TIMESTAMP) {
                    //如果还未过期,买家可以支付（订单被取消时除外）
                    if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
                        $book_list[0]['book_state'] = '已取消';
                        $book_list[0]['book_operate'] = '-';
                        $book_list[1]['book_state'] = '已取消';
                    } else {
                        $_fen = ceil(($book_list[0]['book_end_time'] - TIMESTAMP)/60);
                        $book_list[0]['book_state'] = '须在'.$_fen.'分钟内付款';
                        $book_list[0]['book_operate'] = '<a class="ncm-btn ncm-btn-orange fr mr15" href="index.php?act=buy&op=pay&pay_sn='.$pay_sn.'">付款</a>';
                        $order_info['if_buyer_pay'] = true;
                        $book_list[1]['book_state'] = '未开始';
                    }

                    $book_list[1]['book_operate'] = '';

                    $order_info['pd_amount'] = $book_list[0]['book_pd_amount'];
                    $order_info['rcb_amount'] = $book_list[0]['book_rcb_amount'];
                    $order_info['pay_amount'] = $book_list[0]['book_amount'];
                    $order_info['api_pay_amount'] = $book_list[0]['book_amount'] - $book_list[0]['book_pd_amount'] - $book_list[0]['book_rcb_amount'];

                    //中止后继续支付标志(之前使用站内余额支付过款，但未在线API支付完成，站内款被锁定中)
                    if ($book_list[0]['book_rcb_amount'] || $book_list[0]['book_pd_amount']) {
                        $order_info['if_buyer_pay_lock'] = true;
                    }
                } else {
                    //已过期
                    $book_list[0]['book_state'] = '未完成';
                    $book_list[0]['book_operate'] = '未按时支付定金取消订单';
                    $book_list[1]['book_amount'] = '未生成';
                    $book_list[1]['book_state'] = $book_list[1]['book_operate'] = '';
                    $order_info['state_desc'] = '已取消';
                    $order_info['if_buyer_cancel'] = false;
                    $order_info['if_system_cancel'] = false;
                    $order_info['if_store_cancel'] = false;
                    if ($order_info['order_state'] != ORDER_STATE_CANCEL) {
                        //马上更改订单状态为已取消
                        $this->changeOrderStateCancel($order_info,'system','系统','超期未支付全款系统自动关闭订单');
                    }
                }
                $order_info['system_receive_pay_op_name'] = '收到定金';
            } else {
                //如果已付定金
                $book_list[0]['book_state'] = $book_list[0]['book_end_time'] > TIMESTAMP ? '已支付' : '已完成';
                $book_list[0]['book_operate'] = '已于'.date('Y-m-d H:i',$book_list[0]['book_pay_time']).'付款';
                if (empty($book_list[1]['book_pay_time'])) {
                    //未付尾款
                    if ($book_list[1]['book_end_time'] > TIMESTAMP) {
                        //如果还未过期,买家可以支付（订单被取消时除外）
                        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
                            $book_list[1]['book_state'] = '已取消';
                            $book_list[1]['book_operate'] = '';
                        } else {
                            $book_list[1]['book_state'] = '须在'.date('Y-m-d H:i',$book_list[1]['book_end_time']+1).'之前付款';
                            $book_list[1]['book_operate'] = '<a class="ncm-btn ncm-btn-orange fr mr15" href="index.php?act=buy&op=pay&pay_sn='.$pay_sn.'">付款</a>';
                            $order_info['state_desc'] = '待付尾款';
                            //付尾款支付标志
                            $order_info['if_buyer_repay'] = true;
                            $order_info['if_store_cancel'] = true;
                            $order_info['if_system_cancel'] = true;
                        }
                        $order_info['if_refund_cancel'] = false;
                        $order_info['if_buyer_pay'] = true;
                        $order_info['pd_amount'] = $book_list[1]['book_pd_amount'];
                        $order_info['rcb_amount'] = $book_list[1]['book_rcb_amount'];
                        $order_info['pay_amount'] = $book_list[1]['book_amount'];
                        $order_info['api_pay_amount'] = $book_list[1]['book_amount'] - $book_list[1]['book_pd_amount'] - $book_list[1]['book_rcb_amount'];

                        //中止后继续支付标志(之前使用站内余额支付过款，但未在线API支付完成，站内款被锁定中)
                        if ($book_list[1]['book_rcb_amount'] || $book_list[1]['book_pd_amount']) {
                            $order_info['if_buyer_pay_lock'] = true;
                        }
                        $order_info['if_store_send'] = false;
                    } else {
                        //已过期
                        $book_list[1]['book_state'] = '未完成';
                        $book_list[1]['book_operate'] = '未按时支付尾款取消订单';
                        $order_info['state_desc'] = '已取消';
                        $order_info['if_refund_cancel'] = false;
                        $order_info['if_buyer_cancel'] = false;
                        $order_info['if_system_cancel'] = false;
                        if ($order_info['order_state'] != ORDER_STATE_CANCEL) {
                            //马上更改订单状态为已取消
                            $this->changeOrderStateCancel($order_info,'system','系统','超期未支付全款系统自动关闭订单');
                        }
                    }
                    //尾款到期后，7天内可以更改收尾款状态
                    $order_info['if_system_receive_pay'] = ($order_info['order_state'] == ORDER_STATE_PAY && TIMESTAMP < $book_list[1]['book_end_time']+604800);
                    $order_info['system_receive_pay_op_name'] = '收到尾款';

                    //如果此时支付成功，可以向商家发送支付成功站内信息
                    $order_info['if_send_store_msg_pay_success'] = true;
                } else {
                    //已付尾款
                    $book_list[1]['book_state'] = $book_list[1]['book_end_time'] > TIMESTAMP ? '已支付' : '已完成';
                    $book_list[1]['book_operate'] = '已于'.date('Y-m-d H:i',$book_list[1]['book_pay_time']).'付款';
                    if ($order_info['order_state'] == ORDER_STATE_PAY) {
                        $order_info['state_desc'] = '待发货';        
                    }
                    $order_info['if_buyer_cancel'] = false;
                    $order_info['if_system_cancel'] = false;
                    $order_info['if_system_receive_pay'] = false;
                }
            }
        }
        $order_info['if_modify_price'] = false;
        $order_info['book_list'] = $book_list;
        return callback(true, '', $order_info);
    }

    /**
     * 预定订单收款
     * @param unknown $order_info
     */
    public function changeBookOrderReceivePay($order_info,$post) {
        $model_book = Model('order_book');
        $condition = array();
        $condition['book_order_id'] = $order_info['order_id'];
        $book_list = $model_book->getOrderBookList($condition);
        //如果分步支付
        if ($book_list[0]['book_step']) {
            //如果未付定金
            if (empty($book_list[0]['book_pay_time'])) {
                $condition['book_step'] = 1;
                //更新预定人数
                $order_goods_info = Model('order')->getOrderGoodsInfo(array('order_id'=>$order_info['order_id']),'goods_id','rec_id asc');
                Model('goods')->editGoods(array('book_buyers'=>array('exp','book_buyers+1')),array('goods_id'=>$order_goods_info['goods_id']));
            } else {
                $condition['book_step'] = 2;
            }
        } else {
            //更新预定人数
            $order_goods_info = Model('order')->getOrderGoodsInfo(array('order_id'=>$order_info['order_id']),'goods_id','rec_id asc');
            Model('goods')->editGoods(array('book_buyers'=>array('exp','book_buyers+1')),array('goods_id'=>$order_goods_info['goods_id']));
        }
        $data = array();
        $data['book_pay_time'] = TIMESTAMP;
        $data['book_pay_name'] = orderPaymentName($post['payment_code']);
        $data['book_trade_no'] = $post['trade_no'];
        $update = $model_book->editOrderBook($data,$condition);
        return callback($update,'更新订单预定信息失败');
    }

    /**
     * 取消订单
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param string $msg 操作备注
     * @return array
     */
    public function changeOrderStateCancel($order_info, $role, $user = '', $msg = '') {
        try {
            $model_order = Model('order');
            $model_order->beginTransaction();
            $order_id = $order_info['order_id'];

            //库存销量变更
            $goods_list = $model_order->getOrderGoodsList(array('order_id'=>$order_id));
            $data = array();
            foreach ($goods_list as $goods) {
                $data[$goods['goods_id']] = $goods['goods_num'];
            }
            $result = Logic('queue')->cancelOrderUpdateStorage($data);
            if (!$result['state']) {
                throw new Exception('还原库存失败');
            }

            if (in_array($role,array('seller','admin'))) {
                //商家和后台取消订单，退还已经支付金额
                $order_info = $this->_calcSellerCancelAmount($order_info);
            } else {
                //买家、系统取消，只还超出定金部分，如果不足定金，则不返还
                $order_info = $this->_calcMemberCancelAmount($order_info);
            }
            $this->_editPdAndRcb($order_info);

            //将实际支付金额（平台实际收款额）记录，结算使用
            if (!empty($order_info['real_pay'])) {
                $condition = array();
                $condition['book_order_id'] = $order_info['order_id'];
                $condition['book_step'] = array('in',array(0,1));
                $data = array();
                $data['book_real_pay'] = $order_info['real_pay'];
                $data['book_cancel_time'] = time();
                $update = Model('order_book')->editOrderBook($data,$condition);
                if (!$update) {
                    throw new Exception('保存失败');
                }
                //已取消的预定订单运费置为0，否则结算会计算在内
                $update = $model_order->editOrder(array('shipping_fee'=>0),array('order_id'=>$order_info['order_id']));
                if (!$update) {
                    throw new Exception('保存失败');
                }
            }
            //更新订单信息
            $update_order = array('order_state'=>ORDER_STATE_CANCEL);
            $update = $model_order->editOrder($update_order,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception('保存失败');
            }

            //添加订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_msg'] = '取消了订单';
            $data['log_user'] = $user;
            if ($msg) {
                $data['log_msg'] .= ' ( '.$msg.' )';
            }
            $data['log_orderstate'] = ORDER_STATE_CANCEL;
            $model_order->addOrderLog($data);
            $model_order->commit();
    
            return callback(true,'操作成功');
    
        } catch (Exception $e) {
            $model_order->rollback();
            return callback(false,'操作失败');
        }
    }

    /**
     * 取得订单订金金额
     */
    public function getDepositAmount($order_info = array()) {
        if ($order_info['order_type'] != 2) return 0;
        $condition = array();
        $condition['book_order_id'] = $order_info['order_id'];
        $condition['book_step'] = array('in',array(0,1));
        $book_info = Model('order_book')->getOrderBookInfo(array('book_order_id'=>$order_info['order_id']),'','book_id asc');
        if ($book_info['book_step'] == 1) {
            return $book_info['book_amount'];
        } else {
            return $book_info['book_deposit_amount'];
        } 
    }

    /**
     * 取消订单时，计算应退款金额(商家、管理员取消)
     * 由于是全部退款，平台最终收取金额为0
     */
    private function _calcSellerCancelAmount($order_info) {
        $book_list = Model('order_book')->getOrderBookList(array('book_order_id'=>$order_info['order_id']));

        //如果全款支付
        if (!$book_list[0]['book_step']) {
            //如果未付全款
            if (empty($book_list[0]['book_pay_time'])) {
                //如果支付了部分站内账户，则全部返还
                if ($book_list[0]['book_pd_amount'] > 0 || $book_list[0]['book_rcb_amount'] > 0) {
                    $order_info['cancel_pd_amount'] = $book_list[0]['book_pd_amount'];
                    $order_info['cancel_rcb_amount'] = $book_list[0]['book_rcb_amount'];
                }
            } else {
                //如果已支付全款，返还全款
                $order_info['cancel_pd_amount'] = $book_list[0]['book_amount'];
            }
        } else {
            //如果分步支付
            //如果未付定金
            if (empty($book_list[0]['book_pay_time'])) {
                //如果支付了部分站内账户，则全部返还
                if ($book_list[0]['book_pd_amount'] > 0 || $book_list[0]['book_rcb_amount'] > 0) {
                    $order_info['cancel_pd_amount'] = $book_list[0]['book_pd_amount'];
                    $order_info['cancel_rcb_amount'] = $book_list[0]['book_rcb_amount'];
                }
            } else {
                //如果已付定金未付尾款
                if (empty($book_list[1]['book_pay_time'])) {
                    //如果有使用充值卡，优先退还充值卡
                    if ($book_list[0]['book_rcb_amount'] > 0) {
                        $order_info['cancel_rcb_amount'] = $book_list[0]['book_rcb_amount'];
                        $order_info['cancel_pd_amount'] = $book_list[0]['book_amount'] - $book_list[0]['book_rcb_amount'];
                    } else {
                        //全部退到预存款
                        $order_info['cancel_pd_amount'] = $book_list[0]['book_amount'];
                    }
                    //如果尾款支付了部分站内账户，则全部返还
                    if ($book_list[1]['book_pd_amount'] > 0 || $book_list[1]['book_rcb_amount'] > 0) {
                        $order_info['cancel_pd_amount'] += $book_list[1]['book_pd_amount'];
                        $order_info['cancel_rcb_amount'] += $book_list[1]['book_rcb_amount'];
                    }
                }
            }
        }
        return $order_info;
    }

    /**
     * 取消订单时，计算应退款金额(买家、系统取消)
     * 全款支付但未支付完成退还已经支付部分
     * 分步支付：已经支付完了定金，则不退(如果支付了一部分尾款则退尾款)，定金支付到一半，则退
     * 最终可以的情况是定金不还，未返还的定金最后还需要结算给商家，所以需要记入下标real_pay
     */
    private function _calcMemberCancelAmount($order_info) {
        $book_list = Model('order_book')->getOrderBookList(array('book_order_id'=>$order_info['order_id']));

        //如果全款支付
        if (!$book_list[0]['book_step']) {
            //如果未付全款
            if (empty($book_list[0]['book_pay_time'])) {
                //如果支付了部分站内账户，全部返还
                if ($book_list[0]['book_pd_amount'] > 0 || $book_list[0]['book_rcb_amount'] > 0) {
                    $order_info['cancel_pd_amount'] = $book_list[0]['book_pd_amount'];
                    $order_info['cancel_rcb_amount'] = $book_list[0]['book_rcb_amount'];
                }
            } else {
                //如果付款全款了，订单不可以取消，这种情况不存在
            }
        } else {
            //如果分步支付
            //如果未付定金
            if (empty($book_list[0]['book_pay_time'])) {
                //如果支付了部分站内账户，则全部返还
                if ($book_list[0]['book_pd_amount'] > 0 || $book_list[0]['book_rcb_amount'] > 0) {
                    $order_info['cancel_pd_amount'] = $book_list[0]['book_pd_amount'];
                    $order_info['cancel_rcb_amount'] = $book_list[0]['book_rcb_amount'];
                }
            } else {
                //已经支付了定金,不还退定金
                $order_info['real_pay'] = $book_list[0]['book_amount'];
                //如果付了部分尾款返还尾款
                if (empty($book_list[1]['book_pay_time'])) {
                    $order_info['cancel_pd_amount'] = $book_list[1]['book_pd_amount'];
                    $order_info['cancel_rcb_amount'] = $book_list[1]['book_rcb_amount'];
                }
            }
        }
        return $order_info;
    }

    /**
     * 变更站内账户
     * @param unknown $order_info
     */
    private function _editPdAndRcb($order_info) {
        $model_pd = Model('predeposit');
        //退还到充值卡
        $rcb_amount = floatval($order_info['cancel_rcb_amount']);
        if ($rcb_amount > 0) {
            $data_pd = array();
            $data_pd['member_id'] = $order_info['buyer_id'];
            $data_pd['member_name'] = $order_info['buyer_name'];
            $data_pd['amount'] = $rcb_amount;
            $data_pd['order_sn'] = $order_info['order_sn'];
            $model_pd->changeRcb('order_book_cancel',$data_pd);
        }

        //退还到预存款
        $pd_amount = floatval($order_info['cancel_pd_amount']);
        if ($pd_amount > 0) {
            $data_pd = array();
            $data_pd['member_id'] = $order_info['buyer_id'];
            $data_pd['member_name'] = $order_info['buyer_name'];
            $data_pd['amount'] = $pd_amount;
            $data_pd['order_sn'] = $order_info['order_sn'];
            $model_pd->changePd('order_book_cancel',$data_pd);
        }
    }
}
