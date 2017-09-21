<?php
/**
 * 商家注销
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class seller_storeControl extends mobileSellerControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 店铺信息
     */
    public function store_infoOp() {
        $this->store_info['store_banner'] = getStoreLogo($this->store_info['store_banner'], 'store_logo');
        output_data(array('store_info' => $this->store_info));
    }
    
    /**
     * 编辑店铺
     */
    public function store_editOp() {
        $upload = new UploadFile();
        /**
         * 上传店铺图片
        */
        if (!empty($_FILES['store_banner']['name'])){
            $upload->set('default_dir', ATTACH_STORE);
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','');
            $upload->set('ifremove',false);
            $result = $upload->upfile('store_banner');
            if ($result){
                $_POST['store_banner'] = $upload->file_name;
            }else {
                showDialog($upload->error);
            }
        }
        
        //删除旧店铺图片
        if (!empty($_POST['store_banner']) && !empty($store_info['store_banner'])){
            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.$this->store_info['store_banner']);
        }
        /**
         * 更新入库
         */
        $param = array(
            'store_banner' => empty($_POST['store_banner']) ? $this->store_info['store_banner'] : $_POST['store_banner'],
            'store_qq' => $_POST['store_qq'],
            'store_ww' => $_POST['store_ww'],
            'store_phone' => $_POST['store_phone'],
            'store_zy' => $_POST['store_zy'],
            'store_keywords' => $_POST['seo_keywords'],
            'store_description' => $_POST['seo_description']
        );

        $result = Model('store')->editStore($param, array('store_id' => $this->store_info['store_id']));
        if(!$result) {
            output_error('编辑失败');
        }
        output_data('1');
    }

    /**
     * 店铺信息统计
     */
    public function store_statisticsOp() {
        $model_stat = Model('stat');
        $start_time = strtotime(date('Y-m',time()));        // 当月开始
        // 月销量 月订单
        $condition = array();
        $condition['order_add_time'] = array('gt',$start_time);
        $monthly_sales = $model_stat->getoneByStatorder($condition, 'COUNT(*) as ordernum,SUM(order_amount) as orderamount ');
        
        // 月访问量
        //确定统计分表名称
        $last_num = $this->store_info['store_id'] % 10; //获取店铺ID的末位数字
        $tablenum = ($t = intval(C('flowstat_tablenum'))) > 1 ? $t : 1; //处理流量统计记录表数量
        $flow_tablename = ($t = ($last_num % $tablenum)) > 0 ? "flowstat_$t" : 'flowstat';
        $condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        $condition['stattime'] = array('gt', $start_time);
        $condition['type'] = 'sum';
        $statlist_tmp = $model_stat->getoneByFlowstat($flow_tablename, $condition, 'SUM(clicknum) as amount');
        
        $output = array(
            'store_name'    => $this->store_info['store_name'],
            'store_banner'  => getStoreLogo($this->store_info['store_banner'], 'store_logo'),
            'order_amount'  => $monthly_sales['orderamount'],
            'order_num'     => $monthly_sales['ordernum'],
            'click_amount'  => $statlist_tmp['amount']
        );

        output_data(array('statistics' => $output));
    }
}
