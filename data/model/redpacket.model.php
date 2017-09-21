<?php
/**
 * 平台红包模型
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class redpacketModel extends Model {
        
    const GETTYPE_DEFAULT = 'points';//默认领取方式
    private $gettype_arr;
    private $templatestate_arr;
    private $redpacket_state_arr;
    
    public function __construct(){
        parent::__construct();
        //红包领取方式
        $this->gettype_arr = array('points'=>array('sign'=>1,'name'=>'积分兑换'),'pwd'=>array('sign'=>2,'name'=>'卡密兑换'),'free'=>array('sign'=>3,'name'=>'免费领取'));
        //红包模板状态
        $this->templatestate_arr = array('usable'=>array('sign'=>1,'name'=>'有效'),'disabled'=>array('sign'=>2,'name'=>'失效'));
        //红包状态
        $this->redpacket_state_arr = array('unused'=>array('sign'=>1,'name'=>'未使用'),'used'=>array('sign'=>2,'name'=>'已使用'),'expire'=>array('sign'=>3,'name'=>'已过期'));
    }

    /**
     * 取得当前有效红包数量
     * @param int $member_id
     */
    public function getCurrentAvailableRedpacketCount($member_id) {
        $info = rcache($member_id, 'm_redpacket', 'redpacket_count');
        if (empty($info['redpacket_count']) && $info['redpacket_count'] !== 0) {
            $condition['rpacket_owner_id'] = $member_id;
            $condition['rpacket_end_date'] = array('gt',TIMESTAMP);
            $condition['rpacket_state'] = 1;
            $redpacket_count = $this->table('redpacket')->where($condition)->count();
            $redpacket_count = intval($redpacket_count);
            wcache($member_id, array('redpacket_count' => $redpacket_count), 'm_redpacket');
        } else {
            $redpacket_count = intval($info['redpacket_count']);
        }
        return $redpacket_count;
    }

    /**
     * 获取红包模板状态数组
     */
    public function getTemplateState(){
        return $this->templatestate_arr;
    }
    /**
     * 获取红包状态数组
     */
    public function getRedpacketState(){
        return $this->redpacket_state_arr;
    }
    /**
     * 返回红包领取方式数组
     * @return array
     */
    public function getGettypeArr() {
        return $this->gettype_arr;
    }
    /**
     * 新增红包模板
     */
    public function addRptTemplate($param){
        if(!$param){
        	return false;
        }
        return $this->table('redpacket_template')->insert($param);
    }
    /**
     * 查询红包模板列表
     */
    public function getRptTemplateList($where, $field = '*', $limit = 0, $page = 0, $order = '', $group = '') {
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('redpacket_template')->field($field)->where($where)->limit($limit)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                $list = $this->table('redpacket_template')->field($field)->where($where)->limit($limit)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('redpacket_template')->field($field)->where($where)->limit($limit)->page($page)->order($order)->group($group)->select();
        }
        //会员级别
        $member_grade = Model('member')->getMemberGradeArr();
    
        if (!empty($list) && is_array($list)){
            foreach ($list as $k=>$v){
                if (!empty($v['rpacket_t_customimg'])){
                    $v['rpacket_t_customimg_url'] = UPLOAD_SITE_URL.DS.ATTACH_REDPACKET.DS.$v['rpacket_t_customimg'];
                }else{
                    $v['rpacket_t_customimg_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
                }
                //领取方式
                if($v['rpacket_t_gettype']){
                    foreach($this->gettype_arr as $gtype_k=>$gtype_v){
                        if($v['rpacket_t_gettype'] == $gtype_v['sign']){
                            $v['rpacket_t_gettype_key'] = $gtype_k;
                            $v['rpacket_t_gettype_text'] = $gtype_v['name'];
                        }
                    }
                }
                //状态
                if($v['rpacket_t_state']){
                    foreach($this->templatestate_arr as $tstate_k=>$tstate_v){
                        if($v['rpacket_t_state'] == $tstate_v['sign']){
                            $v['rpacket_t_state_text'] = $tstate_v['name'];
                        }
                    }
                }
                //会员等级
                $v['rpacket_t_mgradelimittext'] = $member_grade[$v['rpacket_t_mgradelimit']]['level_name'];
    
                $list[$k] = $v;
            }
        }
        return $list;
    }
    /**
     * 获得红包模板详情
     */
    public function getRptTemplateInfo($where = array(), $field = '*', $order = '',$group = '') {
        $info = $this->table('redpacket_template')->where($where)->field($field)->order($order)->group($group)->find();
        if (!$info){
        	return array();
        }
        if($info['rpacket_t_gettype']){
            foreach($this->gettype_arr as $k=>$v){
                if($info['rpacket_t_gettype'] == $v['sign']){
                    $info['rpacket_t_gettype_key'] = $k;
                    $info['rpacket_t_gettype_text'] = $v['name'];
                }
            }
        }
        if($info['rpacket_t_state']){
            foreach($this->templatestate_arr as $k=>$v){
                if($info['rpacket_t_state'] == $v['sign']){
                    $info['rpacket_t_state_text'] = $v['name'];
                }
            }
        }
        if (!empty($info['rpacket_t_customimg'])){
            $info['rpacket_t_customimg_url'] = UPLOAD_SITE_URL.DS.ATTACH_REDPACKET.DS.$info['rpacket_t_customimg'];
        }else{
            $info['rpacket_t_customimg_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
        }
        //会员等级
        $member_grade = Model('member')->getMemberGradeArr();
        $info['rpacket_t_mgradelimittext'] = $member_grade[$info['rpacket_t_mgradelimit']]['level_name'];
        return $info;
    }
    
    /**
     * 更新红包模板信息
     * @param array $data
     * @param array $condition
     */
    public function editRptTemplate($where,$data) {
        return $this->table('redpacket_template')->where($where)->update($data);
    }
    
    /**
     * 删除红包模板信息
     * @param array $data
     * @param array $condition
     */
    public function dropRptTemplate($where) {
        $info = $this->getRptTemplateInfo($where);
        if (!$info){
        	return false;
        }
        $result = $this->table('redpacket_template')->where($where)->delete($where);
        if ($result){
            //删除旧图片
            if ($info['rpacket_t_customimg'] && is_file(BASE_UPLOAD_PATH . '/' . ATTACH_REDPACKET . '/' . $info['rpacket_t_customimg'])) {
                @unlink(BASE_UPLOAD_PATH . '/' . ATTACH_REDPACKET . '/' . $info['rpacket_t_customimg']);
                @unlink(BASE_UPLOAD_PATH . '/' . ATTACH_REDPACKET . '/' . str_ireplace('.', '_small.', $info['rpacket_t_customimg']));
            }
        }
        return $result;
    }
    
    /*
     * 获取红包编码
     * */
    public function get_rpt_code($member_id = 0){
        static $num = 1;
        $sign_arr = array();
        $sign_arr[] = sprintf('%02d',mt_rand(10,99));
        $sign_arr[] = sprintf('%03d', (float) microtime() * 1000);
        $sign_arr[] = sprintf('%010d',time() - 946656000);
        if($member_id){
            $sign_arr[] = sprintf('%03d', (int) $member_id % 1000);
        } else {
            //自增变量
            $tmpnum = 0;
            if ($num > 99){
                $tmpnum = substr($num, -1, 2);
            } else {
                $tmpnum = $num;
            }
            $sign_arr[] = sprintf('%02d',$tmpnum);
            $sign_arr[] = mt_rand(1,9);
        }
        $code = implode('',$sign_arr);
        $num += 1;
        return $code;
    }

    /**
     * 返回当前可用的红包列表,每种类型(模板)的红包里取出一个红包(同一个模板所有码面额和到期时间都一样)
     * @param array $condition 条件
     * @param array $goods_total 商品总金额
     * @return string
     */
    public function getCurrentAvailableRpt($condition = array(), $goods_total = 0, $order = '') {
        $condition['rpacket_end_date'] = array('egt',TIMESTAMP);
        $condition['rpacket_start_date'] = array('elt',TIMESTAMP);
        $condition['rpacket_state'] = 1;
        $rpt_list = $this->table('redpacket')->field('rpacket_id,rpacket_end_date,rpacket_price,rpacket_limit,rpacket_t_id,rpacket_code,rpacket_owner_id')->where($condition)->order($order)->key('rpacket_t_id')->select();
        foreach ($rpt_list as $key => $rpt) {
            if ($goods_total > 0 && $goods_total < $rpt['rpacket_limit']) {
                unset($rpt_list[$key]);
            } else {
                $rpt_list[$key]['desc'] = sprintf('%s元红包 有效期至 %s',$rpt['rpacket_price'],date('Y-m-d',$rpt['rpacket_end_date']));
                if ($rpt['rpacket_limit'] > 0) {
                    $rpt_list[$key]['desc'] .= sprintf(' 消费满%s可用',$rpt['rpacket_limit']);
                }

            }
        }
        return $rpt_list;
    }

    /**
     * 生成红包卡密
     */
    public function create_rpt_pwd($t_id) {
        if($t_id <= 0){
            return false;
        }
        static $num = 1;
        $sign_arr = array();
        //时间戳
        $time_tmp = uniqid('', true);
        $time_tmp = explode('.',$time_tmp);
        $sign_arr[] = substr($time_tmp[0], -1, 4).$time_tmp[1];
        //自增变量
        $tmpnum = 0;
        if ($num > 999){
            $tmpnum = substr($num, -1, 3);
        } else {
            $tmpnum = $num;
        }
        $sign_arr[] = sprintf('%03d',$tmpnum);
        //红包模板ID
        if($t_id > 9999){
            $t_id = substr($num, -1, 4);
        }
        $sign_arr[] = sprintf('%04d',$t_id);
        //随机数
        $sign_arr[] = sprintf('%04d',rand(1,9999));
        $pwd = implode('',$sign_arr);
        $num += 1;
        return array(md5($pwd), encrypt($pwd));
    }
    /**
     * 获取红包卡密
     */
    public function get_rpt_pwd($pwd) {
        if (!$pwd){
            return '';
        }
        $pwd = decrypt($pwd);
        $pattern = "/^([0-9]{20})$/i";
        if (preg_match($pattern, $pwd)){
            return $pwd;
        } else {
            return '';
        }
    }
    /**
     * 批量增加红包
     */
    public function addRedpacketBatch($insert_arr){
        return $this->table('redpacket')->insertAll($insert_arr);
    }
    /**
     * 增加红包
     */
    public function addRedpacket($insert_arr){
        return $this->table('redpacket')->insert($insert_arr);
    }
    /**
     * 获得红包列表
     */
    public function getRedpacketList($where, $field = '*', $limit = 0, $page = 0, $order = '', $group = ''){
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('redpacket')->field($field)->where($where)->limit($limit)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                $list = $this->table('redpacket')->field($field)->where($where)->limit($limit)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('redpacket')->field($field)->where($where)->limit($limit)->page($page)->order($order)->group($group)->select();
        }
        if (!empty($list) && is_array($list)){
            foreach ($list as $k=>$v){
                if (!empty($v['rpacket_customimg'])){
                    $v['rpacket_customimg_url'] = UPLOAD_SITE_URL.DS.ATTACH_REDPACKET.DS.$v['rpacket_customimg'];
                }else{
                    $v['rpacket_customimg_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
                }
                foreach ($this->redpacket_state_arr as $state_k=>$state_v){
                    if ($state_v['sign'] == $v['rpacket_state']){
                    	$v['rpacket_state_text'] = $state_v['name'];
                    	$v['rpacket_state_key'] = $state_k;
                    }
                }
                $v['rpacket_start_date_text'] = @date('Y-m-d',$v['rpacket_start_date']);
                $v['rpacket_end_date_text'] = @date('Y-m-d',$v['rpacket_end_date']);
                $list[$k] = $v;
            }
        }
        return $list;
    }
    
    /**
     * 获得红包详情
     */
    public function getRedpacketInfo($where = array(), $field = '*', $order = '',$group = '') {
        $info = $this->table('redpacket')->where($where)->field($field)->order($order)->group($group)->find();
        if($info['rpacket_state']){
            foreach ($this->redpacket_state_arr as $state_k=>$state_v){
                if ($state_v['sign'] == $info['rpacket_state']){
                    $info['rpacket_state_text'] = $state_v['name'];
                    $info['rpacket_state_key'] = $state_k;
                }
            }
            if (!empty($info['rpacket_customimg'])){
                $info['rpacket_customimg_url'] = UPLOAD_SITE_URL.DS.ATTACH_REDPACKET.DS.$info['rpacket_customimg'];
            }else{
                $info['rpacket_customimg_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
            }
        }
        return $info;
    }
    /**
     * 更新过期红包状态
     */
    public function updateRedpacketExpire($member_id){
        $where = array();
        $where['rpacket_owner_id'] = $member_id;
        $where['rpacket_state'] = $this->redpacket_state_arr['unused']['sign'];
        $where['rpacket_end_date'] = array('lt', TIMESTAMP);
        $this->table('redpacket')->where($where)->update(array('rpacket_state'=>$this->redpacket_state_arr['expire']['sign']));
        //清空缓存
        dcache($member_id, 'm_redpacket');
    }
    
    /**
     * 获得推荐的红包列表
     * @param int $num 查询条数
     */
    public function getRecommendRpt($num){
        //查询推荐的热门红包列表
        $where = array();
        $where['rpacket_t_state'] = $this->templatestate_arr['usable']['sign'];
        //领取方式为积分兑换
        $where['rpacket_t_gettype'] = $this->gettype_arr['points']['sign'];
        //$where['rpacket_t_start_date'] = array('elt',time());
        $where['rpacket_t_end_date'] = array('egt',time());
        $recommend_rpt = $this->getRptTemplateList($where, $field = '*', $num, 0, 'rpacket_t_recommend desc,rpacket_t_id desc');
        return $recommend_rpt;
    }
    /**
     * 获得红包总数量
     */
    public function getRedpacketCount($where, $group = ''){
        return $this->table('redpacket')->where($where)->group($group)->count();
    }
    
    /**
     * 更新红包信息
     * @param array $data
     * @param array $condition
     */
    public function editRedpacket($where, $data, $member_id = 0) {
        $result = $this->table('redpacket')->where($where)->update($data);
        if ($result && $member_id > 0){
            wcache($member_id, array('redpacket_count' => null), 'm_redpacket');
        }
        return $result;
    }
    
    /**
     * 查询可兑换红包模板详细信息
     */
    public function getCanChangeTemplateInfo($tid,$member_id){
        if ($tid <= 0 || $member_id <= 0){
            return array('state'=>false,'msg'=>'参数错误');
        }
        //查询可用红包模板
        $where = array();
        $where['rpacket_t_id']          = $tid;
        $where['rpacket_t_state']       = $this->templatestate_arr['usable']['sign'];
        //$where['rpacket_t_start_date']  = array('elt',time());
        $where['rpacket_t_end_date']    = array('egt',time());
        $template_info = $this->getRptTemplateInfo($where);
        if (empty($template_info) || $template_info['rpacket_t_total']<=$template_info['rpacket_t_giveout']){//红包不存在或者已兑换完
            return array('state'=>false,'msg'=>'红包已兑换完');
        }
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($member_id);
        if (empty($member_info)){
            return array('state'=>false,'msg'=>'参数错误');
        }
        //验证会员积分是否足够
        if ($template_info['rpacket_t_gettype'] == $this->gettype_arr['points']['sign'] && $template_info['rpacket_t_points'] > 0){
            if (intval($member_info['member_points']) < intval($template_info['rpacket_t_points'])){
                return array('state'=>false,'msg'=>'您的积分不足，暂时不能兑换该红包');
            }
        }
        //验证会员级别
        $member_currgrade = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
        $member_info['member_currgrade'] = $member_currgrade?$member_currgrade['level']:0;
        if ($member_info['member_currgrade'] < intval($template_info['rpacket_t_mgradelimit'])){
            return array('state'=>false,'msg'=>'您的会员级别不够，暂时不能兑换该红包');
        }
        //查询红包列表
        $where = array();
        $where['rpacket_t_id']      = $tid;
        $where['rpacket_owner_id']  = $member_id;
        $redpacket_count = $this->getRedpacketCount($where);
        //同一张红包最多能兑换的次数
        if (intval($template_info['rpacket_t_eachlimit']) > 0 && $redpacket_count >= intval($template_info['rpacket_t_eachlimit'])){
            $message = sprintf('该红包您已兑换%s次，不可再兑换了',$template_info['rpacket_t_eachlimit']);
            return array('state'=>false,'msg'=>$message);
        }
        return array('state'=>true,'info'=>$template_info);
    }
    
    /**
     * 积分兑换红包
     */
    public function exchangeRedpacket($template_info, $member_id, $member_name = ''){
        if (intval($member_id) <= 0 || empty($template_info)){
            return array('state'=>false,'msg'=>'参数错误');
        }
        //查询会员信息
        if (!$member_name){
            $member_info = Model('member')->getMemberInfoByID($member_id);
            if (empty($template_info)){
                return array('state'=>false,'msg'=>'参数错误');
            }
            $member_name = $member_info['member_name'];
        }
        //添加红包信息
        $insert_arr = array();
        $insert_arr['rpacket_code'] = $this->get_rpt_code($member_id);
        $insert_arr['rpacket_t_id'] = $template_info['rpacket_t_id'];
        $insert_arr['rpacket_title'] = $template_info['rpacket_t_title'];
        $insert_arr['rpacket_desc'] = $template_info['rpacket_t_desc'];
        $insert_arr['rpacket_start_date'] = $template_info['rpacket_t_start_date'];
        $insert_arr['rpacket_end_date'] = $template_info['rpacket_t_end_date'];
        $insert_arr['rpacket_price'] = $template_info['rpacket_t_price'];
        $insert_arr['rpacket_limit'] = $template_info['rpacket_t_limit'];
        $insert_arr['rpacket_state'] = $this->redpacket_state_arr['unused']['sign'];
        $insert_arr['rpacket_active_date'] = time();
        $insert_arr['rpacket_owner_id'] = $member_id;
        $insert_arr['rpacket_owner_name'] = $member_name;
        $insert_arr['rpacket_customimg'] = $template_info['rpacket_t_customimg'];
        $result = $this->addRedpacket($insert_arr);
        if (!$result){
            return array('state'=>false,'msg'=>'兑换失败');
        }
        //扣除会员积分
        if ($template_info['rpacket_t_points'] > 0 && $template_info['rpacket_t_gettype'] == $this->gettype_arr['points']['sign']){
            $points_arr['pl_memberid'] = $member_id;
            $points_arr['pl_membername'] = $member_name;
            $points_arr['pl_points'] = -$template_info['rpacket_t_points'];
            $points_arr['pl_desc'] = '红包'.$insert_arr['rpacket_code'].'消耗积分';
            $result = Model('points')->savePointsLog('app',$points_arr,true);
            if (!$result){
                return array('state'=>false,'msg'=>'兑换失败');
            }
        }
        if ($result){
            //红包模板的兑换数增加
            $result = $this->editRptTemplate(array('rpacket_t_id'=>$template_info['rpacket_t_id']), array('rpacket_t_giveout'=>array('exp','rpacket_t_giveout+1')));
            if (!$result){
                return array('state'=>false,'msg'=>'兑换失败');
            }
            wcache($member_id, array('redpacket_count' => array('exp','redpacket_count+1')), 'm_redpacket');
            return array('state'=>true,'msg'=>'兑换成功');
        } else {
            return array('state'=>false,'msg'=>'兑换失败');
        }
    }
}