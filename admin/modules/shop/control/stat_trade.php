<?php
/**
 * 统计管理（销量分析）
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class stat_tradeControl extends SystemControl{
    private $links = array(
        array('url'=>'act=stat_trade&op=income','lang'=>'stat_sale_income'),
        array('url'=>'act=stat_trade&op=predeposit','lang'=>'stat_predeposit'),
        array('url'=>'act=stat_trade&op=sale','lang'=>'stat_sale')
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
        if (in_array($_REQUEST['op'],array('sale_trend','get_sale_xml','get_plat_sale'))){
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
        $this->incomeOp();
    }

    /**
     * 销售收入统计
     */
    public function incomeOp(){
        $model = Model('stat');
        $where = array();
        if ($_GET['store_name']) {
            $where['ob_store_name'] = array('like',"%{$_GET['store_name']}%");
        }
        if (trim($_GET['query_start_date']) && trim($_GET['query_end_date'])) {
            $sdate = strtotime($_GET['query_start_date']);
            $edate = strtotime($_GET['query_end_date']);
            $where['ob_end_date'] = array('between', "$sdate,$edate");
        } elseif (trim($_GET['query_start_date'])) {
            $sdate = strtotime($_GET['query_start_date']);
            $where['ob_end_date'] = array('egt', $sdate);
        } elseif (trim($_GET['query_end_date'])) {
            $edate = strtotime($_GET['query_end_date']);
            $where['ob_end_date'] = array('elt', $edate);
        }
        if($_GET['exporttype'] == 'excel'){
            //获取全部店铺结账数据
            $field = array('min(ob_id) ob_id, min(ob_store_id) ob_store_id, min(ob_store_name) ob_store_name, SUM(ob_order_totals) as ob_order_totals','SUM(ob_commis_totals) as ob_commis_totals','SUM(ob_order_return_totals) as ob_order_return_totals','SUM(ob_commis_return_totals) as ob_commis_return_totals','SUM(ob_store_cost_totals) as ob_store_cost_totals','SUM(ob_result_totals) as ob_result_totals');
            $bill_list = $model->getBillList($where, $field, 0, 0, 'ob_id desc', 'ob_store_id');
            //导出Excel
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            $excel_data[0][0] = array('styleid'=>'s_title','data'=>'店铺名称');
            //$excel_data[0][1] = array('styleid'=>'s_title','data'=>'商家账号');
            $excel_data[0][1] = array('styleid'=>'s_title','data'=>'订单金额');
            $excel_data[0][2] = array('styleid'=>'s_title','data'=>'收取佣金');
            $excel_data[0][3] = array('styleid'=>'s_title','data'=>'退单金额');
            $excel_data[0][4] = array('styleid'=>'s_title','data'=>'退回佣金');
            $excel_data[0][5] = array('styleid'=>'s_title','data'=>'店铺费用');
            $excel_data[0][6] = array('styleid'=>'s_title','data'=>'结算金额');
            //data
            foreach ($bill_list as $k => $v){
                $excel_data[$k+1][0] = array('data'=>$v['ob_store_name']);
                //$excel_data[$k+1][1] = array('data'=>$v['member_name']);
                $excel_data[$k+1][1] = array('data'=>$v['ob_order_totals']);
                $excel_data[$k+1][2] = array('data'=>$v['ob_commis_totals']);
                $excel_data[$k+1][3] = array('data'=>$v['ob_order_return_totals']);
                $excel_data[$k+1][4] = array('data'=>$v['ob_commis_return_totals']);
                $excel_data[$k+1][5] = array('data'=>$v['ob_store_cost_totals']);
                $excel_data[$k+1][6] = array('data'=>$v['ob_result_totals']);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $excel_obj->addWorksheet($excel_obj->charset('店铺佣金统计',CHARSET));
            $excel_obj->generateXML($excel_obj->charset('店铺佣金统计',CHARSET).date('Y-m-d-H',time()));
            exit();
        }else{
            Tpl::output('top_link',$this->sublink($this->links, 'income'));
			Tpl::setDirquna('shop');
            Tpl::showpage('stat.income');
        }
    }

    /**
     * 输出平台总数据
     */
    public function get_plat_incomeOp(){
        $model = Model('stat');
        $where = array();
        if ($_GET['store_name']) {
            $where['ob_store_name'] = array('like',"%{$_GET['store_name']}%");
        }
        if (trim($_GET['query_start_date']) && trim($_GET['query_end_date'])) {
            $sdate = strtotime($_GET['query_start_date']);
            $edate = strtotime($_GET['query_end_date']);
            $where['ob_end_date'] = array('between', "$sdate,$edate");
        } elseif (trim($_GET['query_start_date'])) {
            $sdate = strtotime($_GET['query_start_date']);
            $where['ob_end_date'] = array('egt', $sdate);
        } elseif (trim($_GET['query_end_date'])) {
            $edate = strtotime($_GET['query_end_date']);
            $where['ob_end_date'] = array('elt', $edate);
        }
        $field = array('SUM(ob_order_totals) as ob_order_totals','SUM(ob_commis_totals) as ob_commis_totals','SUM(ob_order_return_totals) as ob_order_return_totals','SUM(ob_commis_return_totals) as ob_commis_return_totals','SUM(ob_store_cost_totals) as ob_store_cost_totals','SUM(ob_result_totals) as ob_result_totals');
        //获取平台总数据
        $plat_data = $model->getBillList($where, $field, 0, 1, 'ob_id desc', '');
		echo '<div class="title"><h3>销售收入情况一览</h3></div>';
		echo '<dl class="row"><dd class="opt"><ul class="nc-row">';
        echo '<li title="收款金额：'. number_format($plat_data[0]['ob_order_totals'],2).'元"><h4>收款金额</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.$plat_data[0]['ob_order_totals'].'"></h2><h6>元</h6></li>';
        echo '<li title="退款金额：'. number_format($plat_data[0]['os_order_return_totals'],2).'元"><h4>退款金额</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.$plat_data[0]['os_order_return_totals'].'"></h2><h6>元</h6></li>';
        echo '<li title="实收金额：'. (number_format($plat_data[0]['ob_order_totals']-$plat_data[0]['os_order_return_totals'],2)).'元"><h4>实收金额</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'. ($plat_data[0]['ob_order_totals']-$plat_data[0]['os_order_return_totals']).'"></h2><h6>元</h6></li>';
        echo '<li title="佣金总额：'. number_format($plat_data[0]['os_commis_totals']-$plat_data[0]['os_commis_return_totals'],2).'元"><h4>佣金总额</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'. ($plat_data[0]['os_commis_totals']-$plat_data[0]['os_commis_return_totals']).'"></h2><h6>元</h6></li>';
        echo '<li title="店铺费用：'. number_format($plat_data[0]['os_store_cost_totals'],2).'元"><h4>店铺费用</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.$plat_data[0]['os_store_cost_totals'].'"></h2><h6>元</h6></li>';
        echo '<li title="总收入：'. number_format($plat_data[0]['os_result_totals'],2).'元"><h4>总收入</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.$plat_data[0]['os_result_totals'].'"></h2><h6>元</h6></li>';
		echo '</ul></dd><dl>';
        exit();
    }

    /**
     * 输出销售收入统计XML数据
     */
    public function get_income_xmlOp(){
        $model = Model('stat');
        $where = array();
        if ($_GET['store_name']) {
            $where['ob_store_name'] = array('like',"%{$_GET['store_name']}%");
        }
        if (trim($_GET['query_start_date']) && trim($_GET['query_end_date'])) {
            $sdate = strtotime($_GET['query_start_date']);
            $edate = strtotime($_GET['query_end_date']);
            $where['ob_end_date'] = array('between', "$sdate,$edate");
        } elseif (trim($_GET['query_start_date'])) {
            $sdate = strtotime($_GET['query_start_date']);
            $where['ob_end_date'] = array('egt', $sdate);
        } elseif (trim($_GET['query_end_date'])) {
            $edate = strtotime($_GET['query_end_date']);
            $where['ob_end_date'] = array('elt', $edate);
        }

        $order_type = array('ob_order_totals','ob_commis_totals','ob_order_return_totals','ob_commis_return_totals','ob_store_cost_totals','ob_result_totals');
        $sort_type = array('asc','desc');
        $sortname = trim($this->search_arr['sortname']);
        if (!in_array($sortname,$order_type)){
            $sortname = 'ob_id';
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
        //店铺数据
        $field = array('min(ob_id) ob_id, min(ob_store_id) ob_store_id, min(ob_store_name) ob_store_name, SUM(ob_order_totals) as ob_order_totals','SUM(ob_commis_totals) as ob_commis_totals','SUM(ob_order_return_totals) as ob_order_return_totals','SUM(ob_commis_return_totals) as ob_commis_return_totals','SUM(ob_store_cost_totals) as ob_store_cost_totals','SUM(ob_result_totals) as ob_result_totals');
        $list = $model->getBillList($where, $field, $page, 0, $orderby, 'ob_store_id');
        $statlist = array();
        if (!empty($list) && is_array($list)){
            $format_array = array('ob_order_totals','ob_commis_totals','ob_order_return_totals','ob_commis_return_totals','ob_store_cost_totals','ob_result_totals');
            foreach ($list as $k => $v){
                $out_array = array();
                $out_array['operation'] = '';
                //$out_array['operation'] = '<a class="btn green" href="index.php?act=stat_trade&op=sale&search_type=month&searchmonth_month='.$_GET['search_month'].
                //'&searchmonth_year='.$_GET['search_year'].'&store_name='.$v['ob_store_name'].'&order_type=40"><i class="fa fa-area-chart"></i>详细</a>';
                $out_array['ob_store_name'] = '<a href="'.urlShop('show_store', 'index', array('store_id' => $v['ob_store_id'])).'" target="_blank">'.$v['ob_store_name'].'</a>';
                $out_array = getFlexigridArray($out_array,$order_type,$v,$format_array);
                $statlist[$v['ob_store_id']] = $out_array;
            }
        }

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        $data['list'] = $statlist;
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 预存款统计
     */
    public function predepositOp(){
        $where = array();
        if(trim($_GET['pd_type'])=='cash_pay'){
            $field = 'sum(lg_freeze_amount) as allnum';
        }else{
            $field = 'sum(lg_av_amount) as allnum';
        }
        if(!$_REQUEST['search_type']){
            $_REQUEST['search_type'] = 'day';
        }
        $where['lg_type'] = trim($_GET['pd_type'])==''?'recharge':trim($_GET['pd_type']);
        //初始化时间
        //天
        if(!$_REQUEST['search_time']){
            $_REQUEST['search_time'] = date('Y-m-d', time()-86400);
        }
        $search_time = strtotime($_REQUEST['search_time']);//搜索的时间
        Tpl::output('search_time',$_REQUEST['search_time']);
        //周
        if(!$_REQUEST['search_time_year']){
            $_REQUEST['search_time_year'] = date('Y', time());
        }
        if(!$_REQUEST['search_time_month']){
            $_REQUEST['search_time_month'] = date('m', time());
        }
        if(!$_REQUEST['search_time_week']){
            $_REQUEST['search_time_week'] =  implode('|', getWeek_SdateAndEdate(time()));
        }
        $current_year = $_REQUEST['search_time_year'];
        $current_month = $_REQUEST['search_time_month'];
        $current_week = $_REQUEST['search_time_week'];
        $year_arr = getSystemYearArr();
        $month_arr = getSystemMonthArr();
        $week_arr = getMonthWeekArr($current_year, $current_month);

        Tpl::output('current_year', $current_year);
        Tpl::output('current_month', $current_month);
        Tpl::output('current_week', $current_week);
        Tpl::output('year_arr', $year_arr);
        Tpl::output('month_arr', $month_arr);
        Tpl::output('week_arr', $week_arr);

        $model = Model('stat');
        $statlist = array();//统计数据列表
        if($_REQUEST['search_type'] == 'day'){
            $stime = $search_time - 86400;//昨天0点
            $etime = $search_time + 86400 - 1;//今天24点
            $where['lg_add_time'] = array('between',array($stime,$etime));
        }

        if($_REQUEST['search_type'] == 'week'){
            $current_weekarr = explode('|', $current_week);
            $stime = strtotime($current_weekarr[0])-86400*7;
            $etime = strtotime($current_weekarr[1])+86400-1;
            $where['lg_add_time'] = array('between', array($stime,$etime));
        }

        if($_REQUEST['search_type'] == 'month'){
            $stime = strtotime($current_year.'-'.$current_month."-01 -1 month");
            $etime = getMonthLastDay($current_year,$current_month)+86400-1;
            $where['lg_add_time'] = array('between', array($stime,$etime));
        }
        if (!empty($_GET['id']) && is_array($_GET['id'])){
            $where['lg_id'] = array('in',$_GET['id']);
        }
        //导出Excel
        if ($_GET['exporttype'] == 'excel'){
            //获取数据
            $log_list = $model->getPredepositInfo($where, '*', '');
            //导出Excel
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'会员名称');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'创建时间');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'可用金额（元）');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'冻结金额（元）');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'管理员名称');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'类型');
            $excel_data[0][] = array('styleid'=>'s_title','data'=>'描述');
            //data
            foreach ($log_list as $k => $v){
                $excel_data[$k+1][] = array('data'=>$v['lg_member_name']);
                $excel_data[$k+1][] = array('data'=>date('Y-m-d H:i:s',$v['lg_add_time']));
                $excel_data[$k+1][] = array('data'=>$v['lg_av_amount']);
                $excel_data[$k+1][] = array('data'=>$v['lg_freeze_amount']);
                $excel_data[$k+1][] = array('data'=>$v['lg_admin_name']);
                switch ($v['lg_type']){
                    case 'recharge':
                        $excel_data[$k+1][] = array('data'=>'充值');
                        break;
                    case 'order_pay':
                        $excel_data[$k+1][] = array('data'=>'消费');
                        break;
                    case 'cash_pay':
                        $excel_data[$k+1][] = array('data'=>'提现');
                        break;
                    case 'refund':
                        $excel_data[$k+1][] = array('data'=>'退款');
                        break;
                }
                $excel_data[$k+1][] = array('data'=>$v['lg_desc']);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $excel_obj->addWorksheet($excel_obj->charset('预存款统计',CHARSET));
            $excel_obj->generateXML($excel_obj->charset('预存款统计',CHARSET).date('Y-m-d-H',time()));
            exit();
        } else {
            Tpl::output('top_link',$this->sublink($this->links, 'predeposit'));
			Tpl::setDirquna('shop');
            Tpl::showpage('stat.predeposit');
        }
    }

    /**
     * 预存款统计
     */
    public function get_predeposit_highchartsOp(){
        $where = array();
        if(trim($_GET['pd_type'])=='cash_pay'){
            $field = 'sum(lg_freeze_amount) as allnum';
        }else{
            $field = 'sum(lg_av_amount) as allnum';
        }
        if(!$_REQUEST['search_type']){
            $_REQUEST['search_type'] = 'day';
        }
        $where['lg_type'] = trim($_GET['pd_type'])==''?'recharge':trim($_GET['pd_type']);
        //初始化时间
        //天
        if(!$_REQUEST['search_time']){
            $_REQUEST['search_time'] = date('Y-m-d', time()-86400);
        }
        $search_time = strtotime($_REQUEST['search_time']);//搜索的时间
        Tpl::output('search_time',$_REQUEST['search_time']);
        //周
        if(!$_REQUEST['search_time_year']){
            $_REQUEST['search_time_year'] = date('Y', time());
        }
        if(!$_REQUEST['search_time_month']){
            $_REQUEST['search_time_month'] = date('m', time());
        }
        if(!$_REQUEST['search_time_week']){
            $_REQUEST['search_time_week'] =  implode('|', getWeek_SdateAndEdate(time()));
        }
        $current_year = $_REQUEST['search_time_year'];
        $current_month = $_REQUEST['search_time_month'];
        $current_week = $_REQUEST['search_time_week'];

        $model = Model('stat');
        $statlist = array();//统计数据列表
        if($_REQUEST['search_type'] == 'day'){
            //构造横轴数据
            for($i=0; $i<24; $i++){
                //统计图数据
                $curr_arr[$i] = 0;//今天
                $up_arr[$i] = 0;//昨天
                //统计表数据
                $uplist_arr[$i]['timetext'] = $i;
                $currlist_arr[$i]['timetext'] = $i;
                $uplist_arr[$i]['val'] = 0;
                $currlist_arr[$i]['val'] = 0;
                //横轴
                $stat_arr['xAxis']['categories'][] = "$i";
            }
            $stime = $search_time - 86400;//昨天0点
            $etime = $search_time + 86400 - 1;//今天24点

            $today_day = @date('d', $search_time);//今天日期
            $yesterday_day = @date('d', $stime);//昨天日期

            $where['lg_add_time'] = array('between',array($stime,$etime));
            $field .= ' ,DAY(FROM_UNIXTIME(lg_add_time)) as dayval,HOUR(FROM_UNIXTIME(lg_add_time)) as hourval ';
            if (C('dbdriver') == 'oracle') $_group = 'DAY(FROM_UNIXTIME(lg_add_time)),HOUR(FROM_UNIXTIME(lg_add_time))';
            $memberlist = $model->getPredepositInfo($where, $field, 0, '', 0, $_group ? $_group : 'dayval,hourval');
            if($memberlist){
                foreach($memberlist as $k => $v){
                    if($today_day == $v['dayval']){
                        $curr_arr[$v['hourval']] = abs($v['allnum']);
                        $currlist_arr[$v['hourval']]['val'] = abs($v['allnum']);
                    }
                    if($yesterday_day == $v['dayval']){
                        $up_arr[$v['hourval']] = abs($v['allnum']);
                        $uplist_arr[$v['hourval']]['val'] = abs($v['allnum']);
                    }
                }
            }
            $stat_arr['series'][0]['name'] = '昨天';
            $stat_arr['series'][0]['data'] = array_values($up_arr);
            $stat_arr['series'][1]['name'] = '今天';
            $stat_arr['series'][1]['data'] = array_values($curr_arr);

            //统计数据标题
            $statlist['headertitle'] = array('小时','昨天','今天','同比');
        }

        if($_REQUEST['search_type'] == 'week'){
            $current_weekarr = explode('|', $current_week);
            $stime = strtotime($current_weekarr[0])-86400*7;
            $etime = strtotime($current_weekarr[1])+86400-1;
            $up_week = @date('W', $stime);//上周
            $curr_week = @date('W', $etime);//本周
            //构造横轴数据
            for($i=1; $i<=7; $i++){
                //统计图数据
                $up_arr[$i] = 0;
                $curr_arr[$i] = 0;
                $tmp_weekarr = getSystemWeekArr();
                //统计表数据
                $uplist_arr[$i]['timetext'] = $tmp_weekarr[$i];
                $currlist_arr[$i]['timetext'] = $tmp_weekarr[$i];
                $uplist_arr[$i]['val'] = 0;
                $currlist_arr[$i]['val'] = 0;
                //横轴
                $stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
                unset($tmp_weekarr);
            }
            $where['lg_add_time'] = array('between', array($stime,$etime));
            $field .= ',WEEKOFYEAR(FROM_UNIXTIME(lg_add_time)) as weekval,WEEKDAY(FROM_UNIXTIME(lg_add_time))+1 as dayofweekval ';
            if (C('dbdriver') == 'mysql') {
                $_group = 'weekval,dayofweekval';
            } elseif (C('dbdriver') == 'oracle') {
                $_group = 'WEEKOFYEAR(FROM_UNIXTIME(lg_add_time)),WEEKDAY(FROM_UNIXTIME(lg_add_time))+1';
            }
            $memberlist = $model->getPredepositInfo($where, $field, 0, '', 0, $_group);
            if($memberlist){
                foreach($memberlist as $k => $v){
                    if ($up_week == $v['weekval']){
                        $up_arr[$v['dayofweekval']] = abs($v['allnum']);
                        $uplist_arr[$v['dayofweekval']]['val'] = abs($v['allnum']);
                    }
                    if ($curr_week == $v['weekval']){
                        $curr_arr[$v['dayofweekval']] = abs($v['allnum']);
                        $currlist_arr[$v['dayofweekval']]['val'] = abs($v['allnum']);
                    }
                }
            }
            $stat_arr['series'][0]['name'] = '上周';
            $stat_arr['series'][0]['data'] = array_values($up_arr);
            $stat_arr['series'][1]['name'] = '本周';
            $stat_arr['series'][1]['data'] = array_values($curr_arr);
            //统计数据标题
            $statlist['headertitle'] = array('星期','上周','本周','同比');
        }

        if($_REQUEST['search_type'] == 'month'){
            $stime = strtotime($current_year.'-'.$current_month."-01 -1 month");
            $etime = getMonthLastDay($current_year,$current_month)+86400-1;

            $up_month = date('m',$stime);
            $curr_month = date('m',$etime);
            //计算横轴的最大量（由于每个月的天数不同）
            $up_dayofmonth = date('t',$stime);
            $curr_dayofmonth = date('t',$etime);
            $x_max = $up_dayofmonth > $curr_dayofmonth ? $up_dayofmonth : $curr_dayofmonth;

            //构造横轴数据
            for($i=1; $i<=$x_max; $i++){
                //统计图数据
                $up_arr[$i] = 0;
                $curr_arr[$i] = 0;
                //统计表数据
                $uplist_arr[$i]['timetext'] = $i;
                $currlist_arr[$i]['timetext'] = $i;
                $uplist_arr[$i]['val'] = 0;
                $currlist_arr[$i]['val'] = 0;
                //横轴
                $stat_arr['xAxis']['categories'][] = $i;
            }
            $where['lg_add_time'] = array('between', array($stime,$etime));
            $field .= ',MONTH(FROM_UNIXTIME(lg_add_time)) as monthval,day(FROM_UNIXTIME(lg_add_time)) as dayval ';
            if (C('dbdriver') == 'mysql') {
                $_group = 'monthval,dayval';
            } elseif (C('dbdriver') == 'oracle') {
                $_group = 'MONTH(FROM_UNIXTIME(lg_add_time)),day(FROM_UNIXTIME(lg_add_time))';
            }
            $memberlist = $model->getPredepositInfo($where, $field, 0, '', 0, $_group);
            if($memberlist){
                foreach($memberlist as $k => $v){
                    if ($up_month == $v['monthval']){
                        $up_arr[$v['dayval']] = abs($v['allnum']);
                        $uplist_arr[$v['dayval']]['val'] = abs($v['allnum']);
                    }
                    if ($curr_month == $v['monthval']){
                        $curr_arr[$v['dayval']] = abs($v['allnum']);
                        $currlist_arr[$v['dayval']]['val'] = abs($v['allnum']);
                    }
                }
            }
            $stat_arr['series'][0]['name'] = '上月';
            $stat_arr['series'][0]['data'] = array_values($up_arr);
            $stat_arr['series'][1]['name'] = '本月';
            $stat_arr['series'][1]['data'] = array_values($curr_arr);
            //统计数据标题
            $statlist['headertitle'] = array('日期','上月','本月','同比');
        }

        //计算同比
        foreach ((array)$currlist_arr as $k => $v){
            $tmp = array();
            $tmp['timetext'] = $v['timetext'];
            $tmp['currentdata'] = $v['val'];
            $tmp['updata'] = $uplist_arr[$k]['val'];
            $tmp['tbrate'] = getTb($tmp['updata'], $tmp['currentdata']);
            $statlist['data'][]  = $tmp;
        }
        //得到统计图数据
        $stat_arr['title'] = '预存款统计';
        $stat_arr['yAxis'] = '金额';
        $stat_json = getStatData_LineLabels($stat_arr);
        Tpl::output('stat_json',$stat_json);
		Tpl::setDirquna('shop');
        Tpl::showpage('stat.linelabels','null_layout');
    }

    /**
     * 获取平台总预存款统计
     */
    public function get_plat_predepositOp(){
        $model = Model('stat');
        $where = array();
        if(trim($_GET['pd_type'])=='cash_pay'){
            $field = 'sum(lg_freeze_amount) as allnum';
        }else{
            $field = 'sum(lg_av_amount) as allnum';
        }
        if(!$_REQUEST['search_type']){
            $_REQUEST['search_type'] = 'day';
        }
        $where['lg_type'] = trim($_GET['pd_type'])==''?'recharge':trim($_GET['pd_type']);
        //初始化时间
        //天
        if(!$_REQUEST['search_time']){
            $_REQUEST['search_time'] = date('Y-m-d', time()-86400);
        }
        $search_time = strtotime($_REQUEST['search_time']);//搜索的时间
        Tpl::output('search_time',$_REQUEST['search_time']);
        //周
        if(!$_REQUEST['search_time_year']){
            $_REQUEST['search_time_year'] = date('Y', time());
        }
        if(!$_REQUEST['search_time_month']){
            $_REQUEST['search_time_month'] = date('m', time());
        }
        if(!$_REQUEST['search_time_week']){
            $_REQUEST['search_time_week'] =  implode('|', getWeek_SdateAndEdate(time()));
        }
        $current_year = $_REQUEST['search_time_year'];
        $current_month = $_REQUEST['search_time_month'];
        $current_week = $_REQUEST['search_time_week'];
        if($_REQUEST['search_type'] == 'day'){
            $stime = $search_time - 86400;//昨天0点
            $etime = $search_time + 86400 - 1;//今天24点
        }

        if($_REQUEST['search_type'] == 'week'){
            $current_weekarr = explode('|', $current_week);
            $stime = strtotime($current_weekarr[0])-86400*7;
            $etime = strtotime($current_weekarr[1])+86400-1;
        }

        if($_REQUEST['search_type'] == 'month'){
            $stime = strtotime($current_year.'-'.$current_month."-01 -1 month");
            $etime = getMonthLastDay($current_year,$current_month)+86400-1;
        }

        $recharge_amount = $model->getPredepositInfo(array('lg_type'=>'recharge','lg_add_time'=>array('between', array($stime,$etime))), 'sum(lg_av_amount) as allnum');
        $order_amount = $model->getPredepositInfo(array('lg_type'=>'order_pay','lg_add_time'=>array('between', array($stime,$etime))), 'sum(lg_av_amount) as allnum');
        $cash_amount = $model->getPredepositInfo(array('lg_type'=>'cash_pay','lg_add_time'=>array('between', array($stime,$etime))), 'sum(lg_freeze_amount) as allnum');
        $usable_amount = $model->getPredepositInfo(true, 'sum(lg_av_amount+lg_freeze_amount) as allnum');
        $user_amount = $model->getPredepositInfo(true, 'COUNT(distinct lg_member_id) AS allnum',0,'');
		echo '<div class="title"><h3>预存款情况一览</h3></div>';
		echo '<dl class="row"><dd class="opt"><ul class="nc-row">';
		echo '<li title="存入金额：'. number_format($recharge_amount[0]['allnum'],2).'元"><h4>存入金额</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.$recharge_amount[0]['allnum'].'"></h2><h6>元</h6></li>';
		echo '<li title="消费金额：'. number_format(abs($order_amount[0]['allnum']),2).'元"><h4>消费金额</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.abs($order_amount[0]['allnum']).'"></h2><h6>元</h6></li>';
		echo '<li title="提现金额：'. number_format(abs($cash_amount[0]['allnum']),2).'元"><h4>提现金额</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.abs($cash_amount[0]['allnum']).'"></h2><h6>元</h6></li>';
		echo '<li title="总余额：'. number_format($usable_amount[0]['allnum'],2).'元"><h4>总余额</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.$usable_amount[0]['allnum'].'"></h2><h6>元</h6></li>';
		echo '<li title="使用总人数：'. intval($user_amount[0]['allnum']).'人"><h4>使用总人数</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.$user_amount[0]['allnum'].'"></h2><h6>人</h6></li>';
		echo '</ul></dd><dl>';
        exit();
    }

    /**
     * 输出销售收入统计XML数据
     */
    public function get_predeposit_xmlOp(){
        $model = Model('stat');
        $where = array();
        if(trim($_GET['pd_type'])=='cash_pay'){
            $field = 'sum(lg_freeze_amount) as allnum';
        }else{
            $field = 'sum(lg_av_amount) as allnum';
        }
        if(!$_REQUEST['search_type']){
            $_REQUEST['search_type'] = 'day';
        }
        $where['lg_type'] = trim($_GET['pd_type'])==''?'recharge':trim($_GET['pd_type']);
        //初始化时间
        //天
        if(!$_REQUEST['search_time']){
            $_REQUEST['search_time'] = date('Y-m-d', time()-86400);
        }
        $search_time = strtotime($_REQUEST['search_time']);//搜索的时间
        Tpl::output('search_time',$_REQUEST['search_time']);
        //周
        if(!$_REQUEST['search_time_year']){
            $_REQUEST['search_time_year'] = date('Y', time());
        }
        if(!$_REQUEST['search_time_month']){
            $_REQUEST['search_time_month'] = date('m', time());
        }
        if(!$_REQUEST['search_time_week']){
            $_REQUEST['search_time_week'] =  implode('|', getWeek_SdateAndEdate(time()));
        }
        $current_year = $_REQUEST['search_time_year'];
        $current_month = $_REQUEST['search_time_month'];
        $current_week = $_REQUEST['search_time_week'];
        if($_REQUEST['search_type'] == 'day'){
            $stime = $search_time - 86400;//昨天0点
            $etime = $search_time + 86400 - 1;//今天24点
        }

        if($_REQUEST['search_type'] == 'week'){
            $current_weekarr = explode('|', $current_week);
            $stime = strtotime($current_weekarr[0])-86400*7;
            $etime = strtotime($current_weekarr[1])+86400-1;
        }

        if($_REQUEST['search_type'] == 'month'){
            $stime = strtotime($current_year.'-'.$current_month."-01 -1 month");
            $etime = getMonthLastDay($current_year,$current_month)+86400-1;
        }
        $where['lg_add_time'] = array('between', array($stime,$etime));

        $order_type = array('lg_member_name','lg_add_time','lg_av_amount','lg_freeze_amount','lg_admin_name','lg_type','lg_desc');
        $sort_type = array('asc','desc');
        $sortname = trim($this->search_arr['sortname']);
        if (!in_array($sortname,$order_type)){
            $sortname = 'lg_add_time';
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
        $list = $model->getPredepositInfo($where, '*', $page, $orderby);

        $statlist = array();
        if (!empty($list) && is_array($list)){
            $format_array = array('lg_av_amount','lg_freeze_amount');
            foreach ($list as $k => $v){
                $out_array = getFlexigridArray(array(),$order_type,$v,$format_array);
                switch ($out_array['lg_type']){
                    case 'recharge':
                        $out_array['lg_type'] = '充值';
                        break;
                    case 'order_pay':
                        $out_array['lg_type'] = '消费';
                        break;
                    case 'cash_pay':
                        $out_array['lg_type'] = '提现';
                        break;
                    case 'refund':
                        $out_array['lg_type'] = '退款';
                        break;
                }
                $out_array['lg_add_time'] = date('Y-m-d H:i:s',$v['lg_add_time']);
                $statlist[$v['lg_id']] = $out_array;
            }
        }
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        $data['list'] = $statlist;
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 订单统计
     */
    public function saleOp(){
        $model = Model('stat');
        //存储参数
        $this->search_arr = $_REQUEST;
        //处理搜索时间
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
        Tpl::output('search_arr', $this->search_arr);

        //默认统计当前数据
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
        //计算昨天和今天时间
        if($this->search_arr['search_type'] == 'day'){
            $stime = $this->search_arr['day']['search_time'] - 86400;//昨天0点
            $etime = $this->search_arr['day']['search_time'] + 86400 - 1;//今天24点
            $curr_stime = $this->search_arr['day']['search_time'];//今天0点
        } elseif ($this->search_arr['search_type'] == 'week'){
            $current_weekarr = explode('|', $this->search_arr['week']['current_week']);
            $stime = strtotime($current_weekarr[0])-86400*7;
            $etime = strtotime($current_weekarr[1])+86400-1;
            $curr_stime = strtotime($current_weekarr[0]);//本周0点
        } elseif ($this->search_arr['search_type'] == 'month'){
            $stime = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01 -1 month");
            $etime = getMonthLastDay($this->search_arr['month']['current_year'],$this->search_arr['month']['current_month'])+86400-1;
            $curr_stime = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01");;//本月0点
        }

        $where = array();
        $where['order_add_time'] = array('between',array($curr_stime,$etime));
        if(trim($_GET['order_type']) != ''){
            $where['order_state'] = trim($_GET['order_type']);
        }
        if(trim($_GET['store_name']) != ''){
            $where['store_name'] = array('like','%'.trim($_GET['store_name']).'%');
        }
        if (!empty($_GET['id']) && is_array($_GET['id'])){
            $where['order_id'] = array('in',$_GET['id']);
        }
        //导出Excel
        if ($_GET['exporttype'] == 'excel'){
            $order_list = $model->statByStatorder($where, '', 0, 0, 'order_id desc', '');
            //统计数据标题
            $statlist = array();
            $statlist['headertitle'] = array('订单号','买家','店铺名称','下单时间','订单总额','订单状态');

            foreach ((array)$order_list as $k => $v){
                switch ($v['order_state']){
                    case ORDER_STATE_CANCEL:
                        $v['order_statetext'] = '已取消';
                        break;
                    case ORDER_STATE_NEW:
                        $v['order_statetext'] = '待付款';
                        break;
                    case ORDER_STATE_PAY:
                        $v['order_statetext'] = '待发货';
                        break;
                    case ORDER_STATE_SEND:
                        $v['order_statetext'] = '待收货';
                        break;
                    case ORDER_STATE_SUCCESS:
                        $v['order_statetext'] = '交易完成';
                        break;
                }
                $statlist['data'][$k]= $v;
            }
            import('libraries.excel');
            $excel_obj = new Excel();
            $excel_data = array();
            //设置样式
            $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
            //header
            foreach ($statlist['headertitle'] as $v){
                $excel_data[0][] = array('styleid'=>'s_title','data'=>$v);
            }
            //data
            foreach ((array)$statlist['data'] as $k => $v){
                $excel_data[$k+1][] = array('data'=>$v['order_sn']);
                $excel_data[$k+1][] = array('data'=>$v['buyer_name']);
                $excel_data[$k+1][] = array('data'=>$v['store_name']);
                $excel_data[$k+1][] = array('data'=>date('Y-m-d H:i:s',$v['order_add_time']));
                $excel_data[$k+1][] = array('data'=>number_format(($v['order_amount']),2));
                $excel_data[$k+1][] = array('data'=>$v['order_statetext']);
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $excel_obj->addWorksheet($excel_obj->charset('订单统计',CHARSET));
            $excel_obj->generateXML($excel_obj->charset('订单统计',CHARSET).date('Y-m-d-H',time()));
            exit();
        } else {
            Tpl::output('top_link',$this->sublink($this->links, 'sale'));
			Tpl::setDirquna('shop');
            Tpl::showpage('stat.sale');
        }
    }

    /**
     * 输出平台订单总数据
     */
    public function get_plat_saleOp(){
        $model = Model('stat');

        //默认统计当前数据
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
        //计算昨天和今天时间
        if($this->search_arr['search_type'] == 'day'){
            $stime = $this->search_arr['day']['search_time'] - 86400;//昨天0点
            $etime = $this->search_arr['day']['search_time'] + 86400 - 1;//今天24点
            $curr_stime = $this->search_arr['day']['search_time'];//今天0点
        } elseif ($this->search_arr['search_type'] == 'week'){
            $current_weekarr = explode('|', $this->search_arr['week']['current_week']);
            $stime = strtotime($current_weekarr[0])-86400*7;
            $etime = strtotime($current_weekarr[1])+86400-1;
            $curr_stime = strtotime($current_weekarr[0]);//本周0点
        } elseif ($this->search_arr['search_type'] == 'month'){
            $stime = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01 -1 month");
            $etime = getMonthLastDay($this->search_arr['month']['current_year'],$this->search_arr['month']['current_month'])+86400-1;
            $curr_stime = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01");;//本月0点
        }

        $where = array();
        $where['order_add_time'] = array('between',array($curr_stime,$etime));
        if(trim($_GET['order_type']) != ''){
            $where['order_state'] = trim($_GET['order_type']);
        }
        if(trim($_GET['store_name']) != ''){
            $where['store_name'] = array('like','%'.trim($_GET['store_name']).'%');
        }
        $statcount_arr = $model->getoneByStatorder($where,' COUNT(*) as ordernum, SUM(order_amount) as orderamount');
		echo '<div class="title"><h3>订单情况一览</h3></div>';
		echo '<dl class="row"><dd class="opt"><ul class="nc-row">';
		echo '<li title="总销售额：'. number_format(($statcount_arr['orderamount']),2).'元"><h4>总销售额</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.$statcount_arr['orderamount'].'"></h2><h6>元</h6></li>';
		echo '<li title="总订单量：'. intval($statcount_arr['ordernum']).'笔"><h4>总订单量</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'. $statcount_arr['ordernum'].'"></h2><h6>笔</h6></li>';
		echo '</ul></dd><dl>';
        exit();
    }

    /**
     * 输出订单统计XML数据
     */
    public function get_sale_xmlOp(){
        $model = Model('stat');

        //默认统计当前数据
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
        //计算昨天和今天时间
        if($this->search_arr['search_type'] == 'day'){
            $stime = $this->search_arr['day']['search_time'] - 86400;//昨天0点
            $etime = $this->search_arr['day']['search_time'] + 86400 - 1;//今天24点
            $curr_stime = $this->search_arr['day']['search_time'];//今天0点
        } elseif ($this->search_arr['search_type'] == 'week'){
            $current_weekarr = explode('|', $this->search_arr['week']['current_week']);
            $stime = strtotime($current_weekarr[0])-86400*7;
            $etime = strtotime($current_weekarr[1])+86400-1;
            $curr_stime = strtotime($current_weekarr[0]);//本周0点
        } elseif ($this->search_arr['search_type'] == 'month'){
            $stime = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01 -1 month");
            $etime = getMonthLastDay($this->search_arr['month']['current_year'],$this->search_arr['month']['current_month'])+86400-1;
            $curr_stime = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01");;//本月0点
        }

        $where = array();
        $where['order_add_time'] = array('between',array($curr_stime,$etime));
        if(trim($_GET['order_type']) != ''){
            $where['order_state'] = trim($_GET['order_type']);
        }
        if(trim($_GET['store_name']) != ''){
            $where['store_name'] = array('like','%'.trim($_GET['store_name']).'%');
        }
        $order_type = array('order_sn','buyer_name','store_name','order_add_time','order_amount','order_state');
        $sort_type = array('asc','desc');
        $sortname = trim($this->search_arr['sortname']);
        if (!in_array($sortname,$order_type)){
            $sortname = 'order_id';
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
        $list = $model->statByStatorder($where, '', $page, 0, $orderby, '');

        $statlist = array();
        if (!empty($list) && is_array($list)){
            foreach ($list as $k => $v){
                $out_array = getFlexigridArray(array(),$order_type,$v);
                switch ($out_array['order_state']){
                    case ORDER_STATE_CANCEL:
                        $out_array['order_state'] = '已取消';
                        break;
                    case ORDER_STATE_NEW:
                        $out_array['order_state'] = '待付款';
                        break;
                    case ORDER_STATE_PAY:
                        $out_array['order_state'] = '待发货';
                        break;
                    case ORDER_STATE_SEND:
                        $out_array['order_state'] = '待收货';
                        break;
                    case ORDER_STATE_SUCCESS:
                        $out_array['order_state'] = '交易完成';
                        break;
                }
                $out_array['order_add_time'] = date('Y-m-d H:i:s',$v['order_add_time']);
                $out_array['order_amount'] = number_format(($v['order_amount']),2);
                $statlist[$v['order_id']] = $out_array;
            }
        }

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        $data['list'] = $statlist;
        echo Tpl::flexigridXML($data);exit();
    }
    /**
     * 订单走势
     */
    public function sale_trendOp(){
        $model = Model('stat');
        //默认统计当前数据
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
        $where = array();
        if(trim($_GET['order_type']) != ''){
            $where['order_state'] = trim($_GET['order_type']);
        }
        if(trim($_GET['store_name']) != ''){
            $where['store_name'] = array('like','%'.trim($_GET['store_name']).'%');
        }
        $stattype = trim($_GET['type']);
        if($stattype == 'ordernum'){
            $field = ' COUNT(*) as ordernum ';
            $stat_arr['title'] = '订单量统计';
            $stat_arr['yAxis'] = '订单量';
        } else {
            $stattype = 'orderamount';
            $field = ' SUM(order_amount) as orderamount ';
            $stat_arr['title'] = '订单销售额统计';
            $stat_arr['yAxis'] = '订单销售额';
        }
        if($this->search_arr['search_type'] == 'day'){
    	    $searchtime_arr[0] = $this->search_arr['day']['search_time'] - 86400;//昨天0点
    	    $searchtime_arr[1] = $this->search_arr['day']['search_time'] + 86400 - 1;//今天24点
            //构造横轴数据
            for($i=0; $i<24; $i++){
                //统计图数据
                $curr_arr[$i] = 0;//今天
                $up_arr[$i] = 0;//昨天
                //统计表数据
                $currlist_arr[$i]['timetext'] = $i;

                $uplist_arr[$i]['val'] = 0;
                $currlist_arr[$i]['val'] = 0;
                //横轴
                $stat_arr['xAxis']['categories'][] = "$i";
            }

            $today_day = @date('d', $searchtime_arr[1]);//今天日期
            $yesterday_day = @date('d', $searchtime_arr[0]);//昨天日期

            $where['order_add_time'] = array('between',$searchtime_arr);
            $field .= ' ,DAY(FROM_UNIXTIME(order_add_time)) as dayval,HOUR(FROM_UNIXTIME(order_add_time)) as hourval ';
            if (C('dbdriver') == 'mysql') {
                $_group = 'dayval,hourval';
            } elseif (C('dbdriver') == 'oracle') {
                $_group = 'DAY(FROM_UNIXTIME(order_add_time)),HOUR(FROM_UNIXTIME(order_add_time))';
            }
            $orderlist = $model->statByStatorder($where, $field, 0, 0, '', $_group);

            foreach((array)$orderlist as $k => $v){
                if($today_day == $v['dayval']){
                    $curr_arr[$v['hourval']] = intval($v[$stattype]);
                    $currlist_arr[$v['hourval']]['val'] = $v[$stattype];
                }
                if($yesterday_day == $v['dayval']){
                    $up_arr[$v['hourval']] = intval($v[$stattype]);
                    $uplist_arr[$v['hourval']]['val'] = $v[$stattype];
                }
            }
            $stat_arr['series'][0]['name'] = '昨天';
            $stat_arr['series'][0]['data'] = array_values($up_arr);
            $stat_arr['series'][1]['name'] = '今天';
            $stat_arr['series'][1]['data'] = array_values($curr_arr);
        }

        if($this->search_arr['search_type'] == 'week'){
			$current_weekarr = explode('|', $this->search_arr['week']['current_week']);
			$searchtime_arr[0] = strtotime($current_weekarr[0])-86400*7;
			$searchtime_arr[1] = strtotime($current_weekarr[1])+86400-1;
            $up_week = @date('W', $searchtime_arr[0]);//上周
            $curr_week = @date('W', $searchtime_arr[1]);//本周
            //构造横轴数据
            for($i=1; $i<=7; $i++){
                //统计图数据
                $up_arr[$i] = 0;
                $curr_arr[$i] = 0;
                $tmp_weekarr = getSystemWeekArr();
                //统计表数据
                $uplist_arr[$i]['timetext'] = $tmp_weekarr[$i];
                $currlist_arr[$i]['timetext'] = $tmp_weekarr[$i];
                $uplist_arr[$i]['val'] = 0;
                $currlist_arr[$i]['val'] = 0;
                //横轴
                $stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
                unset($tmp_weekarr);
            }
            $where['order_add_time'] = array('between', $searchtime_arr);
            $field .= ',WEEKOFYEAR(FROM_UNIXTIME(order_add_time)) as weekval,WEEKDAY(FROM_UNIXTIME(order_add_time))+1 as dayofweekval ';
            if (C('dbdriver') == 'mysql') {
                $_group = 'weekval,dayofweekval';
            } elseif (C('dbdriver') == 'oracle') {
                $_group = 'WEEKOFYEAR(FROM_UNIXTIME(order_add_time)),WEEKDAY(FROM_UNIXTIME(order_add_time))+1';
            }
            $orderlist = $model->statByStatorder($where, $field, 0, 0, '', $_group);
            foreach((array)$orderlist as $k => $v){
                if ($up_week == $v['weekval']){
                    $up_arr[$v['dayofweekval']] = intval($v[$stattype]);
                    $uplist_arr[$v['dayofweekval']]['val'] = intval($v[$stattype]);
                }
                if ($curr_week == $v['weekval']){
                    $curr_arr[$v['dayofweekval']] = intval($v[$stattype]);
                    $currlist_arr[$v['dayofweekval']]['val'] = intval($v[$stattype]);
                }
            }
            $stat_arr['series'][0]['name'] = '上周';
            $stat_arr['series'][0]['data'] = array_values($up_arr);
            $stat_arr['series'][1]['name'] = '本周';
            $stat_arr['series'][1]['data'] = array_values($curr_arr);
        }

        if($this->search_arr['search_type'] == 'month'){
			$searchtime_arr[0] = strtotime($this->search_arr['month']['current_year'].'-'.$this->search_arr['month']['current_month']."-01 -1 month");
			$searchtime_arr[1] = getMonthLastDay($this->search_arr['month']['current_year'],$this->search_arr['month']['current_month'])+86400-1;
            $up_month = date('m',$searchtime_arr[0]);
            $curr_month = date('m',$searchtime_arr[1]);
            //计算横轴的最大量（由于每个月的天数不同）
            $up_dayofmonth = date('t',$searchtime_arr[0]);
            $curr_dayofmonth = date('t',$searchtime_arr[1]);
            $x_max = $up_dayofmonth > $curr_dayofmonth ? $up_dayofmonth : $curr_dayofmonth;

            //构造横轴数据
            for($i=1; $i<=$x_max; $i++){
                //统计图数据
                $up_arr[$i] = 0;
                $curr_arr[$i] = 0;
                //统计表数据
                $currlist_arr[$i]['timetext'] = $i;
                $uplist_arr[$i]['val'] = 0;
                $currlist_arr[$i]['val'] = 0;
                //横轴
                $stat_arr['xAxis']['categories'][] = $i;
            }
            $where['order_add_time'] = array('between', array($searchtime_arr[0],$searchtime_arr[1]));
            $field .= ',MONTH(FROM_UNIXTIME(order_add_time)) as monthval,day(FROM_UNIXTIME(order_add_time)) as dayval ';
            if (C('dbdriver') == 'mysql') {
                $_group = 'monthval,dayval';
            } elseif (C('dbdriver') == 'oracle') {
                $_group = 'MONTH(FROM_UNIXTIME(order_add_time)),day(FROM_UNIXTIME(order_add_time))';
            }
            $orderlist = $model->statByStatorder($where, $field, 0, 0, '', $_group);
            foreach($orderlist as $k => $v){
                if ($up_month == $v['monthval']){
                    $up_arr[$v['dayval']] = intval($v[$stattype]);
                    $uplist_arr[$v['dayval']]['val'] = intval($v[$stattype]);
                }
                if ($curr_month == $v['monthval']){
                    $curr_arr[$v['dayval']] = intval($v[$stattype]);
                    $currlist_arr[$v['dayval']]['val'] = intval($v[$stattype]);
                }
            }
            $stat_arr['series'][0]['name'] = '上月';
            $stat_arr['series'][0]['data'] = array_values($up_arr);
            $stat_arr['series'][1]['name'] = '本月';
            $stat_arr['series'][1]['data'] = array_values($curr_arr);
        }
        $stat_json = getStatData_LineLabels($stat_arr);
        Tpl::output('stat_json',$stat_json);
        Tpl::output('stattype',$stattype);
		Tpl::setDirquna('shop');
        Tpl::showpage('stat.linelabels','null_layout');
    }
}
