<?php
/**
 * 虚拟订单管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class vr_orderControl extends SystemControl{
    /**
     * 每次导出订单数量
     * @var int
     */
    const EXPORT_SIZE = 1000;

    public function __construct(){
        parent::__construct();
        Language::read('trade');
    }

    public function indexOp(){
        //显示支付接口列表(搜索)
        $payment_list = Model('payment')->getPaymentOpenList();
        $payment_list['wxpay'] = array(
                'payment_code' => 'wxpay',
                'payment_name' => '微信支付'
        );
        foreach ($payment_list as $key => $value){
            if ($value['payment_code'] == 'offline') {
                unset($payment_list[$key]);
            }
        }
        Tpl::output('payment_list',$payment_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('vr_order.index');
    }

    public function get_xmlOp(){
        $model_vr_order = Model('vr_order');
        $condition  = array();
        $this->_get_condition($condition);

        $sort_fields = array('buyer_name','store_name','order_id','payment_code','goods_id','vr_indate','order_state','order_amount','order_from','rcb_amount','pd_amount','payment_time','finnshed_time','evaluation_state','refund_amount','buyer_id','store_id');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        } else {
            $order = 'order_id desc';
        }

        $order_list = $model_vr_order->getOrderList($condition,!empty($_POST['rp']) ? intval($_POST['rp']) : 15,'*',$order);

        $data = array();
        $data['now_page'] = $model_vr_order->shownowpage();
        $data['total_num'] = $model_vr_order->gettotalnum();
        foreach ($order_list as $k => $order_info) {
            $list = array();$operation_detail = '';
            $list['operation'] = "<a class=\"btn green\" href=\"index.php?act=vr_order&op=show_order&order_id={$order_info['order_id']}\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            $if_cancel = $model_vr_order->getOrderOperateState('system_cancel',$order_info);
            if ($if_cancel) {
                $operation_detail .= "<li><a href=\"javascript:void(0);\" onclick=\"fg_cancel({$order_info['order_id']})\">取消订单</a></li>";
            }
            $if_system_receive_pay = $model_vr_order->getOrderOperateState('system_receive_pay',$order_info);
            if ($if_system_receive_pay) {
                $operation_detail .= "<li><a href=\"index.php?act=vr_order&op=change_state&state_type=receive_pay&order_id={$order_info['order_id']}\">收到货款</a></li>";
            }
            if ($operation_detail) {
                $list['operation'] .= "<span class='btn'><em><i class='fa fa-cog'></i>设置 <i class='arrow'></i></em><ul>{$operation_detail}</ul>";
            }
            $list['order_sn'] = $order_info['order_sn'];
            $list['order_from'] = str_replace(array(1,2), array('PC端','移动端'),$order_info['order_from']);
            $list['add_time'] = date('Y-m-d H:i:s',$order_info['add_time']);
            $list['order_amount'] = ncPriceFormat($order_info['order_amount']);
            $list['order_state'] = $order_info['state_desc'];
            $list['payment_code'] = orderPaymentName($order_info['payment_code']);
            $list['payment_time'] = !empty($order_info['payment_time']) ? (intval(date('His',$order_info['payment_time'])) ? date('Y-m-d H:i:s',$order_info['payment_time']) : date('Y-m-d',$order_info['payment_time'])) : '';
            $list['buyer_name'] = $order_info['buyer_name'];
            $list['buyer_phone'] = $order_info['buyer_phone'];
            $list['buyer_id'] = $order_info['buyer_id'];
            $list['store_name'] = $order_info['store_name'];
            $list['store_id'] = $order_info['store_id'];
            $list['goods_name'] = $order_info['goods_name'];
            $list['vr_indate'] = !empty($order_info['vr_indate']) ? date('Y-m-d H:i:s',$order_info['vr_indate']) : '';
            $list['rcb_amount'] = ncPriceFormat($order_info['rcb_amount']);
            $list['pd_amount'] = ncPriceFormat($order_info['pd_amount']);
            $list['finnshed_time'] = !empty($order_info['finnshed_time']) ? date('Y-m-d H:i:s',$order_info['finnshed_time']) : '';
            $list['evaluation_state'] = str_replace(array(0,1,2), array('未评价','已评价','未评价'),$order_info['evaluation_state']);
            $list['refund_amount'] = ncPriceFormat($order_info['refund_amount']);
            $data['list'][$order_info['order_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 平台订单状态操作
     *
     */
    public function change_stateOp() {
        $model_vr_order = Model('vr_order');
        $condition = array();
        $condition['order_id'] = intval($_GET['order_id']);
        $order_info = $model_vr_order->getOrderInfo($condition);
        if ($_GET['state_type'] == 'cancel') {
            $result = $this->_order_cancel($order_info);
        } elseif ($_GET['state_type'] == 'receive_pay') {
            $result = $this->_order_receive_pay($order_info,$_POST);
        }
        if(!$result['state']) {
            showMessage($result['msg'],$_POST['ref_url'],'html','error');
        } else {
            showMessage($result['msg'],$_POST['ref_url']);
        }
    }

    /**
     * 系统取消订单
     * @param unknown $order_info
     */
    private function _order_cancel($order_info) {
        $model_vr_order = Model('vr_order');
        $logic_vr_order = Logic('vr_order');
        $if_allow = $model_vr_order->getOrderOperateState('system_cancel',$order_info);
        if (!$if_allow) {
            return callback(false,'无权操作');
        }
        if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
            $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
            exit(json_encode(array('state'=>false,'msg'=>'该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消')));
        }
        $this->log('关闭了订单,'.L('order_number').':'.$order_info['order_sn'],1);
        $result = $logic_vr_order->changeOrderStateCancel($order_info,'store', '管理员('.$this->admin_info['name'].')关闭订单');
        if ($result['state']) {
            exit(json_encode(array('state'=>true,'msg'=>'取消成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'取消失败')));
        }
    }

    /**
     * 系统收到货款
     * @param unknown $order_info
     * @throws Exception
     */
    private function _order_receive_pay($order_info,$post) {
        $model_vr_order = Model('vr_order');
        $logic_vr_order = Logic('vr_order');
        $if_allow = $model_vr_order->getOrderOperateState('system_receive_pay',$order_info);
        if (!$if_allow) {
            return callback(false,'无权操作');
        }

        if (!chksubmit()) {
            Tpl::output('order_info',$order_info);
            //显示支付接口
            $payment_list = Model('payment')->getPaymentOpenList();
            //去掉预存款和货到付款
            foreach ($payment_list as $key => $value){
                if ($value['payment_code'] == 'predeposit' || $value['payment_code'] == 'offline') {
                   unset($payment_list[$key]);
                }
            }
            Tpl::output('payment_list',$payment_list);
			Tpl::setDirquna('shop');
            Tpl::showpage('order.receive_pay');
            exit();
        } else {
            $result = $logic_vr_order->changeOrderStatePay($order_info,'admin', $post);
            if ($result['state']) {
                $this->log('将订单改为已收款状态,'.L('order_number').':'.$order_info['order_sn'],1);
                //记录消费日志
                $api_pay_amount = $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                QueueClient::push('addConsume', array('member_id'=>$order_info['buyer_id'],'member_name'=>$order_info['buyer_name'],
                'consume_amount'=>$api_pay_amount,'consume_time'=>TIMESTAMP,'consume_remark'=>'管理员更改虚拟订单为已收款状态，订单号：'.$order_info['order_sn']));
            }
            return $result;
        }
    }

    /**
     * 查看订单
     *
     */
    public function show_orderOp(){
        $order_id = intval($_GET['order_id']);
        if($order_id <= 0 ){
            showMessage(L('miss_order_number'));
        }
        $model_vr_order = Model('vr_order');
        $order_info = $model_vr_order->getOrderInfo(array('order_id'=>$order_id));
        if (empty($order_info)) {
            showMessage('订单不存在','','html','error');
        }

        //取兑换码列表
        $vr_code_list = $model_vr_order->getOrderCodeList(array('order_id' => $order_info['order_id']));
        $order_info['extend_vr_order_code'] = $vr_code_list;

        //显示取消订单
        $order_info['if_cancel'] = $model_vr_order->getOrderOperateState('buyer_cancel',$order_info);

        //显示订单进行步骤
        $order_info['step_list'] = $model_vr_order->getOrderStep($order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_TIME * 3600;
        }

        //退款信息
        $refund_list = Model('vr_refund')->getRefundList(array('order_id'=>$order_info['order_id']));
        Tpl::output('refund_list',$refund_list);

        //商家信息
        $store_info = Model('store')->getStoreInfo(array('store_id'=>$order_info['store_id']));
        Tpl::output('store_info',$store_info);

        Tpl::output('order_info',$order_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('vr_order.view');
    }

    /**
     * 导出
     *
     */
    public function export_step1Op(){
        $lang   = Language::getLangContent();

        $model_vr_order = Model('vr_order');
        $condition  = array();
        if (preg_match('/^[\d,]+$/', $_GET['order_id'])) {
            $_GET['order_id'] = explode(',',trim($_GET['order_id'],','));
            $condition['order_id'] = array('in',$_GET['order_id']);
        }
        $this->_get_condition($condition);
        if (!is_numeric($_GET['curpage'])){
            $count = $model_vr_order->getOrderCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=vr_order&op=index');
				Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
            }else{  //如果数量小，直接下载
                $data = $model_vr_order->getOrderList($condition,'','*','order_id desc',self::EXPORT_SIZE);
                $this->createExcel($data);
            }
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_vr_order->getOrderList($condition,'','*','order_id desc',"{$limit1},{$limit2}");
            $this->createExcel($data);
        }
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单编号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单来源');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'下单时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单金额(元)');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单状态');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'支付方式');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'支付时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'买家账号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'接收手机');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'买家ID');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺ID');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'有效期');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'充值卡支付(元)');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'预存款支付(元)');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'完成时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'是否评价');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'退款金额(元)');
        //data
        foreach ((array)$data as $k=>$order_info){
            $list = array();
            $list['order_sn'] = $order_info['order_sn'];
            $list['order_from'] = str_replace(array(1,2), array('PC端','移动端'),$order_info['order_from']);
            $list['add_time'] = date('Y-m-d H:i:s',$order_info['add_time']);
            $list['order_amount'] = ncPriceFormat($order_info['order_amount']);
            $list['order_state'] = $order_info['state_desc'];
            $list['payment_code'] = orderPaymentName($order_info['payment_code']);
            $list['payment_time'] = !empty($order_info['payment_time']) ? (intval(date('His',$order_info['payment_time'])) ? date('Y-m-d H:i:s',$order_info['payment_time']) : date('Y-m-d',$order_info['payment_time'])) : '';
            $list['buyer_name'] = $order_info['buyer_name'];
            $list['buyer_phone'] = $order_info['buyer_phone'];
            $list['buyer_id'] = $order_info['buyer_id'];
            $list['store_name'] = $order_info['store_name'];
            $list['store_id'] = $order_info['store_id'];
            $list['goods_name'] = $order_info['goods_name'];
            $list['vr_indate'] = !empty($order_info['vr_indate']) ? date('Y-m-d H:i:s',$order_info['vr_indate']) : '';
            $list['rcb_amount'] = ncPriceFormat($order_info['rcb_amount']);
            $list['pd_amount'] = ncPriceFormat($order_info['pd_amount']);
            $list['finnshed_time'] = !empty($order_info['finnshed_time']) ? date('Y-m-d H:i:s',$order_info['finnshed_time']) : '';
            $list['evaluation_state'] = str_replace(array(0,1,2), array('未评价','已评价','未评价'),$order_info['evaluation_state']);
            $list['refund_amount'] = ncPriceFormat($order_info['refund_amount']);
            $tmp = array();
            $tmp[] = array('data'=>$list['order_sn']);
            $tmp[] = array('data'=>$list['order_from']);
            $tmp[] = array('data'=>$list['add_time']);
            $tmp[] = array('data'=>$list['order_amount']);
            $tmp[] = array('data'=>$list['order_state']);
            $tmp[] = array('data'=>$list['payment_code']);
            $tmp[] = array('data'=>$list['payment_time']);
            $tmp[] = array('data'=>$list['buyer_name']);
            $tmp[] = array('data'=>$list['buyer_phone']);
            $tmp[] = array('data'=>$list['buyer_id']);
            $tmp[] = array('data'=>$list['store_name']);
            $tmp[] = array('data'=>$list['store_id']);
            $tmp[] = array('data'=>$list['goods_name']);
            $tmp[] = array('data'=>$list['vr_indate']);
            $tmp[] = array('data'=>$list['rcb_amount']);
            $tmp[] = array('data'=>$list['pd_amount']);
            $tmp[] = array('data'=>$list['finnshed_time']);
            $tmp[] = array('data'=>$list['evaluation_state']);
            $tmp[] = array('data'=>$list['refund_amount']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_od_order'),CHARSET));
        $excel_obj->generateXML('order-'.$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

    /**
     * 处理搜索条件
     */
    private function _get_condition(& $condition) {
        if ($_REQUEST['query'] != '' && in_array($_REQUEST['qtype'],array('order_sn','store_name','buyer_name'))) {
            $condition[$_REQUEST['qtype']] = array('like',"%{$_REQUEST['query']}%");
        }
        if ($_GET['keyword'] != '' && in_array($_GET['keyword_type'],array('order_sn','store_name','buyer_name'))) {
            if ($_GET['jq_query']) {
                $condition[$_GET['keyword_type']] = $_GET['keyword'];
            } else {
                $condition[$_GET['keyword_type']] = array('like',"%{$_GET['keyword']}%");
            }
        }
        if (!in_array($_GET['qtype_time'],array('add_time','payment_time','finnshed_time'))) {
            $_GET['qtype_time'] = null;
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']): null;
        if ($_GET['qtype_time'] && ($start_unixtime || $end_unixtime)) {
            $condition[$_GET['qtype_time']] = array('time',array($start_unixtime,$end_unixtime));
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if(in_array($_GET['order_state'],array('0','10','20','40'))){
            $condition['order_state'] = $_GET['order_state'];
        }
        if (floatval($_GET['query_start_amount']) > 0 && floatval($_GET['query_end_amount']) > 0) {
            $condition['order_amount'] = array('between',floatval($_GET['query_start_amount']).','.floatval($_GET['query_end_amount']));
        }
        if(in_array($_GET['order_from'],array('1','2'))){
            $condition['order_from'] = $_GET['order_from'];
        }
    }
}
