<?php
/**
 * 签到
 * 
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');
class signinModel extends Model {
    public function __construct(){
        parent::__construct();
    }
    /**
     * 新增签到信息
     */
    public function addSignin($param){
        if((!$param['member_id']) || (!$param['member_name']) || (!$param['points'])){
        	return false;
        }
        $insert_arr = array();
        $insert_arr['sl_memberid'] = $param['member_id'];
        $insert_arr['sl_membername'] = $param['member_name'];
        $insert_arr['sl_addtime'] = time();
        $insert_arr['sl_points'] = $param['points'];
        return $this->table('signin')->insert($insert_arr);
    }
    /**
     * 查询签到数
     */
    public function getSigninCount($where, $group = ''){
        return $this->table('signin')->where($where)->group($group)->count();
    }
    /**
     * 检验是否能签到
     * @param int $member_id 会员ID
     */
    public function isAbleSignin($member_id){
        if (!$member_id) {
            return array('done'=>false,'msg'=>'参数错误');
        }
        $where = array();
        $where['sl_memberid'] = $member_id;
        $stime = strtotime(date('Y-m-d',time()));
        $etime = $stime + 86400;
        $where['sl_addtime'] = array('between',array($stime,$etime));
        $signin_count = $this->getSigninCount($where);
        if ($signin_count > 0) {
            return array('done'=>false,'msg'=>'已签到');
        }
        return array('done'=>true);
    }
    /**
     * 获得签到日志
     */
    public function getSigninList($where, $field = '*', $limit = 0, $page = 0, $order = '', $group = '') {
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('signin')->field($field)->where($where)->limit($limit)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                $list = $this->table('signin')->field($field)->where($where)->limit($limit)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('signin')->field($field)->where($where)->limit($limit)->page($page)->order($order)->group($group)->select();
        }
        return $list;
    }
}