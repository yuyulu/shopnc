<?php
/**
 * 积分及积分日志管理
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class pointsModel extends Model {
    private $stage_arr;
    public function __construct(){
        parent::__construct();
        $this->stage_arr = array('regist'=>'注册','login'=>'登录','comments'=>'商品评论','order'=>'订单消费','system'=>'积分管理','pointorder'=>'礼品兑换','app'=>'积分兑换','signin'=>'签到');
    }
    /**
     * 操作积分
     * @author 33Hao Develop Team
     * @param  string $stage 操作阶段 regist(注册),login(登录),comments(评论),order(下单),system(系统),other(其他),pointorder(积分礼品兑换),app(同步积分兑换)
     * @param  array $insertarr 该数组可能包含信息 array('pl_memberid'=>'会员编号','pl_membername'=>'会员名称','pl_adminid'=>'管理员编号','pl_adminname'=>'管理员名称','pl_points'=>'积分','pl_desc'=>'描述','orderprice'=>'订单金额','order_sn'=>'订单编号','order_id'=>'订单序号','point_ordersn'=>'积分兑换订单编号');
     * @param  bool $if_repeat 是否可以重复记录的信息,true可以重复记录，false不可以重复记录，默认为true
     * @return bool
     */
    function savePointsLog($stage,$insertarr,$if_repeat = true){
        if (!$insertarr['pl_memberid']){
            return false;
        }
        //记录原因文字
        switch ($stage){
            case 'regist':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '注册会员';
                }
                $insertarr['pl_points'] = intval(C('points_reg'));
                break;
            case 'login':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '会员登录';
                }
                $insertarr['pl_points'] = intval(C('points_login'));
                break;
            case 'comments':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '评论商品';
                }
                $insertarr['pl_points'] = intval(C('points_comments'));
                break;
            case 'order':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '订单'.$insertarr['order_sn'].'购物消费';
                }
                $insertarr['pl_points'] = 0;
                if ($insertarr['orderprice']){
                    $insertarr['pl_points'] = @intval($insertarr['orderprice']/C('points_orderrate'));
                    if ($insertarr['pl_points'] > intval(C('points_ordermax'))){
                        $insertarr['pl_points'] = intval(C('points_ordermax'));
                    }
                }
                //订单添加赠送积分列
                $obj_order = Model('order');
                $data = array();
                $data['order_pointscount'] = array('exp','order_pointscount+'.$insertarr['pl_points']);
                $obj_order->editOrderCommon($data,array('order_id'=>$insertarr['order_id']));
                break;
            case 'system':
                break;
            case 'pointorder':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '兑换礼品信息'.$insertarr['point_ordersn'].'消耗积分';
                }
                break;
            case 'app':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = Language::get('points_pointorderdesc_app');
                }
                break;
            case 'signin':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '签到领积分';
                }
				break;
			//邀请积分返利 33 hao.co m v5
			case 'inviter':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = '邀请新会员['.$insertarr['invited'].']注册';
				}
				$insertarr['pl_points'] = intval($GLOBALS['setting_config']['points_invite']);
				break;
			case 'rebate':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = '被邀请人['.$_SESSION['member_name'].']消费';
				}
				$insertarr['pl_points'] = $insertarr['rebate_amount'];
                break;
            case 'other':
                break;
        }
        $save_sign = true;
        if ($if_repeat == false){
            //检测是否有相关信息存在，如果没有，入库
            $condition['pl_memberid'] = $insertarr['pl_memberid'];
            $condition['pl_stage'] = $stage;
            $log_array = self::getPointsInfo($condition);
            if (!empty($log_array)){
                $save_sign = false;
            }
        }
        if ($save_sign == false){
            return true;
        }
        //新增日志
        $value_array = array();
        $value_array['pl_memberid'] = $insertarr['pl_memberid'];
        $value_array['pl_membername'] = $insertarr['pl_membername'];
        if ($insertarr['pl_adminid']){
            $value_array['pl_adminid'] = $insertarr['pl_adminid'];
        }
        if ($insertarr['pl_adminname']){
            $value_array['pl_adminname'] = $insertarr['pl_adminname'];
        }
        $value_array['pl_points'] = $insertarr['pl_points'];
        $value_array['pl_addtime'] = time();
        $value_array['pl_desc'] = $insertarr['pl_desc'];
        $value_array['pl_stage'] = $stage;
        $result = false;
        if($value_array['pl_points'] != '0'){
            $result = self::addPointsLog($value_array);
        }
        if ($result){
            //更新member内容
            $obj_member = Model('member');
            $upmember_array = array();
            $upmember_array['member_points'] = array('exp','member_points+'.$insertarr['pl_points']);
            $obj_member->editMember(array('member_id'=>$insertarr['pl_memberid']),$upmember_array);
            return true;
        }else {
            return false;
        }

    }
    /**
     * 添加积分日志信息
     *
     * @param array $param 添加信息数组
     */
    public function addPointsLog($param) {
        if(empty($param)) {
            return false;
        }
        return $this->table('points_log')->insert($param);
    }
    /**
     * 积分日志列表
     *
     * @param array $condition 条件数组
     * @param array $page   分页
     * @param array $field   查询字段
     * @param array $page   分页
     */
    public function getPointsLogList($where, $field = '*', $limit = 0, $page = 0, $order = '', $group = ''){
        $order = $order ? $order : 'pl_id desc';
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('points_log')->field($field)->where($where)->page($page[0],$page[1])->limit($limit)->order($order)->group($group)->select();
            } else {
                $list = $this->table('points_log')->field($field)->where($where)->page($page[0])->limit($limit)->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('points_log')->field($field)->where($where)->page($page)->limit($limit)->order($order)->group($group)->select();
        }
        if ($list && is_array($list)){
            foreach ($list as $k=>$v){
                $v['stagetext'] = $this->stage_arr[$v['pl_stage']];
                $v['addtimetext'] = @date('Y-m-d',$v['pl_addtime']);
                $list[$k] = $v;
            }
        }
        return $list;
    }
    /**
     * 积分日志详细信息
     *
     * @param array $condition 条件数组
     * @param array $field   查询字段
     */
    public function getPointsInfo($where = array(), $field = '*', $order = '',$group = ''){
        $info = $this->table('points_log')->where($where)->field($field)->order($order)->group($group)->find();
        if (!$info){
            return array();
        }
        if($info['pl_stage']){
            $info['stagetext'] = $this->stage_arr[$info['pl_stage']];
        }
        if ($info['pl_addtime']) {
            $info['addtimetext'] = @date('Y-m-d',$info['pl_addtime']);
        }
        return $info;
    }
}
