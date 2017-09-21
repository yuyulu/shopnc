<?php
/**
 * 商家销售统计
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class seller_statControl extends mobileSellerControl {

    private $search_arr;//处理后的参数

    public function __construct(){
        parent::__construct();
        import('function.datehelper');

        $model_stat = Model('stat');
        $this->search_arr = $_POST;
        //$this->search_arr = $_GET;
        //处理搜索时间
        if ($this->search_arr['stattype'] == 'yesterday') {
            $this->search_arr['search_type'] = 'day';
            $this->search_arr['show_type'] = 'hour';
            $this->search_arr['day']['search_time'] = date('Y-m-d', time()- 86400);
            $this->search_arr['day']['search_time'] = strtotime($this->search_arr['day']['search_time']);
        }elseif($this->search_arr['stattype'] == 'day3'){
            $this->search_arr['search_type'] = 'day3';
            $this->search_arr['show_type'] = 'day';
            $this->search_arr['day']['search_time'] = date('Y-m-d', time()- 86400);
            $this->search_arr['day']['search_time'] = strtotime($this->search_arr['day']['search_time']);
        }elseif($this->search_arr['stattype'] == 'day7'){
            $this->search_arr['search_type'] = 'day7';
            $this->search_arr['show_type'] = 'day';
            $this->search_arr['day']['search_time'] = date('Y-m-d', time()- 86400);
            $this->search_arr['day']['search_time'] = strtotime($this->search_arr['day']['search_time']);
        } elseif ($this->search_arr['stattype'] == 'week') {
            $this->search_arr['search_type'] = 'week';
            $this->search_arr['show_type'] = 'week';
            $searchweek_weekarr = getWeek_SdateAndEdate(time());
            $this->search_arr['week']['current_week'] = implode('|', $searchweek_weekarr);
        } elseif ($this->search_arr['stattype'] == 'month') {
            $this->search_arr['search_type'] = 'month';
            $this->search_arr['show_type'] = 'day';
            $this->search_arr['month']['current_year']= date('Y', time());
            $this->search_arr['month']['current_month']= date('m', time());
        } elseif ($this->search_arr['stattype'] == 'year') {
            $this->search_arr['search_type'] = 'year';
            $this->search_arr['show_type'] = 'month';
            $this->search_arr['year']['current_year']= date('Y', time());
        }

        $searchtime_arr = $model_stat->getStarttimeAndEndtime($this->search_arr);
        $this->search_arr['stime'] = $searchtime_arr[0];
        $this->search_arr['etime'] = $searchtime_arr[1];
    }

    public function ordersamountOp() {
        $this->ordersstat('ordersamount');
    }
    public function ordersnumOp() {
        $this->ordersstat('ordersnum');
    }
    /**
     * 统计订单下单金额及下单量
     * @param $data_type 统计数据类型'ordersamount'为下单金额'ordersnum'为下单量
     */
    private function ordersstat($data_type) {
        $model_stat = Model('stat');
        $where = array();
        $where['store_id'] = $this->store_info['store_id'];
        $where['order_add_time'] = array('between',array($this->search_arr['stime'],$this->search_arr['etime']));
        $where['order_isvalid'] = 1;//计入统计的有效订单
        $field = ' COUNT(*) as ordersnum,SUM(order_amount) as ordersamount ';
        if ($this->search_arr['show_type'] == 'hour') {
            //构造横轴数据
            for($i=0; $i<24; $i++){
                //横轴坐标
                $x_data[] = $i;
                //统计表数据
                $y_data[$i] = 0;
            }
            $field .= ' ,HOUR(FROM_UNIXTIME(order_add_time)) as hourval ';
            if (C('dbdriver') == 'mysql') {
                $_group = 'hourval';
            } else {
                $_group = 'HOUR(FROM_UNIXTIME(order_add_time))';
            }
            $orderlist = $model_stat->statByStatorder($where, $field, 0, 0, '', $_group);
            foreach((array)$orderlist as $k => $v){
                if ($data_type == 'ordersnum') {//下单量
                    $y_data[$v['hourval']] = intval($v['ordersnum']);
                }
                if ($data_type == 'ordersamount') {//下单金额
                    $y_data[$v['hourval']] = floatval($v['ordersamount']);
                }
            }
        }
        if ($this->search_arr['show_type'] == 'day') {
            //构造横轴数据
            for($i=$this->search_arr['stime']; $i<$this->search_arr['etime']; $i=$i+86400){
                //横轴坐标
                $x_data[] = date('d',$i);
                //统计表数据
                $y_data[date('j',$i)] = 0;
            }
            $field .= ',day(FROM_UNIXTIME(order_add_time)) as dayval ';
            if (C('dbdriver') == 'mysqli') {
                $_group = 'dayval';
            } else {
                $_group = 'DAY(FROM_UNIXTIME(order_add_time))';
            }
            $orderlist = $model_stat->statByStatorder($where, $field, 0, 0, '', $_group);
            foreach($orderlist as $k=>$v){
                if ($data_type == 'ordersnum') {//下单量
                    $y_data[$v['dayval']] = intval($v['ordersnum']);
                }
                if ($data_type == 'ordersamount') {//下单金额
                    $y_data[$v['dayval']] = floatval($v['ordersamount']);
                }
            }
        }
        if ($this->search_arr['show_type'] == 'week') {
            //构造横轴数据
            for($i=1; $i<=7; $i++){
                $tmp_weekarr = getSystemWeekArr();
                //横轴坐标
                $x_data[] = $tmp_weekarr[$i];
                //统计表数据
                $y_data[$i] = 0;
                unset($tmp_weekarr);
            }
            $field .= ',WEEKDAY(FROM_UNIXTIME(order_add_time))+1 as dayofweekval ';
            if (C('dbdriver') == 'mysqli') {
                $_group = 'dayofweekval';
            } else {
                $_group = 'WEEKDAY(FROM_UNIXTIME(order_add_time))+1';
            }
            $orderlist = $model_stat->statByStatorder($where, $field, 0, 0, '', $_group);
            foreach((array)$orderlist as $k=>$v){
                if ($data_type == 'ordersnum') {//下单量
                    $y_data[$v['dayofweekval']] = intval($v['ordersnum']);
                }
                if ($data_type == 'ordersamount') {//下单金额
                    $y_data[$v['dayofweekval']] = floatval($v['ordersamount']);
                }
            }
        }
        if ($this->search_arr['show_type'] == 'month') {
            //构造横轴数据
            for($i=1; $i<=12; $i++){
                //横轴坐标
                $x_data[] = $i;
                //统计表数据
                $y_data[$i] = 0;
                unset($tmp_weekarr);
            }
            $field .= ',MONTH(FROM_UNIXTIME(order_add_time)) as monthval ';
            if (C('dbdriver') == 'mysqli') {
                $_group = 'monthval';
            } else {
                $_group = 'MONTH(FROM_UNIXTIME(order_add_time))';
            }
            $orderlist = $model_stat->statByStatorder($where, $field, 0, 0, '', $_group);
            foreach((array)$orderlist as $k=>$v){
                if ($data_type == 'ordersnum') {//下单量
                    $y_data[$v['monthval']] = intval($v['ordersnum']);
                }
                if ($data_type == 'ordersamount') {//下单金额
                    $y_data[$v['monthval']] = floatval($v['ordersamount']);
                }
            }
        }
        $y_data = array_values($y_data);
        output_data(array('x'=>$x_data, 'y'=>$y_data));
    }

    /**
     * 客单价统计
     */
    public function perguestpriceOp(){
        $model_stat = Model('stat');
        $where = array();
        $where['store_id'] = $this->store_info['store_id'];
        $where['order_isvalid'] = 1;//计入统计的有效订单
        $where['order_add_time'] = array('between',array($this->search_arr['stime'],$this->search_arr['etime']));
        $field = '1';
        //查询店铺价格区间
        $pricerange = Model('store_extend')->getfby_store_id($this->store_info['store_id'],'orderpricerange');
        $pricerange_arr = $pricerange?unserialize($pricerange):array();
        $x_data = $y_data = array();
        if ($pricerange_arr){
            //设置价格区间最后一项，最后一项只有开始值没有结束值
            $pricerange_count = count($pricerange_arr);
            if ($pricerange_arr[$pricerange_count-1]['e']){
                $pricerange_arr[$pricerange_count]['s'] = $pricerange_arr[$pricerange_count-1]['e'] + 1;
                $pricerange_arr[$pricerange_count]['e'] = '';
            }
            foreach ((array)$pricerange_arr as $k=>$v){
                $v['s'] = intval($v['s']);
                $v['e'] = intval($v['e']);
                //构造查询字段
                if (C('dbdriver') == 'mysqli') {
                    if ($v['e']){
                        $field .= " ,SUM(IF(order_amount > {$v['s']} and order_amount <= {$v['e']},1,0)) as ordernum_{$k}";
                    } else {
                        $field .= " ,SUM(IF(order_amount > {$v['s']},1,0)) as ordernum_{$k}";
                    }
                } elseif (C('dbdriver') == 'oracle') {
                    if ($v['e']){
                        $field .= " ,SUM((case when order_amount > {$v['s']} and order_amount <= {$v['e']} then 1 else 0 end)) as ordernum_{$k}";
                    } else {
                        $field .= " ,SUM((case when order_amount > {$v['s']} then 1 else 0 end)) as ordernum_{$k}";
                    }
                }
            }
            $orderlist = $model_stat->getoneByStatorder($where, $field);
            if($orderlist){
                foreach ((array)$pricerange_arr as $k=>$v){
                    //横轴坐标
                    if ($v['e']){
                        $x_data[] = $v['s'].'-'.$v['e'];
                    } else {
                        $x_data[] = $v['s'].'以上';
                    }
                    //统计图数据
                    if ($orderlist['ordernum_'.$k]){
                        $y_data[] = intval($orderlist['ordernum_'.$k]);
                    } else {
                        $y_data[] = 0;
                    }
                }
            }
        }
        output_data(array('x'=>$x_data, 'y'=>$y_data));
    }

    public function goodsamountOp() {
        $this->goodssales('goodsamount');
    }
    public function goodsnumOp() {
        $this->goodssales('goodsnum');
    }
    /**
     * 商品下单金额TOP10、下单数量TOP10
     */
    public function goodssales($data_type){
        $topnum = 10;
        $model_stat = Model('stat');
        $where = array();
        $where['store_id'] = $this->store_info['store_id'];
        $where['order_isvalid'] = 1;//计入统计的有效订单
        $where['order_add_time'] = array('between',array($this->search_arr['stime'],$this->search_arr['etime']));
        //构造横轴数据
        for($i=1; $i<=$topnum; $i++){
            //横轴
            $x_data[] = "$i";
            //数据
            $y_data[] = array('name'=>'','y'=>0);
        }
        $field = ' goods_id,min(goods_name) as goods_name,SUM(goods_num) as goodsnum,SUM(goods_pay_price) as goodsamount ';
        $orderby = "$data_type desc,goods_id";
        $statlist = $model_stat->statByStatordergoods($where, $field, 0, $topnum, $orderby, 'goods_id');
        foreach ((array)$statlist as $k=>$v){
            $y_data[$k] = array('name'=>strval($v['goods_name']),'y'=>intval($v[$data_type]));
        }
        output_data(array('x'=>$x_data, 'y'=>$y_data));
    }
    /**
     * 商品流量TOP10
     */
    public function goodsflowOp(){
        $topnum = 10;
        //确定统计分表名称
        $last_num = $this->store_info['store_id'] % 10; //获取店铺ID的末位数字
        $tablenum = ($t = intval(C('flowstat_tablenum'))) > 1 ? $t : 1; //处理流量统计记录表数量
        $flow_tablename = ($t = ($last_num % $tablenum)) > 0 ? "flowstat_$t" : 'flowstat';

        $model_stat = Model('stat');
        $where = array();
        $where['store_id'] = $this->store_info['store_id'];
        $where['stattime'] = array('between',array($this->search_arr['stime'],$this->search_arr['etime']));
        $where['type'] = 'goods';

        $field = ' goods_id,SUM(clicknum) as allnum';
        //构造横轴数据
        for($i=1; $i<=$topnum; $i++){
            //横轴
            $x_data[] = $i;
            $y_data[] = array('name'=>'','y'=>0);
        }
        $statlist_tmp = $model_stat->statByFlowstat($flow_tablename, $where, $field, 0, $topnum, 'allnum desc,goods_id asc', 'goods_id');
        if ($statlist_tmp){
            $goodsid_arr = array();
            foreach((array)$statlist_tmp as $k=>$v){
                $goodsid_arr[] = $v['goods_id'];
            }
            //查询相应商品
            $goods_list_tmp = $model_stat->statByGoods(array('goods_id'=>array('in',$goodsid_arr)), 'goods_name,goods_id');
            foreach ((array)$goods_list_tmp as $k=>$v){
                $goods_list[$v['goods_id']] = $v;
            }
            foreach((array)$statlist_tmp as $k=>$v){
                $v['goods_name'] = $goods_list[$v['goods_id']];
                $v['allnum'] = floatval($v['allnum']);
                $y_data[$k] = array('name'=>strval($goods_list[$v['goods_id']]['goods_name']),'y'=>floatval($v['allnum']));
            }
        }
        output_data(array('x'=>$x_data, 'y'=>$y_data));
    }

    public function areasalesamountOp() {
        $this->areasales('ordersamount');
    }
    public function areasalesnumOp() {
        $this->areasales('ordersnum');
    }
    public function areamembernumOp() {
        $this->areasales('membernum');
    }
    /**
     * 订单区域下单金额TOP10、下单数量TOP10
     */
    public function areasales($data_type){
        $topnum = 10;
        $model_stat = Model('stat');
        $where = array();
        $where['store_id'] = $this->store_info['store_id'];
        $where['order_isvalid'] = 1;//计入统计的有效订单
        $where['order_add_time'] = array('between',array($this->search_arr['stime'],$this->search_arr['etime']));
        //查询统计数据
        $field = ' reciver_province_id,SUM(order_amount) as ordersamount,COUNT(*) as ordersnum,COUNT(DISTINCT buyer_id) as membernum ';
        $orderby = "$data_type desc,reciver_province_id asc";
        $statlist = $model_stat->statByStatorder($where, $field, $topnum, 0, $orderby, 'reciver_province_id');
        //地区
        $province_array = Model('area')->getTopLevelAreas();
        $x_data = $y_data = array();
        //构造横轴数据
        for($i=1; $i<=$topnum; $i++){
            //横轴
            $x_data[] = $i;
            $y_data[] = array('name'=>'','y'=>0);
        }
        foreach ((array)$statlist as $k=>$v){
            $province_id = intval($v['reciver_province_id']);
            if ($province_id){
                //数据
                $y_data[] = array('name'=>strval($province_array[$province_id]),'y'=>$v[$data_type]);
                //横轴
                //$x_data[] = strval($province_array[$province_id]);
            } else {
                //数据
                $y_data[] = array('name'=>'未知','y'=>$v[$data_type]);
                //横轴
                //$x_data[] = '未知';
            }
        }
        output_data(array('x'=>$x_data, 'y'=>$y_data));
    }
}
