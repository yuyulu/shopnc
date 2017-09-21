<?php
/**
 * 退款管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class refundControl extends SystemControl{
    const EXPORT_SIZE = 1000;
    private $links = array(
            array('url'=>'act=refund','text'=>'待处理'),
            array('url'=>'act=refund&op=refund_all','text'=>'所有记录'),
            array('url'=>'act=refund&op=reason','text'=>'退款退货原因')
    );
    public function __construct(){
        parent::__construct();
        $model_refund = Model('refund_return');
        $model_refund->getRefundStateArray();
        Tpl::output('top_link',$this->sublink($this->links,$_GET['op']));
    }

    public function indexOp() {
		Tpl::setDirquna('shop');
        Tpl::showpage('refund_manage.list');
    }

    /**
     * 待处理列表
     */
    public function get_manage_xmlOp() {
        $model_refund = Model('refund_return');
        $condition = array();
        //状态:1为处理中,2为待管理员处理,3为已完成
        $condition['refund_state'] = 2;

        list($condition,$order) = $this->_get_condition($condition);

        $refund_list = $model_refund->getRefundList($condition,$_POST['rp'],$order);
        $data = array();
        $data['now_page'] = $model_refund->shownowpage();
        $data['total_num'] = $model_refund->gettotalnum();
        $pic_base_url = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/';
        foreach ($refund_list as $k => $refund_info) {
            $list = array();
            $list['operation'] = "<a class=\"btn orange\" href=\"index.php?act=refund&op=edit&refund_id={$refund_info['refund_id']}\"><i class=\"fa fa-gavel\"></i>处理</a>";
            $list['refund_sn'] = $refund_info['refund_sn'];
            $list['refund_amount'] = ncPriceFormat($refund_info['refund_amount']);
            if(!empty($refund_info['pic_info'])) {
                $info = unserialize($refund_info['pic_info']);
                if (is_array($info) && !empty($info['buyer'])) {
                    foreach($info['buyer'] as $pic_name) {
                        $list['pic_info'] .= "<a href='".$pic_base_url.$pic_name."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$pic_name.">\")'><i class='fa fa-picture-o'></i></a> ";
                    }
                    $list['pic_info'] = trim($list['pic_info']);
                }
            }
            if (empty($list['pic_info'])) $list['pic_info'] = '';
            $list['buyer_message'] = "<span title='{$refund_info['buyer_message']}'>{$refund_info['buyer_message']}</span>";
            $list['add_times'] = date('Y-m-d H:i:s',$refund_info['add_time']);
            $list['goods_name'] = $refund_info['goods_name'];
            if ($refund_info['goods_id'] > 0) {
                $list['goods_name'] = "<a class='open' title='{$refund_info['goods_name']}' href='". urlShop('goods', 'index', array('goods_id' => $refund_info['goods_id'])) .
                "' target='blank'>{$refund_info['goods_name']}</a>";
            }
            $list['seller_message'] = $refund_info['seller_message'];
            $list['seller_times'] = !empty($refund_info['seller_time']) ? date('Y-m-d H:i:s',$refund_info['seller_time']) : '';
            if ($refund_info['goods_image'] != '') {
                $list['goods_image'] = "<a href='".thumb($refund_info,360)."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".thumb($refund_info,240).">\")'><i class='fa fa-picture-o'></i></a> ";
            } else {
                $list['goods_image'] = '';
            }
            $list['goods_id'] = !empty($refund_info['goods_id']) ? $refund_info['goods_id'] : '';
            $list['order_sn'] = $refund_info['order_sn'];
            $list['buyer_name'] = $refund_info['buyer_name'];
            $list['buyer_id'] = $refund_info['buyer_id'];
            $list['store_name'] = $refund_info['store_name'];
            $list['store_id'] = $refund_info['store_id'];
            $data['list'][$refund_info['refund_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 所有记录
     */
    public function refund_allOp() {
		Tpl::setDirquna('shop');
        Tpl::showpage('refund_all.list');
    }

    /**
     * 所有记录
     */
    public function get_all_xmlOp() {
        $model_refund = Model('refund_return');
        $condition = array();

        list($condition,$order) = $this->_get_condition($condition);

        $refund_list = $model_refund->getRefundList($condition,!empty($_POST['rp']) ? intval($_POST['rp']) : 15,$order);
        $data = array();
        $data['now_page'] = $model_refund->shownowpage();
        $data['total_num'] = $model_refund->gettotalnum();
        $pic_base_url = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/';
        foreach ($refund_list as $k => $refund_info) {
            $list = array();
            if ($refund_info['refund_state'] == 2) {
                $list['operation'] = "<a class=\"btn orange\" href=\"index.php?act=refund&op=edit&refund_id={$refund_info['refund_id']}\"><i class=\"fa fa-gavel\"></i>处理</a>";
            }
            $list['operation'] .= "<a class=\"btn green\" href=\"index.php?act=refund&op=view&refund_id={$refund_info['refund_id']}\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            $list['refund_sn'] = $refund_info['refund_sn'];
            $list['refund_amount'] = ncPriceFormat($refund_info['refund_amount']);
            if(!empty($refund_info['pic_info'])) {
                $info = unserialize($refund_info['pic_info']);
                if (is_array($info) && !empty($info['buyer'])) {
                    foreach($info['buyer'] as $pic_name) {
                        $list['pic_info'] .= "<a href='".$pic_base_url.$pic_name."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$pic_base_url.$pic_name.">\")'><i class='fa fa-picture-o'></i></a> ";
                    }
                    $list['pic_info'] = trim($list['pic_info']);
                }
            }
            if (empty($list['pic_info'])) $list['pic_info'] = '';
            $list['buyer_message'] = "<span title='{$refund_info['buyer_message']}'>{$refund_info['buyer_message']}</span>";
            $list['add_times'] = date('Y-m-d H:i:s',$refund_info['add_time']);
            $list['goods_name'] = $refund_info['goods_name'];
            if ($refund_info['goods_id'] > 0) {
                $list['goods_name'] = "<a class='open' title='{$refund_info['goods_name']}' href='". urlShop('goods', 'index', array('goods_id' => $refund_info['goods_id'])) .
                "' target='blank'>{$refund_info['goods_name']}</a>";
            }
            $state_array = $model_refund->getRefundStateArray('seller');
            $list['seller_state'] = $state_array[$refund_info['seller_state']];

            $admin_array = $model_refund->getRefundStateArray('admin');
            $list['refund_state'] = $refund_info['seller_state'] == 2 ? $admin_array[$refund_info['refund_state']]:'';

            $list['seller_message'] = "<span title='{$refund_info['seller_message']}'>{$refund_info['seller_message']}</i>";
            $list['admin_message'] = "<span title='{$refund_info['admin_message']}'>{$refund_info['admin_message']}</span>";
            $list['seller_times'] = !empty($refund_info['seller_time']) ? date('Y-m-d H:i:s',$refund_info['seller_time']) : '';
            if ($refund_info['goods_image'] != '') {
                $list['goods_image'] = "<a href='".thumb($refund_info,360)."' target='_blank' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".thumb($refund_info,240).">\")'><i class='fa fa-picture-o'></i></a> ";
            } else {
                $list['goods_image'] = '';
            }
            $list['goods_id'] = !empty($refund_info['goods_id']) ? $refund_info['goods_id'] : '';
            $list['order_sn'] = $refund_info['order_sn'];
            $list['buyer_name'] = $refund_info['buyer_name'];
            $list['buyer_id'] = $refund_info['buyer_id'];
            $list['store_name'] = $refund_info['store_name'];
            $list['store_id'] = $refund_info['store_id'];
            $data['list'][$refund_info['refund_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 退款处理页
     *
     */
    public function editOp() {
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['refund_id'] = intval($_GET['refund_id']);
        $refund = $model_refund->getRefundReturnInfo($condition);
        $order_id = $refund['order_id'];
        $model_order = Model('order');
        $order = $model_order->getOrderInfo(array('order_id'=> $order_id),array());
        if ($order['payment_time'] > 0) {
            $order['pay_amount'] = $order['order_amount']-$order['rcb_amount']-$order['pd_amount'];//在线支付金额=订单总价格-充值卡支付金额-预存款支付金额
        }
        Tpl::output('order',$order);
        $detail_array = $model_refund->getDetailInfo($condition);
        if(empty($detail_array)) {
            $model_refund->addDetail($refund,$order);
            $detail_array = $model_refund->getDetailInfo($condition);
        }
        Tpl::output('detail_array',$detail_array);
        if (chksubmit()) {
            if ($refund['refund_state'] != '2') {//检查状态,防止页面刷新不及时造成数据错误
                showMessage(Language::get('nc_common_save_fail'));
            }
            
            if ($detail_array['pay_time'] > 0) {
                $refund['pay_amount'] = $detail_array['pay_amount'];//已完成在线退款金额
            }
            $state = $model_refund->editOrderRefund($refund,$this->admin_info['name']);
            if ($state) {
                $refund_array = array();
                $refund_array['admin_time'] = time();
                $refund_array['refund_state'] = '3';//状态:1为处理中,2为待管理员处理,3为已完成
                $refund_array['admin_message'] = $_POST['admin_message'];
                $model_refund->editRefundReturn($condition, $refund_array);

                // 发送买家消息
                $param = array();
                $param['code'] = 'refund_return_notice';
                $param['member_id'] = $refund['buyer_id'];
                $param['param'] = array(
                    'refund_url' => urlShop('member_refund', 'view', array('refund_id' => $refund['refund_id'])),
                    'refund_sn' => $refund['refund_sn']
                );
                QueueClient::push('sendMemberMsg', $param);

                $this->log('退款确认，退款编号'.$refund['refund_sn']);
                showMessage(Language::get('nc_common_save_succ'),'index.php?act=refund&op=index');
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        Tpl::output('refund',$refund);
        $info['buyer'] = array();
        if(!empty($refund['pic_info'])) {
            $info = unserialize($refund['pic_info']);
        }
        Tpl::output('pic_list',$info['buyer']);
		Tpl::setDirquna('shop');
        Tpl::showpage('refund.edit');
    }

    /**
     * 退款记录查看页
     *
     */
    public function viewOp() {
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['refund_id'] = intval($_GET['refund_id']);
        $refund = $model_refund->getRefundReturnInfo($condition);
        Tpl::output('refund',$refund);
        $info['buyer'] = array();
        if(!empty($refund['pic_info'])) {
            $info = unserialize($refund['pic_info']);
        }
        Tpl::output('pic_list',$info['buyer']);
        $detail_array = $model_refund->getDetailInfo($condition);
        Tpl::output('detail_array',$detail_array);
		Tpl::setDirquna('shop');
        Tpl::showpage('refund.view');
    }

    /**
     * 退款退货原因
     */
    public function reasonOp() {
        $model_refund = Model('refund_return');
        $condition = array();

        $reason_list = $model_refund->getReasonList($condition,200);
        Tpl::output('reason_list',$reason_list);
		Tpl::setDirquna('shop');

        Tpl::showpage('refund_reason.list');
    }

    /**
     * 新增退款退货原因
     *
     */
    public function add_reasonOp() {
        $model_refund = Model('refund_return');
        if (chksubmit()) {
            $reason_array = array();
            $reason_array['reason_info'] = $_POST['reason_info'];
            $reason_array['sort'] = intval($_POST['sort']);
            $reason_array['update_time'] = time();

            $state = $model_refund->addReason($reason_array);
            if ($state) {
                $this->log('新增退款退货原因，编号'.$state);
                showMessage(Language::get('nc_common_save_succ'),'index.php?act=refund&op=reason');
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
		Tpl::setDirquna('shop');
        Tpl::showpage('refund_reason.add');
    }

    /**
     * 编辑退款退货原因
     *
     */
    public function edit_reasonOp() {
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['reason_id'] = intval($_GET['reason_id']);
        $reason_list = $model_refund->getReasonList($condition);
        $reason = $reason_list[$condition['reason_id']];
        if (chksubmit()) {
            $reason_array = array();
            $reason_array['reason_info'] = $_POST['reason_info'];
            $reason_array['sort'] = intval($_POST['sort']);
            $reason_array['update_time'] = time();
            $state = $model_refund->editReason($condition, $reason_array);
            if ($state) {
                $this->log('编辑退款退货原因，编号'.$condition['reason_id']);
                showMessage(Language::get('nc_common_save_succ'),'index.php?act=refund&op=reason');
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        Tpl::output('reason',$reason);
		Tpl::setDirquna('shop');
        Tpl::showpage('refund_reason.edit');
    }

    /**
     * 删除退款退货原因
     *
     */
    public function del_reasonOp() {
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['reason_id'] = intval($_GET['reason_id']);
        $state = $model_refund->delReason($condition);
        if ($state) {
            $this->log('删除退款退货原因，编号'.$condition['reason_id']);
            showMessage(Language::get('nc_common_del_succ'),'index.php?act=refund&op=reason');
        } else {
            showMessage(Language::get('nc_common_del_fail'));
        }
    }

    /**
     * 封装共有查询代码
     */
    private function _get_condition($condition) {
        if ($_REQUEST['query'] != '' && in_array($_REQUEST['qtype'],array('order_sn','store_name','buyer_name','goods_name','refund_sn'))) {
            $condition[$_REQUEST['qtype']] = array('like',"%{$_REQUEST['query']}%");
        }
        if ($_GET['keyword'] != '' && in_array($_GET['keyword_type'],array('order_sn','store_name','buyer_name','goods_name','refund_sn'))) {
            if ($_GET['jq_query']) {
                $condition[$_GET['keyword_type']] = $_GET['keyword'];
            } else {
                $condition[$_GET['keyword_type']] = array('like',"%{$_GET['keyword']}%");
            }
        }
        if (!in_array($_GET['qtype_time'],array('add_time','seller_time','admin_time'))) {
            $_GET['qtype_time'] = null;
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']): null;
        if ($_GET['qtype_time'] && ($start_unixtime || $end_unixtime)) {
            $condition[$_GET['qtype_time']] = array('time',array($start_unixtime,$end_unixtime));
        }
        if (floatval($_GET['query_start_amount']) > 0 && floatval($_GET['query_end_amount']) > 0) {
            $condition['refund_amount'] = array('between',floatval($_GET['query_start_amount']).','.floatval($_GET['query_end_amount']));
        }
        if ($_GET['refund_state'] == 2) {
            $condition['refund_state'] = 2;
        }
        $sort_fields = array('buyer_name','store_name','goods_id','refund_id','seller_time','refund_amount','buyer_id','store_id');
        if ($_REQUEST['sortorder'] != '' && in_array($_REQUEST['sortname'],$sort_fields)) {
            $order = $_REQUEST['sortname'].' '.$_REQUEST['sortorder'];
        }
        return array($condition,$order);
    }

    /**
     * csv导出
     */
    public function export_step1Op() {
        $model_refund = Model('refund_return');
        $condition = array();
        if (preg_match('/^[\d,]+$/', $_GET['refund_id'])) {
            $_GET['refund_id'] = explode(',',trim($_GET['refund_id'],','));
            $condition['refund_id'] = array('in',$_GET['refund_id']);
        }
        list($condition,$order) = $this->_get_condition($condition);
        if (!is_numeric($_GET['curpage'])){
            $count = $model_refund->getRefundCount($condition);
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $array = array();
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','javascript:history.back(-1)');
                Tpl::showpage('export.excel');
                exit();
            }
            $limit = false;
        } else {
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = $limit1 .','. $limit2;
        }
        $refund_list = $model_refund->getRefundList($condition,'',$order,$limit);
        $this->createCsv($refund_list);
    }

    /**
     * 生成csv文件
     */
    private function createCsv($refund_list) {
        $model_refund = Model('refund_return');
        $list = array();
        $pic_base_url = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/';
        foreach ($refund_list as $k => $refund_info) {
            $list[$k]['refund_sn'] = $refund_info['refund_sn'];
            $list[$k]['refund_amount'] = ncPriceFormat($refund_info['refund_amount']);
            if(!empty($refund_info['pic_info'])) {
                $info = unserialize($refund_info['pic_info']);
                if (is_array($info) && !empty($info['buyer'])) {
                    foreach($info['buyer'] as $pic_name) {
                        $list[$k]['pic_info'] .= $pic_base_url.$pic_name.'|';
                    }
                    $list[$k]['pic_info'] = trim($list[$k]['pic_info'],'|');
                }
            }
            if (empty($list[$k]['pic_info'])) $list[$k]['pic_info'] = '';
            $list[$k]['buyer_message'] = $refund_info['buyer_message'];
            $list[$k]['add_times'] = date('Y-m-d H:i:s',$refund_info['add_time']);
            $list[$k]['goods_name'] = $refund_info['goods_name'];
            $state_array = $model_refund->getRefundStateArray('seller');
            $list[$k]['seller_state'] = $state_array[$refund_info['seller_state']];
            $admin_array = $model_refund->getRefundStateArray('admin');
            $list[$k]['refund_state'] = $refund_info['seller_state'] == 2 ? $admin_array[$refund_info['refund_state']]:'';
            $list[$k]['seller_message'] = $refund_info['seller_message'];
            $list[$k]['admin_message'] = $refund_info['admin_message'];
            $list[$k]['seller_times'] = !empty($refund_info['seller_time']) ? date('Y-m-d H:i:s',$refund_info['seller_time']) : '';
            if ($refund_info['goods_image'] != '') {
                $list[$k]['goods_image'] = thumb($refund_info,360);
            } else {
                $list[$k]['goods_image'] = '';
            }
            $list[$k]['goods_id'] = !empty($refund_info['goods_id']) ? $refund_info['goods_id'] : '';
            $list[$k]['order_sn'] = $refund_info['order_sn'];
            $list[$k]['buyer_name'] = $refund_info['buyer_name'];
            $list[$k]['buyer_id'] = $refund_info['buyer_id'];
            $list[$k]['store_name'] = $refund_info['store_name'];
            $list[$k]['store_id'] = $refund_info['store_id'];
        }

        $header = array(
                'refund_sn' => '退单编号',
                'refund_amount' => '退款金额',
                'pic_info' => '申请图片',
                'buyer_message' => '申请原因',
                'add_times' => '申请时间',
                'goods_name' => '涉及商品',
                'seller_state' => '商家处理',
                'refund_state' => '平台处理',
                'seller_message' => '商家处理备注',
                'admin_message' => '平台处理备注',
                'seller_times' => '商家申核时间',
                'goods_image' => '商品图',
                'goods_id' => '商品ID',
                'order_sn' => '订单编号',
                'buyer_name' => '买家',
                'buyer_id' => '买家ID',
                'store_name' => '商家名称',
                'store_id'  => '商家ID'
        );
        array_unshift($list, $header);
        
		$csv = new Csv();
	    $export_data = $csv->charset($list,CHARSET,'gbk');
	    $csv->filename = $csv->charset('refund',CHARSET).$_GET['curpage'] . '-'.date('Y-m-d');
	    $csv->export($list);   		
    }
}
