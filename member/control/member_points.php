<?php
/**
 * 积分管理
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class member_pointsControl extends BaseMemberControl {
    public function indexOp(){
        $this->points_logOp();
        exit;
    }
    public function __construct() {
        parent::__construct();
        /**
         * 读取语言包
         */
        Language::read('member_member_points,member_pointorder');
        /**
         * 判断系统是否开启积分功能
         */
        if (C('points_isuse') != 1){
            showMessage(Language::get('points_unavailable'),urlShop('member', 'home'),'html','error');
        }
    }
    /**
     * 积分日志列表
     */
    public function points_logOp(){
        $where = array();
        $where['pl_memberid'] = $_SESSION['member_id'];
        if ($_GET['stage']){
            $where['pl_stage'] = $_GET['stage'];
        }
        if (trim($_GET['stime']) && trim($_GET['etime'])) {
            $stime = strtotime($_GET['stime']);
            $etime = strtotime($_GET['etime']);
            $where['pl_addtime'] = array('between', "$stime,$etime");
        } elseif (trim($_GET['stime'])) {
            $stime = strtotime($_GET['stime']);
            $where['pl_addtime'] = array('egt', $stime);
        } elseif (trim($_GET['etime'])) {
            $etime = strtotime($_GET['etime']);
            $where['pl_addtime'] = array('elt', $etime);
        }
        $where['pl_desc'] = array('like',"%{$_GET['description']}%");
        //查询积分日志列表
        $points_model = Model('points');
        $list_log = $points_model->getPointsLogList($where, '*', 0, 10);
        //信息输出
        self::profile_menu('points');
        Tpl::output('show_page',$points_model->showpage());
        Tpl::output('list_log',$list_log);
        Tpl::showpage('member_points');
    }
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_key='',$array=array()) {
        $menu_array = array(
            1=>array('menu_key'=>'points',  'menu_name'=>'积分明细',    'menu_url'=>'index.php?act=member_points'),
            2=>array('menu_key'=>'orderlist','menu_name'=>'积分兑换',    'menu_url'=>'index.php?act=member_pointorder&op=orderlist')
        );
        if(!empty($array)) {
            $menu_array[] = $array;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
