<?php
/**
 * 会员中心——积分兑换信息
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_pointorderControl extends BaseMemberControl{
    public function __construct() {
        parent::__construct();
        //读取语言包
        Language::read('member_member_points,member_pointorder');
        //判断系统是否开启积分和积分兑换功能
        if (C('points_isuse') != 1 || C('pointprod_isuse') != 1){
            showDialog(L('member_pointorder_unavailable'),urlShop('member', 'home'),'error');
        }
        Tpl::output('act', 'member_points');
    }
    public function indexOp() {
        $this->orderlistOp();
    }
    /**
     * 兑换信息列表
     */
    public function orderlistOp() {
        //兑换信息列表
        $where = array();
        $where['point_buyerid'] = $_SESSION['member_id'];

        $model_pointorder = Model('pointorder');
        $order_list = $model_pointorder->getPointOrderList($where, '*', 10, 0, 'point_orderid desc');
        $order_idarr = array();
        $order_listnew = array();
        if (is_array($order_list) && count($order_list)>0){
            foreach ($order_list as $k => $v){
                $order_listnew[$v['point_orderid']] = $v;
                $order_idarr[] = $v['point_orderid'];
            }
        }

        //查询兑换商品
        if (is_array($order_idarr) && count($order_idarr)>0){
            $prod_list = $model_pointorder->getPointOrderGoodsList(array('point_orderid'=>array('in',$order_idarr)));
            if (is_array($prod_list) && count($prod_list)>0){
                foreach ($prod_list as $v){
                    if (isset($order_listnew[$v['point_orderid']])){
                        $order_listnew[$v['point_orderid']]['prodlist'][] = $v;
                    }
                }
            }
        }

        //信息输出
        Tpl::output('order_list',$order_listnew);
        Tpl::output('page',$model_pointorder->showpage(2));
        self::profile_menu('pointorder','orderlist');
        Tpl::showpage('member_pointorder');
    }
    /**
     *  取消兑换
     */
    public function cancel_orderOp(){
        $model_pointorder = Model('pointorder');
        //取消订单
        $data = $model_pointorder->cancelPointOrder($_GET['order_id'],$_SESSION['member_id']);
        if ($data['state']){
            showDialog(L('member_pointorder_cancel_success'),'index.php?act=member_pointorder','succ');
        }else {
            showDialog($data['msg'],'index.php?act=member_pointorder','error');
        }
    }
    /**
     * 确认收货
     */
    public function receiving_orderOp(){
        $data = Model('pointorder')->receivingPointOrder($_GET['order_id']);
        if ($data['state']){
            showDialog(L('member_pointorder_confirmreceiving_success'),'index.php?act=member_pointorder','succ');
        }else {
            showDialog($data['msg'],'index.php?act=member_pointorder','error');
        }
    }
    /**
     * 从第三方取快递信息
     *
     */
    public function get_expressOp(){
    
        $content = Model('express')->get_express($_GET['e_code'], $_GET['shipping_code']);
    
        $output = array();
        foreach ($content as $k=>$v) {
            if ($v['time'] == '') continue;
            $output[]= $v['time'].'&nbsp;&nbsp;'.$v['context'];
        }
        if (empty($output)) exit(json_encode(false));
    
        echo json_encode($output);
    }
    /**
     * 兑换信息详细
     */
    public function order_infoOp(){
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0){
            showDialog(L('member_pointorder_parameter_error'),'index.php?act=member_pointorder','error');
        }
        $model_pointorder = Model('pointorder');
        //查询兑换订单信息
        $where = array();
        $where['point_orderid'] = $order_id;
        $where['point_buyerid'] = $_SESSION['member_id'];
        $order_info = $model_pointorder->getPointOrderInfo($where);
        if (!$order_info){
            showDialog(L('member_pointorder_record_error'),'index.php?act=member_pointorder','error');
        }
        //获取订单状态
        $pointorderstate_arr = $model_pointorder->getPointOrderStateBySign();
        Tpl::output('pointorderstate_arr',$pointorderstate_arr);

        //查询兑换订单收货人地址
        $orderaddress_info = $model_pointorder->getPointOrderAddressInfo(array('point_orderid'=>$order_id));
        Tpl::output('orderaddress_info',$orderaddress_info);

        //兑换商品信息
        $prod_list = $model_pointorder->getPointOrderGoodsList(array('point_orderid'=>$order_id));
        Tpl::output('prod_list',$prod_list);

        //物流公司信息
        if ($order_info['point_shipping_ecode'] != ''){
            $data = Model('express')->getExpressInfoByECode($order_info['point_shipping_ecode']);
            if ($data['state']){
                $express_info = $data['data']['express_info'];
            }
            Tpl::output('express_info',$express_info);
        }

        Tpl::output('order_info',$order_info);
        Tpl::output('left_show','order_view');
        Tpl::showpage('member_pointorder_info');
    }
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array = array();
        switch ($menu_type) {
            case 'pointorder':
                $menu_array = array(
                    1=>array('menu_key'=>'points',  'menu_name'=>'积分明细',    'menu_url'=>'index.php?act=member_points'),
                    2=>array('menu_key'=>'orderlist','menu_name'=>Language::get('member_pointorder_list_title'),    'menu_url'=>'index.php?act=member_pointorder&op=orderlist')
                );
                break;
            case 'pointorderinfo':
                $menu_array = array(
                    1=>array('menu_key'=>'points',  'menu_name'=>'积分明细',    'menu_url'=>'index.php?act=member_points'),
                    2=>array('menu_key'=>'orderlist','menu_name'=>Language::get('nc_member_path_pointorder_list'),  'menu_url'=>'index.php?act=member_pointorder&op=orderlist'),
                    3=>array('menu_key'=>'orderinfo','menu_name'=>Language::get('nc_member_path_pointorder_info'),  '')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
