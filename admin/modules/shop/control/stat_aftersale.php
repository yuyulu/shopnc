<?php
/**
 * 售后分析
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class stat_aftersaleControl extends SystemControl{
    private $links = array(
        array('url'=>'act=stat_aftersale&op=refund','lang'=>'stat_refund'),
        array('url'=>'act=stat_aftersale&op=evalstore','lang'=>'stat_evalstore'),
    );

    private $search_arr;//处理后的参数

    public function __construct(){
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');

        $model = Model('stat');
        //存储参数
        $this->search_arr = $_REQUEST;
        //处理搜索时间
        if (!isset($this->search_arr['op']) || in_array($this->search_arr['op'],array('refund','refundlist','get_refundlist_xml','get_refund_highcharts'))){
            $this->search_arr = $model->dealwithSearchTime($this->search_arr);
            //获得系统年份
            $year_arr = getSystemYearArr();
            //获得系统月份
            $month_arr = getSystemMonthArr();
            //获得本月的周时间段
            $week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
            Tpl::output('year_arr', $year_arr);
            Tpl::output('month_arr', $month_arr);
            Tpl::output('week_arr', $week_arr);
        }
        Tpl::output('search_arr', $this->search_arr);
    }

    public function indexOp() {
        $this->refundOp();
    }
    /**
     * 退款统计
     */
    public function refundOp(){
        Tpl::output('top_link',$this->sublink($this->links, 'refund'));
		Tpl::setDirquna('shop');
        Tpl::showpage('stat.aftersale.refund');
    }
    /**
     * 退款统计
     */
    public function refundlistOp(){
        $where = array();
        $model = Model('stat');
        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
        $model_refund = Model('refund_return');
        $refundstate_arr = $model_refund->getRefundStateArray();
        $where['add_time'] = array('between',$searchtime_arr);
        if (!empty($_GET['id']) && is_array($_GET['id'])){
            $where['refund_id'] = array('in',$_GET['id']);
        }
        if ($this->search_arr['exporttype'] == 'excel'){
            $refundlist_tmp = $model_refund->getRefundReturnList($where, 0);
        }
        $statheader = array();
        $statheader[] = array('text'=>'订单编号','key'=>'order_sn');
        $statheader[] = array('text'=>'退款编号','key'=>'refund_sn');
        $statheader[] = array('text'=>'店铺名','key'=>'store_name','class'=>'alignleft');
        $statheader[] = array('text'=>'商品名称','key'=>'goods_name','class'=>'alignleft');
        $statheader[] = array('text'=>'买家会员名','key'=>'buyer_name');
        $statheader[] = array('text'=>'申请时间','key'=>'add_time');
        $statheader[] = array('text'=>'退款金额','key'=>'refund_amount');
        $statheader[] = array('text'=>'商家审核','key'=>'seller_state');
        $statheader[] = array('text'=>'平台确认','key'=>'refund_state');
        foreach ((array)$refundlist_tmp as $k => $v){
            $tmp = $v;
            foreach ((array)$statheader as $h_k=>$h_v){
                $tmp[$h_v['key']] = $v[$h_v['key']];
                if ($h_v['key'] == 'add_time'){
                    $tmp[$h_v['key']] = @date('Y-m-d',$v['add_time']);
                }
                if ($h_v['key'] == 'refund_state'){
                    $tmp[$h_v['key']] = $v['seller_state']==2 ? $refundstate_arr['admin'][$v['refund_state']]:'无';
                }
                if ($h_v['key'] == 'seller_state'){
                    $tmp[$h_v['key']] = $refundstate_arr['seller'][$v['seller_state']];
                }
                if ($h_v['key'] == 'goods_name'){
                    $tmp[$h_v['key']] = '<a href="'.urlShop('goods', 'index', array('goods_id' => $v['goods_id'])).'" target="_blank">'.$v['goods_name'].'</a>';
                }
            }
            $statlist[] = $tmp;
        }
        if ($this->search_arr['exporttype'] == 'excel'){
            //导出Excel
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            foreach ((array)$statheader as $k => $v){
                $excel_data[0][] = array('styleid'=>'s_title','data'=>$v['text']);
            }
            //data
            foreach ((array)$statlist as $k => $v){
                foreach ((array)$statheader as $h_k=>$h_v){
                    $excel_data[$k+1][] = array('data'=>$v[$h_v['key']]);
                }
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $excel_obj->addWorksheet($excel_obj->charset('退款记录',CHARSET));
            $excel_obj->generateXML($excel_obj->charset('退款记录',CHARSET).date('Y-m-d-H',time()));
            exit();
        }
    }

    /**
     * 退款统计
     */
    public function get_refund_highchartsOp(){
        $where = array();
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
        $model = Model('stat');

        //获得搜索的开始时间和结束时间
        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);

        $field = ' SUM(refund_amount) as amount ';
        if($this->search_arr['search_type'] == 'day'){
            //构造横轴数据
            for($i=0; $i<24; $i++){
                $stat_arr['xAxis']['categories'][] = "$i";
                $statlist[$i] = 0;
            }
            $field .= ' ,HOUR(FROM_UNIXTIME(add_time)) as timeval ';
            if (C('dbdriver') == 'oracle') $_group = 'HOUR(FROM_UNIXTIME(add_time))';
        }
        if($this->search_arr['search_type'] == 'week'){
            //构造横轴数据
            for($i=1; $i<=7; $i++){
                $tmp_weekarr = getSystemWeekArr();
                //横轴
                $stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
                unset($tmp_weekarr);
                $statlist[$i] = 0;
            }
            $field .= ' ,WEEKDAY(FROM_UNIXTIME(add_time))+1 as timeval ';
            if (C('dbdriver') == 'oracle') $_group = 'WEEKDAY(FROM_UNIXTIME(add_time))+1';
        }
        if($this->search_arr['search_type'] == 'month'){
            //计算横轴的最大量（由于每个月的天数不同）
            $dayofmonth = date('t',$searchtime_arr[0]);
            //构造横轴数据
            for($i=1; $i<=$dayofmonth; $i++){
                //横轴
                $stat_arr['xAxis']['categories'][] = $i;
                $statlist[$i] = 0;
            }
            $field .= ' ,day(FROM_UNIXTIME(add_time)) as timeval ';
            if (C('dbdriver') == 'oracle') $_group = 'day(FROM_UNIXTIME(add_time))';
        }
        $where = array();
        $where['add_time'] = array('between',$searchtime_arr);
        $statlist_tmp = $model->statByRefundreturn($where, $field, 0, 0, 'timeval asc', $_group? $_group : 'timeval');
        if ($statlist_tmp){
            foreach((array)$statlist_tmp as $k => $v){
                $statlist[$v['timeval']] = floatval($v['amount']);
            }
        }
        //得到统计图数据
        $stat_arr['legend']['enabled'] = false;
        $stat_arr['series'][0]['name'] = '退款金额';
        $stat_arr['series'][0]['data'] = array_values($statlist);
        $stat_arr['title'] = '退款金额统计';
        $stat_arr['yAxis'] = '金额';
        $stat_json = getStatData_LineLabels($stat_arr);
        Tpl::output('stat_json',$stat_json);
		Tpl::setDirquna('shop');
        Tpl::showpage('stat.linelabels','null_layout');
    }
    /**
     * 输出退款统计XML数据
     */
    public function get_refundlist_xmlOp(){
        $where = array();
        $model = Model('stat');
        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
        $where['add_time'] = array('between',$searchtime_arr);
        $model_refund = Model('refund_return');
        $refundstate_arr = $model_refund->getRefundStateArray();

        $order_type = array('add_time','refund_amount');
        $sort_type = array('asc','desc');
        $sortname = trim($this->search_arr['sortname']);
        if (!in_array($sortname,$order_type)){
            $sortname = 'add_time';
        }
        $sortorder = trim($this->search_arr['sortorder']);
        if (!in_array($sortorder,$sort_type)){
            $sortorder = 'desc';
        }
        $orderby = $sortname.' '.$sortorder;
        $page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }

        $list = $model_refund->getRefundReturnList($where, $page, '*', '', $orderby);
        $statlist = array();
        if (!empty($list) && is_array($list)){
            $fields_array = array('order_sn','refund_sn','store_name','goods_name','buyer_name','add_time','refund_amount','seller_state','refund_state');
            foreach ($list as $k => $v){
                $out_array = getFlexigridArray(array(),$fields_array,$v);
                if ($v['goods_id'] > 0) {
                    $out_array['goods_name'] = '<a href="'.urlShop('goods', 'index', array('goods_id' => $v['goods_id'])).'" target="_blank">'.$v['goods_name'].'</a>';
                }
                $out_array['add_time'] = date('Y-m-d',$v['add_time']);
                $out_array['refund_amount'] = ncPriceFormat($v['refund_amount']);
                $out_array['seller_state'] = $refundstate_arr['seller'][$v['seller_state']];
                $out_array['refund_state'] = $v['seller_state']==2 ? $refundstate_arr['admin'][$v['refund_state']]:'无';
                $statlist[$v['refund_id']] = $out_array;
            }
        }

        $data = array();
        $data['now_page'] = $model_refund->shownowpage();
        $data['total_num'] = $model_refund->gettotalnum();
        $data['list'] = $statlist;
        echo Tpl::flexigridXML($data);exit();
    }
    /**
     * 店铺动态评分统计
     */
    public function evalstoreOp(){
        //店铺分类
        Tpl::output('class_list', rkcache('store_class', true));

        $model = Model('stat');
        $where = array();
        if(intval($_GET['store_class']) > 0){
            $where['sc_id'] = intval($_GET['store_class']);
        }
        if (trim($this->search_arr['storename'])){
            $where['seval_storename'] = array('like',"%".trim($this->search_arr['storename'])."%");
        }
        if (!empty($_GET['id']) && is_array($_GET['id'])){
            $where['seval_storeid'] = array('in',$_GET['id']);
        }
        $field = ' seval_storeid, min(seval_storename) as seval_storename';
        $field .= ' ,(SUM(seval_desccredit)/COUNT(*)) as avgdesccredit';
        $field .= ' ,(SUM(seval_servicecredit)/COUNT(*)) as avgservicecredit';
        $field .= ' ,(SUM(seval_deliverycredit)/COUNT(*)) as avgdeliverycredit';
        $orderby = 'avgdesccredit desc,seval_storeid';
        //导出Excel
        if ($this->search_arr['exporttype'] == 'excel'){
            $statlist_tmp = $model->statByStoreAndEvaluatestore($where, $field, 0, 0, $orderby, 'seval_storeid');
            foreach((array)$statlist_tmp as $k => $v){
                $tmp = $v;
                $tmp['avgdesccredit'] = round($v['avgdesccredit'],2);
                $tmp['avgservicecredit'] = round($v['avgservicecredit'],2);
                $tmp['avgdeliverycredit'] = round($v['avgdeliverycredit'],2);
                $statlist[] = $tmp;
            }
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺名称');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'描述相符度');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'服务态度');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'发货速度');
            //data
            foreach ((array)$statlist as $k => $v){
                $excel_data[$k+1][] = array('data'=>$v['seval_storename']);
                $excel_data[$k+1][] = array('data'=>$v['avgdesccredit']);
                $excel_data[$k+1][] = array('data'=>$v['avgservicecredit']);
                $excel_data[$k+1][] = array('data'=>$v['avgdeliverycredit']);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $excel_obj->addWorksheet($excel_obj->charset('店铺动态评分统计',CHARSET));
            $excel_obj->generateXML($excel_obj->charset('店铺动态评分统计',CHARSET).date('Y-m-d-H',time()));
            exit();
        }
        Tpl::output('top_link',$this->sublink($this->links, 'evalstore'));
		Tpl::setDirquna('shop');
        Tpl::showpage('stat.aftersale.evalstore');
    }

    /**
     * 输出店铺动态评分统计XML数据
     */
    public function get_evalstore_xmlOp(){
        $model = Model('stat');
        $where = array();
        if(intval($_GET['store_class']) > 0){
            $where['sc_id'] = intval($_GET['store_class']);
        }
        if (trim($this->search_arr['storename'])){
            $where['seval_storename'] = array('like',"%".trim($this->search_arr['storename'])."%");
        }
        $field = ' seval_storeid, min(seval_storename) as seval_storename';
        $field .= ' ,(SUM(seval_desccredit)/COUNT(*)) as avgdesccredit';
        $field .= ' ,(SUM(seval_servicecredit)/COUNT(*)) as avgservicecredit';
        $field .= ' ,(SUM(seval_deliverycredit)/COUNT(*)) as avgdeliverycredit';

        $order_type = array('seval_storename','avgdesccredit','avgservicecredit','avgdeliverycredit');
        $sort_type = array('asc','desc');
        $sortname = trim($this->search_arr['sortname']);
        if (!in_array($sortname,$order_type)){
            $sortname = 'avgdesccredit';
        }
        $sortorder = trim($this->search_arr['sortorder']);
        if (!in_array($sortorder,$sort_type)){
            $sortorder = 'desc';
        }
        $orderby = $sortname.' '.$sortorder.',seval_storeid';
        $page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }
        //查询评论的店铺总数
        $count_arr = $model->statByStoreAndEvaluatestore($where, 'count(DISTINCT evaluate_store.seval_storeid) as countnum');
        $countnum = intval($count_arr[0]['countnum']);
        $list = $model->statByStoreAndEvaluatestore($where, $field, array($page,$countnum), 0, $orderby, 'seval_storeid');
        $statlist = array();
        if (!empty($list) && is_array($list)){
            foreach ($list as $k => $v){
                $out_array = getFlexigridArray(array(),$order_type,$v);
                $out_array['avgdesccredit'] = round($v['avgdesccredit'],2);
                $out_array['avgservicecredit'] = round($v['avgservicecredit'],2);
                $out_array['avgdeliverycredit'] = round($v['avgdeliverycredit'],2);
                $statlist[$v['seval_storeid']] = $out_array;
            }
        }
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $countnum;
        $data['list'] = $statlist;
        echo Tpl::flexigridXML($data);exit();
    }
}
