<?php
/**
 * 营销分析
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class stat_marketingControl extends SystemControl{
    private $links = array(
        array('url'=>'act=stat_marketing&op=promotion','lang'=>'stat_promotion'),
        array('url'=>'act=stat_marketing&op=group','lang'=>'stat_group'),
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
        if (!isset($this->search_arr['op']) || in_array($this->search_arr['op'],array('promotion','group','get_groupgoods_xml','groupgoods','get_grouplist_xml'))){
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
        $this->promotionOp();
    }
    /**
     * 促销分析
     */
    public function promotionOp(){
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
        $model = Model('stat');
        //获得搜索的开始时间和结束时间
        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
        $where = array();
        $where['order_isvalid'] = 1;//计入统计的有效订单
        $where['order_add_time'] = array('between',$searchtime_arr);
        $where['goods_type'] = array('in',array(2,3,4));
        //下单量
        $field = ' goods_type,count(DISTINCT order_id) as ordernum,SUM(goods_num) as goodsnum,SUM(goods_pay_price) as orderamount';
        $statlist_tmp = $model->statByStatordergoods($where, $field, 0, 0, 'goods_type', 'goods_type');
        //优惠类型数组
        $goodstype_arr = array(2=>'抢购',3=>'限时折扣',4=>'优惠套装');
        $statlist = array();
        $statcount = array('ordernum'=>0,'goodsnum'=>0,'orderamount'=>0.00);
        $stat_arr = array();
        $stat_json = array('ordernum'=>'','goodsnum'=>'','orderamount'=>'');
        if ($statlist_tmp){
            foreach((array)$statlist_tmp as $k => $v){
                $statcount['ordernum'] += intval($v['ordernum']);
                $statcount['goodsnum'] += intval($v['goodsnum']);
                $statcount['orderamount'] += floatval($v['orderamount']);
            }
            foreach((array)$statlist_tmp as $k => $v){
                $v['ordernumratio'] = round($v['ordernum']/$statcount['ordernum'],4)*100;
                $v['goodsnumratio'] = round($v['goodsnum']/$statcount['goodsnum'],4)*100;
                $v['orderamountratio'] = round($v['orderamount']/$statcount['orderamount'],4)*100;
                $statlist_tmp2[$v['goods_type']] = $v;
                $stat_arr['ordernum'][] = array('p_name'=>$goodstype_arr[$v['goods_type']],'allnum'=>$v['ordernumratio']);
                $stat_arr['goodsnum'][] = array('p_name'=>$goodstype_arr[$v['goods_type']],'allnum'=>$v['goodsnumratio']);
                $stat_arr['orderamount'][] = array('p_name'=>$goodstype_arr[$v['goods_type']],'allnum'=>$v['orderamountratio']);
            }
            foreach ($goodstype_arr as $k => $v){
                if ($statlist_tmp2[$k]){
                    $statlist_tmp2[$k]['goodstype_text'] = $v;
                    $statlist[] = $statlist_tmp2[$k];
                } else {
                    $statlist[] = array('goodstype_text'=>$k,'goodstype_text'=>$v,'ordernum'=>0,'goodsnum'=>0,'orderamount'=>0.00);
                }
            }
            $stat_json['ordernum'] = getStatData_Pie(array('title'=>'下单量','name'=>'下单量(%)','label_show'=>false,'series'=>$stat_arr['ordernum']));
            $stat_json['goodsnum'] = getStatData_Pie(array('title'=>'下单商品数','name'=>'下商品数(%)','label_show'=>false,'series'=>$stat_arr['goodsnum']));
            $stat_json['orderamount'] = getStatData_Pie(array('title'=>'下单金额','name'=>'下单金额(%)','label_show'=>false,'series'=>$stat_arr['orderamount']));
        }
        Tpl::output('statcount',$statcount);
        Tpl::output('statlist',$statlist);
        Tpl::output('stat_json',$stat_json);
        Tpl::output('searchtime',implode('|',$searchtime_arr));
        Tpl::output('top_link',$this->sublink($this->links, 'promotion'));
		Tpl::setDirquna('shop');
        Tpl::showpage('stat.marketing.promotion');
    }
    /**
     * 促销销售趋势分析
     */
    public function promotiontrendOp(){
        //优惠类型数组
        $goodstype_arr = array(2=>'抢购',3=>'限时折扣',4=>'优惠套装');

        $model = Model('stat');
        $where = array();
        $searchtime_arr_tmp = explode('|',$this->search_arr['t']);
        foreach ((array)$searchtime_arr_tmp as $k => $v){
            $searchtime_arr[] = intval($v);
        }
        $where['order_isvalid'] = 1;//计入统计的有效订单
        $where['order_add_time'] = array('between',$searchtime_arr);
        $where['goods_type'] = array('in',array(2,3,4));
        $field = ' goods_type';
        switch ($this->search_arr['stattype']){
            case 'orderamount':
                $field .= " ,SUM(goods_pay_price) as orderamount";
                $caption = '下单金额';
                break;
            case 'goodsnum':
                $field .= " ,SUM(goods_num) as goodsnum";
                $caption = '下单商品数';
                break;
            default:
                $field .= " ,count(DISTINCT order_id) as ordernum";
                $caption = '下单量';
                break;
        }
        if($this->search_arr['search_type'] == 'day'){
            //构造横轴数据
            for($i=0; $i<24; $i++){
                //横轴
                $stat_arr['xAxis']['categories'][] = "$i";
                foreach ($goodstype_arr as $k => $v){
                    $statlist[$k][$i] = 0;
                }
            }
            $field .= ' ,HOUR(FROM_UNIXTIME(order_add_time)) as timeval ';
            if (C('dbdriver') == 'oracle') $_group = 'HOUR(FROM_UNIXTIME(order_add_time))';
        }
        if($this->search_arr['search_type'] == 'week'){
            //构造横轴数据
            for($i=1; $i<=7; $i++){
                $tmp_weekarr = getSystemWeekArr();
                //横轴
                $stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
                unset($tmp_weekarr);
                foreach ($goodstype_arr as $k => $v){
                    $statlist[$k][$i] = 0;
                }
            }
            $field .= ' ,WEEKDAY(FROM_UNIXTIME(order_add_time))+1 as timeval ';
            if (C('dbdriver') == 'oracle') $_group = 'WEEKDAY(FROM_UNIXTIME(order_add_time))+1';
        }
        if($this->search_arr['search_type'] == 'month'){
            //计算横轴的最大量（由于每个月的天数不同）
            $dayofmonth = date('t',$searchtime_arr[0]);
            //构造横轴数据
            for($i=1; $i<=$dayofmonth; $i++){
                //横轴
                $stat_arr['xAxis']['categories'][] = $i;
                foreach ($goodstype_arr as $k => $v){
                    $statlist[$k][$i] = 0;
                }
            }
            $field .= ' ,day(FROM_UNIXTIME(order_add_time)) as timeval ';
            if (C('dbdriver') == 'oracle') $_group = 'day(FROM_UNIXTIME(order_add_time))';
        }
        //查询数据
        $statlist_tmp = $model->statByStatordergoods($where, $field, 0, 0, 'timeval','goods_type,'.($_group ? $_group : 'timeval'));
        //整理统计数组
        if($statlist_tmp){
            foreach($statlist_tmp as $k => $v){
                //将数据按照不同的促销方式分组
                foreach ($goodstype_arr as $t_k=>$t_v){
                    if ($t_k == $v['goods_type']){
                        switch ($this->search_arr['stattype']){
                            case 'orderamount':
                                $statlist[$t_k][$v['timeval']] = round($v[$this->search_arr['stattype']],2);
                                break;
                            case 'goodsnum':
                                $statlist[$t_k][$v['timeval']] = intval($v[$this->search_arr['stattype']]);
                                break;
                            default:
                                $statlist[$t_k][$v['timeval']] = intval($v[$this->search_arr['stattype']]);
                                break;
                        }
                    }
                }
            }
        }
        foreach ($goodstype_arr as $k => $v){
            $tmp = array();
            $tmp['name'] = $v;
            $tmp['data'] = array_values($statlist[$k]);
            $stat_arr['series'][] = $tmp;
        }
        //得到统计图数据
        $stat_arr['title'] = $caption.'统计';
        $stat_arr['yAxis'] = $caption;
        $stat_json = getStatData_LineLabels($stat_arr);
        Tpl::output('stat_json',$stat_json);
        Tpl::output('stattype',$_GET['stattype']);
		Tpl::setDirquna('shop');
        Tpl::showpage('stat.linelabels','null_layout');
    }

    /**
     * 抢购统计
     */
    public function groupOp(){
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
        $model = Model('stat');
        //获得搜索的开始时间和结束时间
        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
        Tpl::output('searchtime',implode('|',$searchtime_arr));
        Tpl::output('top_link',$this->sublink($this->links, 'group'));
		Tpl::setDirquna('shop');
        Tpl::showpage('stat.marketing.group');
    }
    /**
     * 输出抢购统计XML数据
     */
    public function get_grouplist_xmlOp(){
        $model = Model('groupbuy');
        $where = array();
        $where['is_vr'] = 0;//不统计虚拟抢购
        $model_stat = Model('stat');
        //获得搜索的开始时间和结束时间
        $searchtime_arr = $model_stat->getStarttimeAndEndtime($this->search_arr);
        $where['start_time'] = array('exp',"(end_time >= {$searchtime_arr[0]} and start_time <= {$searchtime_arr[1]})");
        $where['state'] = array('in',array(10,20,30));

        $keyword_type = array('groupbuy_name','goods_name');
        if (trim($_POST['query']) != '' && in_array($_POST['qtype'],$keyword_type)) {
            $type = $_POST['qtype'];
            $where[$type] = array('like','%'.$_POST['query'].'%');
        }
        $order_type = array('start_time','end_time','goods_price','groupbuy_price','views');
        $sort_type = array('asc','desc');
        $sortname = trim($this->search_arr['sortname']);
        if (!in_array($sortname,$order_type)){
            $sortname = 'start_time';
        }
        $sortorder = trim($this->search_arr['sortorder']);
        if (!in_array($sortorder,$sort_type)){
            $sortorder = 'asc';
        }
        $orderby = $sortname.' '.$sortorder;
        $page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }
        $grouplist_tmp = $model->getGroupbuyExtendList($where,$page,$orderby);
        $grouplist = array();
        if ($grouplist_tmp){
            foreach ((array)$grouplist_tmp as $k => $v){
                $v['goodsnum'] = 0;
                $v['ordernum'] = 0;
                $v['orderrate'] = round(0,2).'%';
                $v['goodsamount'] = ncPriceFormat(0);
                $grouplist[$v['groupbuy_id']] = $v;
            }
            //查询抢购的订单
            $where = array();
            $where['order_isvalid'] = 1;//计入统计的有效订单
            $where['goods_type'] = 2;//抢购
            $where['promotions_id'] = array('in',array_keys($grouplist));
            $field = 'promotions_id,SUM(goods_num) as goodsnum,COUNT(DISTINCT order_id) as ordernum,SUM(goods_pay_price) as goodsamount';
            $order_list = Model('stat')->statByStatordergoods($where, $field, 0, 0, '', 'promotions_id');
            foreach ((array)$order_list as $k => $v){
                $grouplist[$v['promotions_id']]['goodsnum'] = $v['goodsnum'];
                $grouplist[$v['promotions_id']]['ordernum'] = $v['ordernum'];
                if (intval($grouplist['views']) > 0){
                    $grouplist[$v['promotions_id']]['orderrate'] = round(($v['ordernum']/$grouplist[$v['promotions_id']]['views'])*100,2).'%';
                }
                $grouplist[$v['promotions_id']]['goodsamount'] = $v['goodsamount'];
            }
        }
        $statlist = array();
        if (!empty($grouplist) && is_array($grouplist)){
            $fields_array = array('groupbuy_name','start_time','end_time','groupbuy_state_text','goods_name','goods_price',
                'groupbuy_rebate','groupbuy_price','views','ordernum','goodsnum','goodsamount','orderrate');
            foreach ($grouplist as $k => $v){
                $out_array = getFlexigridArray(array(),$fields_array,$v);
                $out_array['groupbuy_name'] = '<a href="'.urlShop('show_groupbuy', 'groupbuy_detail', array('group_id' => $v['groupbuy_id'])).'" target="_blank">'.$v['groupbuy_name'].'</a>';
                $out_array['start_time'] = date('Y-m-d',$v['start_time']);
                $out_array['end_time'] = date('Y-m-d',$v['end_time']);
                $out_array['goods_name'] = '<a href="'.urlShop('goods', 'index', array('goods_id' => $v['goods_id'])).'" target="_blank">'.$v['goods_name'].'</a>';
                $out_array['goods_price'] = ncPriceFormat($v['goods_price']);
                $out_array['groupbuy_price'] = ncPriceFormat($v['groupbuy_price']);
                $out_array['goodsamount'] = ncPriceFormat($v['goodsamount']);
                $statlist[] = $out_array;
            }
        }
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        $data['list'] = $statlist;
        echo Tpl::flexigridXML($data);exit();
    }
    /**
     * 抢购商品统计
     */
    public function groupgoodsOp(){
        $model = Model('stat');
        $where = array();
        //获得搜索的开始时间和结束时间
        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
        $where['order_add_time'] = array('between',$searchtime_arr);
        $where['goods_type'] = 2;//抢购
        $field = " goods_id,min(goods_name) as goods_name";
        $field .= " ,SUM(goods_num) as goodsnum";
        $field .= " ,SUM(goods_pay_price) as goodsamount";
        if (C('dbdriver') == 'mysql') {
            $field .= " ,SUM(IF(order_state='".ORDER_STATE_CANCEL."',goods_num,0)) as cancelgoodsnum";
            $field .= " ,SUM(IF(order_state='".ORDER_STATE_CANCEL."',goods_pay_price,0)) as cancelgoodsamount";
            $field .= " ,SUM(IF(order_state<>'".ORDER_STATE_CANCEL."' and order_state<>'".ORDER_STATE_NEW."',goods_num,0)) as finishgoodsnum";
            $field .= " ,SUM(IF(order_state<>'".ORDER_STATE_CANCEL."' and order_state<>'".ORDER_STATE_NEW."',goods_pay_price,0)) as finishgoodsamount";
        } elseif (C('dbdriver') == 'oracle') {
            $field .= " ,SUM((case when order_state='".ORDER_STATE_CANCEL."' then goods_num else 0 end)) as cancelgoodsnum";
            $field .= " ,SUM((case when order_state='".ORDER_STATE_CANCEL."' then goods_pay_price else 0 end)) as cancelgoodsamount";
            $field .= " ,SUM((case when order_state<>'".ORDER_STATE_CANCEL."' and order_state<>'".ORDER_STATE_NEW."' then goods_num else 0 end)) as finishgoodsnum";
            $field .= " ,SUM((case when order_state<>'".ORDER_STATE_CANCEL."' and order_state<>'".ORDER_STATE_NEW."' then goods_pay_price else 0 end)) as finishgoodsamount";
        }
        if (!empty($_GET['id']) && is_array($_GET['id'])){
            $where['goods_id'] = array('in',$_GET['id']);
        }
        $orderby = 'goodsnum desc,goods_id desc';

        if ($this->search_arr['exporttype'] == 'excel'){
            $statlist_tmp = $model->statByStatordergoods($where, $field, 0, 0, $orderby, 'goods_id');
        }
        $statheader = array();
        $statheader[] = array('text'=>'商品名称','key'=>'goods_name','class'=>'alignleft');
        $statheader[] = array('text'=>'下单商品数','key'=>'goodsnum','isorder'=>1);
        $statheader[] = array('text'=>'下单金额','key'=>'goodsamount','isorder'=>1);
        $statheader[] = array('text'=>'取消商品数','key'=>'cancelgoodsnum','isorder'=>1);
        $statheader[] = array('text'=>'取消金额','key'=>'cancelgoodsamount','isorder'=>1);
        $statheader[] = array('text'=>'完成商品数','key'=>'finishgoodsnum','isorder'=>1);
        $statheader[] = array('text'=>'完成金额','key'=>'finishgoodsamount','isorder'=>1);
        foreach ((array)$statlist_tmp as $k => $v){
            $tmp = $v;
            foreach ($statheader as $h_k=>$h_v){
                $tmp[$h_v['key']] = $v[$h_v['key']];
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
            foreach ($statheader as $k => $v){
                $excel_data[0][] = array('styleid'=>'s_title','data'=>$v['text']);
            }
            //data
            foreach ((array)$statlist as $k => $v){
                foreach ($statheader as $h_k=>$h_v){
                    $excel_data[$k+1][] = array('data'=>$v[$h_v['key']]);
                }
            }
            $excel_data = $excel_obj->charset($excel_data,CHARSET);
            $excel_obj->addArray($excel_data);
            $excel_obj->addWorksheet($excel_obj->charset('抢购商品统计',CHARSET));
            $excel_obj->generateXML($excel_obj->charset('抢购商品统计',CHARSET).date('Y-m-d-H',time()));
            exit();
        }
    }
    /**
     * 输出抢购商品统计XML数据
     */
    public function get_groupgoods_xmlOp(){
        $model = Model('stat');
        $where = array();
        //获得搜索的开始时间和结束时间
        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
        $where['order_add_time'] = array('between',$searchtime_arr);
        $where['goods_type'] = 2;//抢购
        $field = " goods_id,min(goods_name) as goods_name";
        $field .= " ,SUM(goods_num) as goodsnum";
        $field .= " ,SUM(goods_pay_price) as goodsamount";
        if (C('dbdriver') == 'mysql') {
            $field .= " ,SUM(IF(order_state='".ORDER_STATE_CANCEL."',goods_num,0)) as cancelgoodsnum";
            $field .= " ,SUM(IF(order_state='".ORDER_STATE_CANCEL."',goods_pay_price,0)) as cancelgoodsamount";
            $field .= " ,SUM(IF(order_state<>'".ORDER_STATE_CANCEL."' and order_state<>'".ORDER_STATE_NEW."',goods_num,0)) as finishgoodsnum";
            $field .= " ,SUM(IF(order_state<>'".ORDER_STATE_CANCEL."' and order_state<>'".ORDER_STATE_NEW."',goods_pay_price,0)) as finishgoodsamount";
        } elseif (C('dbdriver') == 'oracle') {
            $field .= " ,SUM((case when order_state='".ORDER_STATE_CANCEL."' then goods_num else 0 end)) as cancelgoodsnum";
            $field .= " ,SUM((case when order_state='".ORDER_STATE_CANCEL."' then goods_pay_price else 0 end)) as cancelgoodsamount";
            $field .= " ,SUM((case when order_state<>'".ORDER_STATE_CANCEL."' and order_state<>'".ORDER_STATE_NEW."' then goods_num else 0 end)) as finishgoodsnum";
            $field .= " ,SUM((case when order_state<>'".ORDER_STATE_CANCEL."' and order_state<>'".ORDER_STATE_NEW."' then goods_pay_price else 0 end)) as finishgoodsamount";
        }

        $order_type = array('goods_name','goodsnum','goodsamount','cancelgoodsnum','cancelgoodsamount','finishgoodsnum','finishgoodsamount');
        $sort_type = array('asc','desc');
        $sortname = trim($this->search_arr['sortname']);
        if (!in_array($sortname,$order_type)){
            $sortname = 'goodsnum';
        }
        $sortorder = trim($this->search_arr['sortorder']);
        if (!in_array($sortorder,$sort_type)){
            $sortorder = 'desc';
        }
        $orderby = $sortname.' '.$sortorder.',goods_id desc';
        $page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }
        //统计记录总条数
        $count_arr = $model->getoneByStatordergoods($where, 'count(DISTINCT goods_id) as countnum');
        $countnum = intval($count_arr['countnum']);
        $list = $model->statByStatordergoods($where, $field, array($page,$countnum), 0, $orderby, 'goods_id');

        $statlist = array();
        if (!empty($list) && is_array($list)){
            $format_array = array('goodsamount','cancelgoodsamount','finishgoodsamount');
            foreach ($list as $k => $v){
                $out_array = getFlexigridArray(array(),$order_type,$v,$format_array);
                $out_array['goods_name'] = '<a href="'.urlShop('goods', 'index', array('goods_id' => $v['goods_id'])).'" target="_blank">'.$v['goods_name'].'</a>';
                $statlist[$v['goods_id']] = $out_array;
            }
        }
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $countnum;
        $data['list'] = $statlist;
        echo Tpl::flexigridXML($data);exit();
    }
}
