<?php
/**
 * 虚拟订单结算管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class vr_billControl extends SystemControl{
    /**
     * 每次导出订单数量
     * @var int
     */
    const EXPORT_SIZE = 1000;

    public function __construct(){
        parent::__construct();
    }

    /**
     * 所有月份销量账单
     *
     */
//     public function indexOp(){

//         $condition = array();
//         if (preg_match('/^\d{4}$/',$_GET['query_year'],$match)) {
//             $condition['os_year'] = $_GET['query_year'];
//         }
//         $model_bill = Model('vr_bill');
//         $bill_list = $model_bill->getOrderStatisList($condition,'*',12,'os_month desc');
//         Tpl::output('bill_list',$bill_list);
//         Tpl::output('show_page',$model_bill->showpage());

//         //输出子菜单
//         Tpl::output('top_link',$this->sublink($this->links,'index'));

//         Tpl::showpage('vr_bill_order_statis.index');
//     }

//     public function get_statis_xmlOp(){
//         $condition = array();
//         if (preg_match('/^\d{4}$/',$_POST['query'])) {
//             $condition['os_year'] = $_POST['query'];
//         }
//         $sort_fields = array('os_month','os_start_date','os_end_date','os_order_totals','os_commis_totals','os_result_totals');
//         if (in_array($_POST['sortorder'],array('asc','desc')) && in_array($_POST['sortname'],$sort_fields)) {
//             $order = $_POST['sortname'].' '.$_POST['sortorder'];
//         }
//         $model_bill = Model('vr_bill');
//         $bill_list = $model_bill->getOrderStatisList($condition,'*',$_POST['rp'],$order);
//         $data = array();
//         $data['now_page'] = $model_bill->shownowpage();
//         $data['total_num'] = $model_bill->gettotalnum();
//         foreach ($bill_list as $bill_info) {
//             $list = array();
//             $list['operation'] = "<a class=\"btn green\" href=\"index.php?act=vr_bill&op=show_statis&os_month={$bill_info['os_month']}\"><i class=\"fa fa-list-alt\"></i>查看</a>";
//             $list['os_month'] = substr($bill_info['os_month'],0,4).'-'.substr($bill_info['os_month'],4);
//             $list['os_start_date'] = date('Y-m-d',$bill_info['os_start_date']);
//             $list['os_end_date'] = date('Y-m-d',$bill_info['os_end_date']);
//             $list['os_order_totals'] = ncPriceFormat($bill_info['os_order_totals']);
//             $list['os_commis_totals'] = ncPriceFormat($bill_info['os_commis_totals']);
//             $list['os_result_totals'] = ncPriceFormat($bill_info['os_result_totals']);
//             $data['list'][$bill_info['os_month']] = $list;
//         }
//         exit(Tpl::flexigridXML($data));
//     }

    public function indexOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('vr_bill.index');
    }

    public function get_bill_xmlOp(){
        $model_bill = Model('vr_bill');
        $condition = array();

        if (!empty($_REQUEST['os_month']) && !preg_match('/^20\d{4}$/',$_REQUEST['os_month'])) {
            exit();
        }
        list($condition,$order) = $this->_get_bill_condition($condition);

        $bill_list = $model_bill->getOrderBillList($condition,'*',$_POST['rp'],$order);
        $data = array();
        $data['now_page'] = $model_bill->shownowpage();
        $data['total_num'] = $model_bill->gettotalnum();
        foreach ($bill_list as $bill_info) {
            $list = array();
            if (in_array($bill_info['ob_state'],array(2,3))) {
                $list['operation'] = "<a class=\"btn orange\" href=\"index.php?act=vr_bill&op=show_bill&ob_id={$bill_info['ob_id']}\"><i class=\"fa fa-gavel\"></i>处理</a>";
            } else {
                $list['operation'] = "<a class=\"btn green\" href=\"index.php?act=vr_bill&op=show_bill&ob_id={$bill_info['ob_id']}\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            }
			$list['ob_id'] = $bill_info['ob_id'];
            $list['ob_start_date'] = date('Y-m-d',$bill_info['ob_start_date']);
            $list['ob_end_date'] = date('Y-m-d',$bill_info['ob_end_date']);
            $list['ob_order_totals'] = ncPriceFormat($bill_info['ob_order_totals']);
            $list['ob_commis_totals'] = ncPriceFormat($bill_info['ob_commis_totals']);
            $list['ob_result_totals'] = ncPriceFormat($bill_info['ob_result_totals']);
            $list['ob_create_date'] = date('Y-m-d',$bill_info['ob_create_date']);
            $list['ob_state'] = billState($bill_info['ob_state']);
            $list['ob_store_name'] = $bill_info['ob_store_name'];
            $list['ob_store_id'] = $bill_info['ob_store_id'];
            $data['list'][$bill_info['ob_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 某店铺某月订单列表
     *
     */
    public function show_billOp(){
        $ob_id = intval($_GET['ob_id']);
        if ($ob_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_bill = Model('vr_bill');
        $bill_info = $model_bill->getOrderBillInfo(array('ob_id'=>$ob_id));
        if (!$bill_info){
            showMessage('参数错误','','html','error');
        }
        $sub_tpl_name = 'vr_bill.show.code_list';

        Tpl::output('tpl_name',$sub_tpl_name);
        Tpl::output('bill_info',$bill_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('vr_bill_order_bill.show');
    }

    public function get_bill_info_code_xmlOp(){
        $ob_id = intval($_GET['ob_id']);
        if ($ob_id <= 0) {
            exit();
        }
        $model_bill = Model('vr_bill');
        $bill_info = $model_bill->getOrderBillInfo(array('ob_id'=>$ob_id));
        if (!$bill_info){
            exit();
        }
        $model_order = Model('vr_order');
        $condition = array();
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('vr_code','buyer_name'))) {
            if ($_POST['qtype'] == 'vr_code') {
                $condition['vr_code'] = array('like',"%{$_POST['query']}%");
            } else {
                $order_list = $model_order->getOrderList(array('buyer_name'=>array('like',"%{$_POST['query']}%")),'','order_id');
                if (!empty($order_list)) {
                    $order_id_list = array();
                    foreach ($order_list as $order_info) {
                        $order_id_list[] = $order_info['order_id'];
                    }
                    $condition['order_id'] = array('in',$order_id_list);
                } else {
                    $condition['order_id'] = 0;
                }
            }
        }
        $sort_fields = array('vr_indate','vr_usetime','buyer_id');
        if (in_array($_POST['sortorder'],array('asc','desc')) && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $condition['store_id'] = $bill_info['ob_store_id'];
        if ($_GET['query_type'] == 'timeout') {
            //计算未使用已过期不可退兑换码列表
            $condition['vr_state'] = 0;
            $condition['vr_invalid_refund'] = 0;
            $condition['vr_indate'] = array('between',"{$bill_info['ob_start_date']},{$bill_info['ob_end_date']}");
        } else {
            //计算已使用兑换码列表
            $condition['vr_state'] = 1;
            $condition['vr_usetime'] = array('between',"{$bill_info['ob_start_date']},{$bill_info['ob_end_date']}");
        }
        $code_list = $model_order->getCodeList($condition, '*', $_POST['rp'], $order);

        //然后取订单编号
        $order_id_array = array();
        if (is_array($code_list)) {
            foreach ($code_list as $code_info) {
                $order_id_array[] = $code_info['order_id'];
            }
        }
        $condition = array();
        $condition['order_id'] = array('in',$order_id_array);
        $order_list = $model_order->getOrderList($condition);
        $order_new_list = array();
        if (!empty($order_list)) {
            foreach ($order_list as $v) {
                $order_new_list[$v['order_id']]['order_sn'] = $v['order_sn'];
                $order_new_list[$v['order_id']]['buyer_name'] = $v['buyer_name'];
                $order_new_list[$v['order_id']]['store_name'] = $v['store_name'];
                $order_new_list[$v['order_id']]['store_id'] = $v['store_id'];
            }
        }

        $data = array();
        $data['now_page'] = $model_order->shownowpage();
        $data['total_num'] = $model_order->gettotalnum();
        foreach ($code_list as $code_info) {
            $list = array();
            $list['operation'] = "<a class=\"btn green\" href=\"index.php?act=vr_order&op=show_order&order_id={$code_info['order_id']}\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            $list['vr_code'] = $code_info['vr_code'];
            $list['ob_start_date'] = $_GET['query_type'] == 'timeout' ? date('Y-m-d',$code_info['vr_indate']) : date('Y-m-d H:i:s',$code_info['vr_usetime']);
            $list['order_sn'] = $order_new_list[$code_info['order_id']]['order_sn'];
            $list['pay_price'] = ncPriceFormat($code_info['pay_price']);
            $list['commis_rate'] = ncPriceFormat($code_info['pay_price']*$code_info['commis_rate']/100);
            $list['buyer_name'] = $order_new_list[$code_info['order_id']]['buyer_name'];
            $list['buyer_id'] = $code_info['buyer_id'];
            $data['list'][$code_info['rec_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    public function bill_checkOp(){
        $ob_id = intval($_GET['ob_id']);
        if ($ob_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_bill = Model('vr_bill');
        $condition = array();
        $condition['ob_id'] = $ob_id;
        $condition['ob_state'] = BILL_STATE_STORE_COFIRM;
        $update = $model_bill->editOrderBill(array('ob_state'=>BILL_STATE_SYSTEM_CHECK),$condition);
        if ($update){
            $this->log('审核账单,账单号：'.$ob_id,1);
            showMessage('审核成功，账单进入付款环节');
        }else{
            $this->log('审核账单，账单号：'.$ob_id,0);
            showMessage('审核失败','','html','error');
        }
    }

    /**
     * 账单付款
     *
     */
    public function bill_payOp(){
        $ob_id = intval($_GET['ob_id']);
        if ($ob_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_bill = Model('vr_bill');
        $condition = array();
        $condition['ob_id'] = $ob_id;
        $condition['ob_state'] = BILL_STATE_SYSTEM_CHECK;
        $bill_info = $model_bill->getOrderBillInfo($condition);
        if (!$bill_info){
            showMessage('参数错误','','html','error');
        }
        if (chksubmit()){
            if (!preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_POST['pay_date'])) {
                showMessage('参数错误','','html','error');
            }
            $input = array();
            $input['ob_pay_content'] = $_POST['pay_content'];
            $input['ob_pay_date'] = strtotime($_POST['pay_date']);
            $input['ob_state'] = BILL_STATE_SUCCESS;
            $update = $model_bill->editOrderBill($input,$condition);
            if ($update){
                // 发送店铺消息
                $param = array();
                $param['code'] = 'store_bill_gathering';
                $param['store_id'] = $bill_info['ob_store_id'];
                $param['param'] = array(
                    'bill_no' => $bill_info['ob_id']
                );
                QueueClient::push('sendStoreMsg', $param);

                $this->log('账单付款,账单号：'.$ob_id,1);
                showMessage('保存成功','index.php?act=vr_bill');
            }else{
                $this->log('账单付款,账单号：'.$ob_id,1);
                showMessage('保存失败','','html','error');
            }
        }else{
			Tpl::setDirquna('shop');
            Tpl::showpage('vr_bill.pay');
        }
    }

    /**
     * 打印结算单
     *
     */
    public function bill_printOp(){
        $ob_id = intval($_GET['ob_id']);
        if ($ob_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_bill = Model('vr_bill');
        $condition = array();
        $condition['ob_id'] = $ob_id;
        $condition['ob_state'] = BILL_STATE_SUCCESS;
        $bill_info = $model_bill->getOrderBillInfo($condition);
        if (!$bill_info){
            showMessage('参数错误','','html','error');
        }

        Tpl::output('bill_info',$bill_info);
		Tpl::setDirquna('shop');

        Tpl::showpage('vr_bill.print','null_layout');
    }


    /**
     * 导出平台月出账单表
     *
     */
    public function export_billOp(){
        $model_bill = Model('vr_bill');
        $condition = array();

        if (preg_match('/^[\d,]+$/', $_GET['ob_id'])) {
            $_GET['ob_id'] = explode(',',trim($_GET['ob_id'],','));
            $condition['ob_id'] = array('in',$_GET['ob_id']);
        }
        list($condition,$order) = $this->_get_bill_condition($condition);

        if (!is_numeric($_GET['curpage'])){
            $count = $model_bill->getOrderBillCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){
                //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','javascript:history.back(-1)');
				Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
                exit();
            }
            $limit = false;
        }else{
            //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = "{$limit1},{$limit2}";
        }
        $data = $model_bill->getOrderBillList($condition,'*','',$order,$limit);
        $export_data = array();
        $export_data[0] = array('账单编号','开始日期','结束日期','消费金额','佣金金额','本期应结','出账日期','账单状态','店铺','店铺ID');
        $ob_order_totals = 0;
        $ob_commis_totals = 0;
        $ob_result_totals = 0;
        foreach ($data as $k => $v) {
            $export_data[$k+1][] = $v['ob_id'];
            $export_data[$k+1][] = date('Y-m-d',$v['ob_start_date']);
            $export_data[$k+1][] = date('Y-m-d',$v['ob_end_date']);
            $ob_order_totals += $export_data[$k+1][] = $v['ob_order_totals'];
            $ob_commis_totals += $export_data[$k+1][] = $v['ob_commis_totals'];
            $ob_result_totals += $export_data[$k+1][] = $v['ob_result_totals'];
            $export_data[$k+1][] = date('Y-m-d',$v['ob_create_date']);
            $export_data[$k+1][] = billState($v['ob_state']);
            $export_data[$k+1][] = $v['ob_store_name'];
            $export_data[$k+1][] = $v['ob_store_id'];
        }
        $count = count($export_data);
        $export_data[$count][] = '';
        $export_data[$count][] = '';
        $export_data[$count][] = '合计';
        $export_data[$count][] = $ob_order_totals;
        $export_data[$count][] = $ob_commis_totals;
        $export_data[$count][] = $ob_result_totals;
        $csv = new Csv();
        $export_data = $csv->charset($export_data,CHARSET,'gbk');
        $csv->filename = 'vr_bill-'.$_GET['os_month'];
        $csv->export($export_data);
    }

    /**
     * 导出兑换码明细CSV
     *
     */
    public function export_orderOp(){
        $ob_id = intval($_GET['ob_id']);
        if ($ob_id <= 0) {
            showMessage('参数错误','','html','error');
        }
        $model_bill = Model('vr_bill');
        $bill_info = $model_bill->getOrderBillInfo(array('ob_id'=>$ob_id));
        if (!$bill_info){
            showMessage('参数错误','','html','error');
        }
        $model_order = Model('vr_order');
        $condition = array();
        $condition['store_id'] = $bill_info['ob_store_id'];
        if ($_GET['query_type'] == 'timeout') {
            //计算未使用已过期不可退兑换码列表
            $condition['vr_state'] = 0;
            $condition['vr_invalid_refund'] = 0;
            $condition['vr_indate'] = array('between',"{$bill_info['ob_start_date']},{$bill_info['ob_end_date']}");
        } else {
            //计算已使用兑换码列表
            $condition['vr_state'] = 1;
            $condition['vr_usetime'] = array('between',"{$bill_info['ob_start_date']},{$bill_info['ob_end_date']}");
        }
        if (preg_match('/^[\d,]+$/', $_GET['rec_id'])) {
            $_GET['rec_id'] = explode(',',trim($_GET['rec_id'],','));
            $condition['rec_id'] = array('in',$_GET['rec_id']);
        }
        if (!is_numeric($_GET['curpage'])){
            $count = $model_order->getOrderCodeCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){
                //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=vr_bill&op=show_bill&ob_id='.$ob_id);
				Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
                exit();
            }else{
                //如果数量小，直接下载
                $data = $model_order->getCodeList($condition,'*','','rec_id desc',self::EXPORT_SIZE);
            }
        }else{
            //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_order->getCodeList($condition,'*','','rec_id desc',"{$limit1},{$limit2}");
        }

        //然后取订单编号
        $order_id_array = array();
        if (is_array($data)) {
            foreach ($data as $code_info) {
                $order_id_array[] = $code_info['order_id'];
            }
        }
        $condition = array();
        $condition['order_id'] = array('in',$order_id_array);
        $order_list = $model_order->getOrderList($condition);
        $order_new_list = array();
        if (!empty($order_list)) {
            foreach ($order_list as $v) {
                $order_new_list[$v['order_id']]['order_sn'] = $v['order_sn'];
                $order_new_list[$v['order_id']]['buyer_name'] = $v['buyer_name'];
                $order_new_list[$v['order_id']]['buyer_id'] = $v['buyer_id'];
                $order_new_list[$v['order_id']]['store_name'] = $v['store_name'];
                $order_new_list[$v['order_id']]['store_id'] = $v['store_id'];
                $order_new_list[$v['order_id']]['goods_name'] = $v['goods_name'];
            }
        }

        $export_data = array();
        $export_data[0] = array('兑换码','消费时间','订单号','消费金额','佣金金额','商家','商家编号','买家','买家编号','商品');
        if ($_GET['query_type'] == 'timeout') {
            $export_data[0][1] = '过期时间';
        }

        $pay_totals = 0;
        $commis_totals = 0;
        $k = 0;
        foreach ($data as $v) {
            //该订单算佣金
            $export_data[$k+1][] = $v['vr_code'];
            if ($_GET['query_type'] == 'timeout') {
                $export_data[$k+1][] = date('Y-m-d H:i:s',$v['vr_indate']);
            } else {
                $export_data[$k+1][] = date('Y-m-d H:i:s',$v['vr_usetime']);
            }
            $export_data[$k+1][] = $order_new_list[$v['order_id']]['order_sn'];
            $pay_totals += $export_data[$k+1][] = floatval($v['pay_price']);
            $commis_totals += $export_data[$k+1][] = floatval($v['pay_price'] * $v['commis_rate']/100);
            $export_data[$k+1][] = $order_new_list[$v['order_id']]['store_name'];
            $export_data[$k+1][] = $order_new_list[$v['order_id']]['store_id'];
            $export_data[$k+1][] = $order_new_list[$v['order_id']]['buyer_name'];
            $export_data[$k+1][] = $order_new_list[$v['order_id']]['buyer_id'];
            $export_data[$k+1][] = $order_new_list[$v['order_id']]['goods_name'];
            $k++;
        }
        $count = count($export_data);
        $export_data[$count][] = '合计';
        $export_data[$count][] =  '';
        $export_data[$count][] = '';
        $export_data[$count][] = $pay_totals;
        $export_data[$count][] = $commis_totals;
        $csv = new Csv();
        $export_data = $csv->charset($export_data,CHARSET,'gbk');
        $file_name = $_GET['query_type'] == 'timeout' ? 'timeout_code' : 'xiaofei_code';
        $csv->filename = $ob_id.'-'.$file_name;
        $csv->export($export_data);
    }

    /**
     * 合并相同代码
     */
    private function _get_bill_condition($condition) {
        if ($_GET['query_year'] && $_GET['query_month']) {
            $_GET['os_month'] = intval($_GET['query_year'].$_GET['query_month']);
        } elseif ($_GET['query_year']) {
            $condition['os_month'] = array('between',$_GET['query_year'].'01,'.$_GET['query_year'].'12');
        }
        if (!empty($_GET['os_month'])) {
            $condition['os_month'] = intval($_GET['os_month']);
        }
        if ($_REQUEST['query'] != '' && in_array($_REQUEST['qtype'],array('ob_no','ob_store_name'))) {
            $condition[$_REQUEST['qtype']] = $_REQUEST['query'];
        }
        if (is_numeric($_GET["ob_state"])) {
            $condition['ob_state'] = intval($_GET["ob_state"]);
        }
        if (is_numeric($_GET["ob_no"])) {
            $condition['ob_no'] = intval($_GET["ob_no"]);
        }
        if (is_numeric($_GET["ob_id"])) {
            $condition['ob_id'] = intval($_GET["ob_id"]);
        }
        if ($_GET['ob_store_name'] != ''){
            if ($_GET['jq_query']) {
                $condition['ob_store_name'] = $_GET['ob_store_name'];
            } else {
                $condition['ob_store_name'] = array('like',"%{$_GET['ob_store_name']}%");
            }
        }
        $sort_fields = array('ob_id','ob_start_date','ob_end_date','ob_order_totals','ob_shipping_totals','ob_commis_totals','ob_order_return_totals','ob_commis_return_totals','ob_store_cost_totals','ob_result_totals','ob_create_date','ob_state','ob_store_id');
        if (in_array($_REQUEST['sortorder'],array('asc','desc')) && in_array($_REQUEST['sortname'],$sort_fields)) {
            $order = $_REQUEST['sortname'].' '.$_REQUEST['sortorder'];
        } else {
            $order = 'ob_id desc';
        }
        return array($condition,$order);
    }
}
