<?php
/**
 * 会员中心——买家评价
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class member_evaluateControl extends BaseMemberControl{
    public function __construct(){
        parent::__construct() ;
        Language::read('member_layout,member_evaluate');
        Tpl::output('pj_act','member_evaluate');
    }

    /**
     * 订单添加评价
     */
    public function addOp(){
        $order_id = intval($_GET['order_id']);
        $return = Logic('member_evaluate')->validation($order_id, $_SESSION['member_id']);
        if (!$return['state']) {
            showMessage($return['msg'],'index.php?act=member_order','html','error');
        }
        extract($return['data']);
        //判断是否提交
        if (chksubmit()){
            $return = Logic('member_evaluate')->save($_POST, $order_info, $store_info, $order_goods, $this->member_info['member_id'], $this->member_info['member_name']);
            if (!$return['state']) {
                showDialog($return['msg'],'reload','error');
            } else {
                showDialog(Language::get('member_evaluation_evaluat_success'),'index.php?act=member_order', 'succ');
            }
        } else {
            //处理积分、经验值计算说明文字
            $ruleexplain = '';
            $exppoints_rule = C("exppoints_rule")?unserialize(C("exppoints_rule")):array();
            $exppoints_rule['exp_comments'] = intval($exppoints_rule['exp_comments']);
            $points_comments = intval(C('points_comments'));
            if ($exppoints_rule['exp_comments'] > 0 || $points_comments > 0){
                $ruleexplain .= '评价完成将获得';
                if ($exppoints_rule['exp_comments'] > 0){
                    $ruleexplain .= (' “'.$exppoints_rule['exp_comments'].'经验值”');
                }
                if ($points_comments > 0){
                    $ruleexplain .= (' “'.$points_comments.'积分”');
                }
                $ruleexplain .= '。';
            }
            Tpl::output('ruleexplain', $ruleexplain);
    
            $model_sns_alumb = Model('sns_album');
            $ac_id = $model_sns_alumb->getSnsAlbumClassDefault($_SESSION['member_id']);
            Tpl::output('ac_id', $ac_id);
            
            //不显示左菜单
            Tpl::output('left_show','order_view');
            Tpl::output('order_info',$order_info);
            Tpl::output('order_goods',$order_goods);
            Tpl::output('store_info',$store_info);
            Tpl::showpage('evaluation.add');
        }
    }

    /**
     * 订单添加评价
     */
    public function add_againOp(){
        $order_id = intval($_GET['order_id']);
        $return = Logic('member_evaluate')->validationAgain($order_id, $_SESSION['member_id']);
        if (!$return['state']) {
            showMessage($return['msg'],'index.php?act=member_order','html','error');
        }
        extract($return['data']);
    
        //判断是否提交
        if (chksubmit()){
            $return = Logic('member_evaluate')->saveAgain($_POST, $order_info, $evaluate_goods);
            if (!$return['state']) {
                showDialog($return['msg'],'reload','error');
            } else {
                showDialog(Language::get('member_evaluation_evaluat_success'),'index.php?act=member_order', 'succ');
            }
        } else {
            $model_sns_alumb = Model('sns_album');
            $ac_id = $model_sns_alumb->getSnsAlbumClassDefault($_SESSION['member_id']);
            Tpl::output('ac_id', $ac_id);
        
            //不显示左菜单
            Tpl::output('left_show','order_view');
            Tpl::output('order_info',$order_info);
            Tpl::output('evaluate_goods',$evaluate_goods);
            Tpl::output('store_info',$store_info);
            Tpl::showpage('evaluation.add_again');
        }
    }

    /**
     * 虚拟商品评价
     */
    public function add_vrOp(){
        $order_id = intval($_GET['order_id']);
        $return = Logic('member_evaluate')->validationVr($order_id, $_SESSION['member_id']);
        if (!$return['state']) {
            showMessage($return['msg'],'index.php?act=member_vr_order','html','error');
        }
        extract($return['data']);
        //判断是否为页面
        if (!$_POST){
            $order_goods[] = $order_info;
            //处理积分、经验值计算说明文字
            $ruleexplain = '';
            $exppoints_rule = C("exppoints_rule")?unserialize(C("exppoints_rule")):array();
            $exppoints_rule['exp_comments'] = intval($exppoints_rule['exp_comments']);
            $points_comments = intval(C('points_comments'));
            if ($exppoints_rule['exp_comments'] > 0 || $points_comments > 0){
                $ruleexplain .= '评价完成将获得';
                if ($exppoints_rule['exp_comments'] > 0){
                    $ruleexplain .= (' “'.$exppoints_rule['exp_comments'].'经验值”');
                }
                if ($points_comments > 0){
                    $ruleexplain .= (' “'.$points_comments.'积分”');
                }
                $ruleexplain .= '。';
            }
            Tpl::output('ruleexplain', $ruleexplain);

            //不显示左菜单
            Tpl::output('left_show','order_view');
            Tpl::output('order_info',$order_info);
            Tpl::output('order_goods',$order_goods);
            Tpl::output('store_info',$store_info);
            Tpl::showpage('evaluation.add');
        }else {
            $return = Logic('member_evaluate')->saveVr($_POST, $order_info, $store_info, $_SESSION['member_id'], $_SESSION['member_name']);
            if (!$return['state']) {
                showDialog($return['msg'],'reload','error');
            } else {
                showDialog(Language::get('member_evaluation_evaluat_success'),'index.php?act=member_vr_order', 'succ');
            }
        }
    }

    /**
     * 评价列表
     */
    public function listOp(){
        $model_evaluate_goods = Model('evaluate_goods');

        $condition = array();
        $condition['geval_frommemberid'] = $_SESSION['member_id'];
        $goodsevallist = $model_evaluate_goods->getEvaluateGoodsList($condition, 10);
        Tpl::output('goodsevallist',$goodsevallist);
        Tpl::output('show_page',$model_evaluate_goods->showpage());

        Tpl::showpage('evaluation.index');
    }

}
